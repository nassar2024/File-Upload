<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\ApiController;

Route::get('/products', [ProductController::class, 'index']);
Route::post('/upload', [ApiController::class, 'upload']);
Route::get('/job-status/{jobId}', [ApiController::class, 'checkJobStatus']);