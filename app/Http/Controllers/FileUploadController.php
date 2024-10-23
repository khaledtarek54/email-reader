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
        // Validate the request
        $request->validate([
            'email_id' => 'required',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Adjust mime types and max size as needed
        ]);

        // Retrieve the email ID and file from the request
        $emailId = $request->input('email_id');
        $file = $request->file('file');

        // Use the service to upload the file and set permissions
        $filePath = $this->fileUploadService->uploadFile($emailId, $file);

        // Return a JSON response with the uploaded file details
        return response()->json([
            'message' => 'File uploaded successfully!',
            'email_id' => $emailId,
            'file_path' => $filePath,
        ]);
    }
}