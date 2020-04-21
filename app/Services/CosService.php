<?php

namespace App\Services;

use App\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CosService
{
    public static function store(UploadedFile $uploadedFile): File
    {
        return DB::transaction(function () use ($uploadedFile) {
            $name = Storage::putFile('/', $uploadedFile);

            $file = File::newInstanceForUploadFile($uploadedFile, $name);

            $file->save();

            return $file;
        });
    }

    /**
     * Build full url for given file name that stored on tencent cos
     *
     * @param string $filename
     * @return string
     */
    public static function url(string $filename): string
    {
        return 'https://' . Storage::url("/{$filename}");
    }
}
