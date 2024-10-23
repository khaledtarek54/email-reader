<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function uploadFile($emailId, $file)
    {
        // Define the storage path based on the email_id
        $path = "uploads/{$emailId}";

        // Create the directory if it doesn't exist and set 0777 permission
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
            // Set 0777 permission for the folder
            @chmod(storage_path("app/$path"), 0777);
        }

        // Store the file in the directory
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs($path, $fileName);

        // Set 0777 permission for the file
        @chmod(storage_path("app/$filePath"), 0777);

        // Return the file path
        return $filePath;
    }
}
