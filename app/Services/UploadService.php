<?php

namespace App\Services;

use App\File;
use App\Jobs\UploadFileJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public function store(UploadedFile $uploadedFile): string
    {
        $prefix = config('filesystems.disks.b2.asset_prefix');
        $file = File::newInstanceForUploadFile($uploadedFile);
        $extension = $file->extension;
        $file->save();

        if (Str::contains($file->type, 'image')) {
            $base64File = base64_encode(file_get_contents($uploadedFile));
            UploadFileJob::dispatch($base64File, $file->name, $extension);
        } else {
            Storage::disk('b2')->putFileAs('/', $uploadedFile, $file->name);
        }

        return $prefix . $file->name;
    }
}
