<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class File extends Model
{
    const STATUS_FAIL = 'fail';
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    protected $fillable = [
        'type',
        'name',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['name'])) {
            $attributes['name'] = self::generateName();
        }
        parent::__construct($attributes);
    }

    protected static function generateName(): string
    {
        return today()->format('ymd') . sprintf("%08d", random_int(0, 99999999));
    }

    public static function newInstanceForUploadFile(UploadedFile $uploadedFile): self
    {
        [$mime, $extension] = self::getExtensionAndMime($uploadedFile);

        $file = new File();
        $file->name = $file->name . '.' . $extension;
        $file->type = $mime;
        return $file;
    }

    protected static function getExtensionAndMime(UploadedFile $uploadedFile)
    {
        $mime = $uploadedFile->getClientMimeType();
        $extension = $uploadedFile->extension();
        if (Str::contains($mime, 'image')) {
            $mime = 'image/webp';
            $extension = 'webp';
        }

        return [$mime, $extension];
    }
}
