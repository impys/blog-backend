<?php

namespace App\Services;

use App\File;
use App\Jobs\UploadFileJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    const END_POINT = 'https://asset.moreless.blog/file/moreless-public/';

    public function store(UploadedFile $uploadedFile): string
    {
        $file = File::newInstanceForUploadFile($uploadedFile);
        $file->save();

        if (Str::contains($file->type, 'image')) {
            $base64File = base64_encode(file_get_contents($uploadedFile));
            UploadFileJob::dispatch($base64File, $file->name);
        } else {
            Storage::disk('b2')->putFileAs('/', $uploadedFile, $file->name);
        }

        return self::END_POINT . $file->name;
    }
}
