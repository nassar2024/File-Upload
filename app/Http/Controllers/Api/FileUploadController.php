<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessProductStatus;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('uploads');
        $absolutePath = storage_path('app/' . $filePath);

        try {
            $spreadsheet = IOFactory::load($absolutePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) < 2) {
                return response()->json(['message' => 'Excel file is empty or lacks data.'], 422);
            }

            $headers = array_map('strtolower', $rows[1]);

            if (!in_array('product_id', $headers) || !in_array('status', $headers) || !in_array('quantity', $headers)) {
                return response()->json(['message' => 'Invalid Excel file structure. Required columns: product_id, status, quantity.'], 422);
            }

            // All checks passed – dispatch job
            ProcessProductStatus::dispatch($absolutePath);

            return response()->json(['message' => 'File uploaded successfully. Processing in background.']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error reading Excel file: ' . $e->getMessage()], 422);
        }
    }
}
