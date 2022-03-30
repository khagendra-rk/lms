<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public static function upload(UploadedFile $file, string $folder = "uploads"): string
    {
        $extension = $file->extension();
        $type = $file->getMimeType();
        $path = Str::random(10) . mt_rand(1, 100) . "." . $extension;

        // Store File
        $file->storeAs("public/" . $folder, $path);

        return "$folder/$path";
    }
}
