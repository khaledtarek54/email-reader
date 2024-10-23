<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

// Define API routes

    Route::post('/upload', [FileUploadController::class, 'upload']);
    // You can add more API routes here as needed


// Optionally, you can define public routes
Route::get('/public-endpoint', function () {
    return response()->json(['message' => 'This is a public endpoint']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});
