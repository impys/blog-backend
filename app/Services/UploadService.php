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

            $uploadedFile->storeAs('/public/assets/', $file->name);

            $file->save();

            return $file;
        });
    }
}
