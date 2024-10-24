<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\FileUploadService;
use Illuminate\Routing\Controller;

class FileUploadController extends Controller
{
    protected $fileUploadService;

    // Inject the FileUploadService
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function uploadMethod(Request $request)
    {
    // Retrieve the email ID and files from the request
    $emailId = $request->input('email_id');
    $files = $request->file('file'); // Expecting an array of files
    
    // Use the service to upload the files and set permissions
    $filePaths = $this->fileUploadService->uploadFiles($emailId, $files);
    
    // Return a JSON response with the uploaded file details
    return response()->json([
    'message' => 'Files uploaded successfully!',
    'email_id' => $emailId,
    'file_paths' => $filePaths,
    ]);
    }
}