<?php

namespace App\Services;

use App\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UploadService
{
    public function store(UploadedFile $uploadedFile): File
    {
        return DB::transaction(function () use ($uploadedFile) {
            $file = File::newInstanceForUploadFile($uploadedFile);

            $file->save();

            $uploadedFile->storeAs('/public', $file->original_full_name);

            return $file;
        });
    }
}
