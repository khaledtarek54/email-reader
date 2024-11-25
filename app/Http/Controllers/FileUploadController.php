<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

use App\Services\FileUploadService;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Input\Input;

class FileUploadController extends Controller
{
    protected $fileUploadService;
    protected $mailService;

    // Inject the FileUploadService
    public function __construct(FileUploadService $fileUploadService, MailService $mailService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->mailService = $mailService;
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
    public function uploadFilesFromTP($id)
    {
        $mailId = $this->mailService->fetchAttachmentStatus($id);
        if ($mailId != null) {
            $fileData = $this->fileUploadService->getAndDecodeAttachment($id);
            //return $fileData;
            if (!$fileData) {
                return response()->json([
                    'message' => 'No files uploaded',
                    'email_id' => $id,
                ]);
            }
            if (is_array($fileData) && isset($fileData['success'])) {
                return response()->json([
                    'message' => 'No files uploaded',
                    'email_id' => $id,
                ]);
            }
            $this->fileUploadService->uploadFilesFromApiResponse($mailId, $fileData);
            $this->mailService->updateAttachmentStatus($mailId->mail_id);
            return response()->json([
                'message' => 'Files uploaded successfully!',
                'email_id' => $id,
            ]);
        }
        return response()->json([
            'message' => 'No files uploaded',
            'email_id' => $id,
        ]);
    }
    public function uploadedMailFiles($id)
    {

        $fileData = Cache::remember("mail_files_{$id}", 3600, function () use ($id) {
            return  $this->fileUploadService->getAndDecodeAttachment($id);
        });
        //$fileData =  $this->fileUploadService->getAndDecodeAttachment($id);
        //return $fileData;
        if (!$fileData) {
            return response()->json([
                'message' => 'No files uploaded',
                'email_id' => $id,
            ]);
        }
        if (is_array($fileData) && isset($fileData['success'])) {
            return response()->json([
                'message' => 'No files uploaded',
                'email_id' => $id,
            ]);
        }
        $filenames = collect($fileData)->pluck('filename');
        return response()->json([
            'message' => $filenames,
            'email_id' => $id,
        ]);
    }
}
