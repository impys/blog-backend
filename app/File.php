<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    const TYPE_AUDIO = 'audio';
    const TYPE_IMAGE = 'image';

    protected $fillable = [
        'type',
        'mime',
        'name',
        'size',
    ];

    protected $appends = [
        'url',
        'markdown_dom',
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->name);
    }

    public function getMarkdownDomAttribute(): ?string
    {
        if ($this->type === self::TYPE_AUDIO) {
            return ":::music $this->url\n:::";
        }
        if ($this->type === self::TYPE_IMAGE) {
            return "![]({$this->url})";
        }
        return null;
    }

    public function scopeOfName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function scopeInNames($query, array $names)
    {
        return $query->whereIn('name', $names);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfSort($query, int $sort)
    {
        return $query->where('sort', $sort);
    }

    public static function newInstanceForUploadFile(UploadedFile $uploadedFile, string $name): self
    {
        $mime = $uploadedFile->getClientMimeType();

        $type = explode('/', $mime)[0];

        $size = $uploadedFile->getSize();

        $file = new File([
            'mime' => $mime,
            'type' => $type,
            'size' => $size,
            'name' => $name,
        ]);

        return $file;
    }
}
