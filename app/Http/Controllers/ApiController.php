<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessProductStatus;
use App\Models\JobStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Store the file in a persistent location (e.g., storage/app/uploads)
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName); // Stores in storage/app/uploads
        $absolutePath = storage_path('app/' . $filePath); // Get the absolute path

        $jobId = uniqid(); // Generate unique job ID
        ProcessProductStatus::dispatch($absolutePath, $jobId)->onQueue('default');

        return response()->json(['jobId' => $jobId]);
    }

    public function checkJobStatus($jobId)
    {
        $jobStatus = JobStatus::where('job_id', $jobId)->first();

        if (!$jobStatus) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json(['status' => $jobStatus->status]);
    }
}