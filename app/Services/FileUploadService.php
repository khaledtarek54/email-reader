<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    protected $apiUrl;
    protected $apiLogService;
    public function __construct(ApiLogService $apiLogService)
    {
        $this->apiLogService = $apiLogService;
        $this->apiUrl = "https://stg.gotransparent.com/transparent_updates/mailAttachments.php";
    }
    public function uploadFiles($emailId, $files)
    {
        // Define the storage path based on the email_id
        $path = "uploads/{$emailId}";

        // Create the directory if it doesn't exist
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
            @chmod(storage_path("app/$path"), 0777);
        }

        // Initialize an array to store file paths
        $storedFiles = [];

        // Loop through each file and store it
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            // Check if a file with the same name exists in the database
            while (File::where('email_id', $emailId)->where('file_name', $fileName)->exists()) {
                // Add ".copy" before the file extension
                $fileInfo = pathinfo($fileName);
                $fileName = $fileInfo['filename'] . '-copy.' . $fileInfo['extension'];
            }

            // Store the file with the unique name
            $filePath = $file->storeAs($path, $fileName);

            // Set 0777 permission for the file
            @chmod(storage_path("app/$filePath"), 0777);

            // Store the file path and email ID in the database
            File::create([
                'email_id' => $emailId,
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

            // Add the file path to the array
            $storedFiles[] = $filePath;
        }

        // Return the array of file paths
        return $storedFiles;
    }
    public function uploadFilesFromApiResponse($emailId, $files)
    {
        // Convert base64 data to files
        $decodedFiles = [];
        foreach ($files as $fileData) {
            $filename = $fileData['filename'];
            $mimeType = $fileData['mimeType'];
            $base64Data = $fileData['data'];

            // Decode base64 data and save it to a temporary file
            $decodedContent = base64_decode($base64Data);
            $tempFilePath = sys_get_temp_dir() . '/' . $filename;

            file_put_contents($tempFilePath, $decodedContent);

            // Create an UploadedFile instance
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempFilePath,
                $filename,
                $mimeType,
                null,
                true // Indicates that this is a test file (it won't be uploaded via HTTP)
            );

            $decodedFiles[] = $uploadedFile;
        }

        // Call the existing uploadFiles method
        return $this->uploadFiles($emailId->mail_id, $decodedFiles);
    }
    public function getAndDecodeAttachment($mailId)
    {

        $response = Http::get($this->apiUrl, [
            'mail_id' => $mailId
        ]);

        $this->apiLogService->log(
            $this->apiUrl,
            'post',
            ['mail_id' => $mailId],
            $response->json(),
            $response->status()
        );
        // Check if the request was successful
        if ($response->successful()) {
            // Decode the JSON response
            $responseData = $response->json();
            return $responseData;
        } else {
            return response()->json(['error' => 'Failed to fetch data from the API'], 500);
        }
    }
}
