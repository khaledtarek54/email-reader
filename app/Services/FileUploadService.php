<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\File;

class FileUploadService
{
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
}
