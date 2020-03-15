<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    const TYPE_AUDIO = 'audio';
    const TYPE_IMAGE = 'image';

    protected $fillable = [
        'type',
        'mime',
        'name',
        'size',
        'width',
        'height',
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
        return Storage::disk('public')->url("assets/{$this->name}");
    }

    public function getMarkdownDomAttribute(): ?string
    {
        if ($this->type === self::TYPE_AUDIO) {
            return "<audio controls><source src=\"{$this->url}\"></audio>\n";
        }
        if ($this->type === self::TYPE_IMAGE) {
            return "![]({$this->url})\n";
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

    protected static function generateNamePrefix(): string
    {
        return today()->format('ymd') . sprintf("%08d", random_int(0, 99999999));
    }

    public static function newInstanceForUploadFile(UploadedFile $uploadedFile): self
    {
        $mime = $uploadedFile->getClientMimeType();

        $type = explode('/', $mime)[0];

        $size = $uploadedFile->getSize();

        $ext = $uploadedFile->clientExtension();

        $name = self::generateNamePrefix() . '.' . $ext;

        $file = new File([
            'mime' => $mime,
            'type' => $type,
            'size' => $size,
            'name' => $name,
        ]);

        return $file;
    }

    protected function getPhysicalPath(): string
    {
        return storage_path("app/public/assets/{$this->name}");
    }

    public function syncWidthAndHeight(): bool
    {
        [$width, $height] = getimagesize($this->getPhysicalPath());

        $this->width = $width;
        $this->height = $height;
        return $this->save();
    }
}
