<?php

namespace App\Services;

use App\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function store(UploadedFile $uploadedFile): File
    {
        return DB::transaction(function () use ($uploadedFile) {
            $name = Storage::disk('local')->putFile('/', $uploadedFile);

            $file = File::newInstanceForUploadFile($uploadedFile, $name);

            $file->save();

            return $file;
        });
    }
}
