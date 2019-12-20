<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    const STATUS_FAIL = 'fail';
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';

    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_IMAGE = 'image';

    const ENCODE_VIDEO_EXT = 'mp4';
    const ENCODE_AUDIO_EXT = 'mp3';
    const ENCODE_IMAGE_EXT = 'webp';

    protected $fillable = [
        'type',
        'mime',
        'name',
        'original_ext',
        'encode_ext',
        'status',
        'size',
        'width',
        'height',
    ];

    protected $appends = [
        'url',
        'original_full_name',
        'encode_full_name',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($self) {
            $self->name = self::generateName();
        });
    }

    /**
     * poster of video file related by self
     *
     * @return void
     */
    public function poster()
    {
        return $this->hasOne(self::class, 'id', 'poster_id');
    }

    /**
     * poster of video file related by self
     *
     * @return void
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * full url of this file
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        $assetPrefix = config('filesystems.disks.b2.asset_prefix');

        return $assetPrefix . $this->encode_full_name;
    }

    /**
     * name dot original extension of this file
     *
     * @return void
     */
    public function getOriginalFullNameAttribute(): string
    {
        return $this->name . '.' . $this->original_ext;
    }

    /**
     * name dot encoded extension of this file
     *
     * @return void
     */
    public function getEncodeFullNameAttribute(): string
    {
        return $this->name . '.' . $this->encode_ext;
    }

    public function scopeOfName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function scopeOfSort($query)
    {
        return $query->where('sort', 1);
    }

    public function scopeInNames($query, array $names)
    {
        return $query->whereIn('name', $names);
    }

    protected static function generateName(): string
    {
        return today()->format('ymd') . sprintf("%08d", random_int(0, 99999999));
    }

    public static function newInstanceForUploadFile(UploadedFile $uploadedFile): self
    {
        $mime = $uploadedFile->getClientMimeType();

        $size = $uploadedFile->getSize();

        $type = explode('/', $mime)[0];

        $originalExt = $uploadedFile->extension();

        $encodeExt = self::getEncodeExtViaType($type);

        $file = new File([
            'mime' => $mime,
            'type' => $type,
            'original_ext' => $originalExt,
            'encode_ext' => $encodeExt,
            'size' => $size,
        ]);

        return $file;
    }

    protected static function getEncodeExtViaType(string $type): string
    {
        switch ($type) {
            case self::TYPE_IMAGE:
                return self::ENCODE_IMAGE_EXT;
            case self::TYPE_VIDEO:
                return self::ENCODE_VIDEO_EXT;
            case self::TYPE_AUDIO:
                return self::ENCODE_AUDIO_EXT;
            default:
                # code...
                break;
        }
    }

    public function updateStatusSuccess(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }
        $this->status = self::STATUS_SUCCESS;
        return $this->save();
        return $this;
    }

    public function updateStatusFail(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }
        $this->status = self::STATUS_FAIL;
        return $this->save();
    }

    public function handlePutFileToBackblaze()
    {
        if ($this->status === self::STATUS_SUCCESS) {
            return;
        }

        switch ($this->type) {
            case self::TYPE_VIDEO:
                $this->handleVideo();
                break;
            case self::TYPE_IMAGE:
                $this->handleImage();
                break;
            case self::TYPE_AUDIO:
                $this->handleAudio();
                break;
            default:
                break;
        }

        $this->putFileToBackblaze();

        $this->updateStatusSuccess();

        $this->clearLocalFile();
    }

    protected function handleVideo()
    {
        //TODO: logic
    }

    protected function handleImage()
    {
        $this->encodeImageToWebp();

        $this->syncWidthAndHeight();
    }

    protected function handleAudio()
    {
        //TODO: logic
    }

    protected function encodeImageToWebp(): bool
    {
        $path = public_path("storage/{$this->original_full_name}");
        [$width] = getimagesize($path);
        $width = min($width, 800);
        $newPath = public_path("storage/{$this->encode_full_name}");

        switch ($this->original_ext) {
            case 'gif':
                $command = "~/webp/bin/gif2webp -mixed -min_size {$path} -o {$newPath} 2>&1";
                break;
            case 'png':
            case 'jpeg':
            case 'tiff':
            case 'webp':
                $command = "~/webp/bin/cwebp -near_lossless 60 -resize {$width} 0 {$path} -o {$newPath} 2>&1";
                break;
            default:
                break;
        }

        exec($command, $output, $result); // $result 为 0 代表成功

        if ($result) {
            $error = implode('-', $output);
            throw new \Exception("Encode image to webp failed with message {$error}");
        }

        return $result;
    }

    protected function syncWidthAndHeight(): bool
    {
        [$width, $height] = getimagesize(public_path("storage/{$this->encode_full_name}"));

        $this->width = $width;
        $this->height = $height;
        return $this->save();
    }

    protected function putFileToBackblaze(): bool
    {
        $content = Storage::disk('public')->get($this->encode_full_name);
        try {
            $result = Storage::disk('b2')->put("/{$this->encode_full_name}", $content);
        } catch (\Throwable $th) {
            throw new \Exception("Put file to b2 failed with message {$th->getMessage()}");
        }
        return $result;
    }

    public function clearLocalFile()
    {
        Storage::disk('public')->delete($this->original_full_name);
        Storage::disk('public')->delete($this->encode_full_name);
    }
}
