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
            if (!$self->name) {
                $self->name = self::generateName();
            }
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

        $originalExt = $uploadedFile->clientExtension();

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

    public static function newInstanceForPoster(string $path, string $name): self
    {
        $size = filesize($path);

        $file = new File([
            'name' => $name,
            'mime' => 'image/png',
            'type' => 'image',
            'original_ext' => 'png',
            'encode_ext' => 'webp',
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
        $this->grabVideoFrame();

        $this->encodeVideoToMp4();
    }

    protected function grabVideoFrame()
    {
        if ($this->poster) {
            return;
        }
        $path = public_path("storage/{$this->original_full_name}");

        $newFileName = self::generateName();

        $newPath = public_path("storage/{$newFileName}.png");

        $command = "ffmpeg -i {$path} -y -f image2 -ss 4.0 -t 0.001 {$newPath}  2>&1";

        $this->execCommand($command);

        $file = self::newInstanceForPoster($newPath, $newFileName);

        $file->save();

        $this->poster_id = $file->id;

        $this->save();

        //TODO: why use save method not set poster id???
        // $this->poster()->save($file);
    }

    protected function encodeVideoToMp4(): bool
    {
        if ($this->original_ext === self::ENCODE_VIDEO_EXT) {
            return true;
        }

        $path = public_path("storage/{$this->original_full_name}");
        $newPath = public_path("storage/{$this->encode_full_name}");
        $command = "ffmpeg -i {$path} -vcodec libx264 {$newPath}  2>&1";
        return $this->execCommand($command);
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
        if ($this->original_ext === self::ENCODE_VIDEO_EXT) {
            return true;
        }

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
            case 'jpg':
            case 'tiff':
            case 'webp':
                $command = "~/webp/bin/cwebp -near_lossless 60 -resize {$width} 0 {$path} -o {$newPath} 2>&1";
                break;
            default:
                break;
        }

        return $this->execCommand($command);
    }

    protected function execCommand(string $command): bool
    {
        exec($command, $output, $result); // $result 为 0 代表成功

        if ($result) {
            $error = implode('-', $output);

            logger(__("Encode image to webp failed file - :fileId - error :error", [
                'fileId' => $this->id,
                'error' => $error,
            ]));

            throw new \Exception("Encode file {$this->id} failed with message {$error}");
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

    protected function clearLocalFile()
    {
        $this->clearOriginalFile();
        $this->clearEncodeFile();
    }

    protected function clearOriginalFile()
    {
        Storage::disk('public')->delete($this->original_full_name);
    }

    protected function clearEncodeFile()
    {
        Storage::disk('public')->delete($this->encode_full_name);
    }
}
