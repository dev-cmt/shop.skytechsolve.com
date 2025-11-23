<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Upload an image safely.
     *
     * @param UploadedFile|null $file
     * @param string $folder
     * @param string|null $existingPath
     * @return string|null
     */
    public static function uploadImage($file, string $folder, string $existingPath = null): ?string
    {
        // Keep existing file if no new file is provided
        if (!$file instanceof UploadedFile) {
            return $existingPath;
        }

        // Delete old file if exists
        if ($existingPath && file_exists(public_path($existingPath))) {
            @unlink(public_path($existingPath));
        }

        // Ensure folder exists
        $directory = public_path($folder);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        // Sanitize and generate unique filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $uniqueName = Str::slug($originalName) . '_' . uniqid() . '.' . $extension;

        // Move file safely
        try {
            $file->move($directory, $uniqueName);
        } catch (\Exception $e) {
            // Optional: log error for debugging
            \Log::error("Image upload failed: " . $e->getMessage());
            return $existingPath; // fallback to old file
        }

        return $folder . '/' . $uniqueName;
    }

    /**
     * Delete an image safely.
     *
     * @param string|null $imagePath
     * @return bool
     */
    public static function deleteImage(?string $imagePath): bool
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return @unlink(public_path($imagePath));
        }
        return false;
    }
}
