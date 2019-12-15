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
        'extension',
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

    public function scopeOfName($query, string $name)
    {
        return $query->where('name', $name);
    }

    protected static function generateName(): string
    {
        return today()->format('ymd') . sprintf("%08d", random_int(0, 99999999));
    }

    public static function newInstanceForUploadFile(UploadedFile $uploadedFile): self
    {
        $mime = $uploadedFile->getClientMimeType();
        $extension = $uploadedFile->extension();

        $file = new File();
        $file->name = $file->name . '.' . $extension;
        $file->type = $mime;
        $file->extension = $extension;
        return $file;
    }

    public static function updateStatusSuccess(string $name)
    {
        $self = self::ofName($name)->first();
        $self->status = self::STATUS_SUCCESS;
        $self->save();
    }

    public static function updateStatusFail(string $name)
    {
        $self = self::ofName($name)->first();
        $self->status = self::STATUS_FAIL;
        $self->save();
    }
}
