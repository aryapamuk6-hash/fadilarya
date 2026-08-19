<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait HandlesUploads
{
    /**
     * Save an uploaded file to the `public` disk, falling back to
     * copying directly into public/storage if the disk is not writable.
     * Returns the relative path (e.g. "products/xyz.jpg").
     */
    protected function saveToPublic(UploadedFile $file, string $dir): string
    {
        try {
            $path = Storage::disk('public')->putFile($dir, $file);

            if ($path) {
                return $path;
            }
        } catch (\Throwable $e) {
            // fall through to manual copy
        }

        $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();
        $targetDir = public_path('storage/' . trim($dir, '/'));
        File::ensureDirectoryExists($targetDir);
        $file->move($targetDir, $filename);

        return trim($dir, '/') . '/' . $filename;
    }
}
