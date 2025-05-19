<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\ProductStatusImport;
use Illuminate\Support\Facades\Log;
use App\Models\JobStatus;

class ProcessProductStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $jobId;

    public function __construct($filePath, $jobId)
    {
        $this->filePath = $filePath;
        $this->jobId = $jobId;
    }

    public function handle()
    {
        try {
            // Initialize job status as 'processing'
            JobStatus::updateOrCreate(
                ['job_id' => $this->jobId],
                ['status' => 'processing', 'created_at' => now(), 'updated_at' => now()]
            );

            if (!file_exists($this->filePath)) {
                throw new \Exception("File {$this->filePath} does not exist.");
            }

            $spreadsheet = IOFactory::load($this->filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) < 2) {
                Log::warning("Excel file contains no data for job ID: {$this->jobId}.");
                $this->fail(new \Exception("Excel file contains no data."));
                return;
            }

            // Use the first row as headers
            $headers = array_map('strtolower', array_shift($rows));
            $mappedRows = array_map(function ($row) use ($headers) {
                return array_combine($headers, array_values($row));
            }, $rows);

            ProductStatusImport::process($mappedRows);

            // Update job status to 'completed'
            JobStatus::where('job_id', $this->jobId)->update(['status' => 'completed', 'updated_at' => now()]);
            Log::info("Job {$this->jobId} completed successfully.");

            if (file_exists($this->filePath)) {
                unlink($this->filePath); // Cleanup
            }
        } catch (\Exception $e) {
            // Update job status to 'failed' on error
            JobStatus::where('job_id', $this->jobId)->update(['status' => 'failed', 'updated_at' => now()]);
            Log::error("Error processing Excel file for job ID {$this->jobId}: " . $e->getMessage());
            throw $e;
        }
    }
}