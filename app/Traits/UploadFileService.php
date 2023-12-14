<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadFileService
{
    public function uploadFile(UploadedFile $file, $folder = null, $disk = 'local', $filename = null): bool|string
    {
        $FileName = !is_null($filename) ? $filename : Str::random(10);
        return $file->storeAs(
            $folder,
            $FileName . "." . $file->getClientOriginalExtension(),
            $disk
        );
    }

    public function deleteFile($path, $disk = 'local'): void
    {
        Storage::disk($disk)->delete($path);
    }
}
