<?php

namespace App;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasStatus;

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
     * @return string
     */
    public function getOriginalFullNameAttribute(): string
    {
        return $this->name . '.' . $this->original_ext;
    }

    /**
     * name dot encoded extension of this file
     *
     * @return string
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

    /**
     * get full physical path of uncoded file
     *
     * @return string
     */
    protected function getOriginPath(): string
    {
        return public_path("storage/{$this->original_full_name}");
    }

    /**
     * get full physical path of encoded file
     *
     * @param string $newName
     * @return string
     */
    protected function getEncodePath(): string
    {
        return public_path("storage/encodedFiles/{$this->encode_full_name}");
    }

    public function handlePutFileToB2()
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

        $isPutToB2Success = $this->putFileToB2();

        // change status success or fail based on result
        $this->changeStatus($isPutToB2Success);

        $this->clearLocalFile($isPutToB2Success);
    }

    protected function handleVideo()
    {
        $this->grabVideoFrame();

        $this->encodeVideoToMp4();
    }

    protected function grabVideoFrame()
    {
        // if the video has poster，no need to grab a frame
        if ($this->poster) {
            return;
        }

        //拿到视频物理路径
        $path = $this->getOriginPath();

        //生成一个新的文件名字
        $newFileName = self::generateName();

        //加上扩展名
        $newFileFullName = $newFileName . '.png';

        //物理路径
        $newPath = public_path("storage/{$newFileFullName}");

        //执行命令截取帧，保存在 storage 路径下
        $command = "ffmpeg -i {$path} -y -f image2 -ss 4.0 -t 0.001 {$newPath}  2>&1";
        $this->execCommand($command);

        //视频截图存入数据库
        $file = self::newInstanceForPoster($newPath, $newFileName);
        $file->save();

        //关联视频海报
        $this->poster_id = $file->id;
        $this->save();

        //TODO: why use save method not set poster id???
        // $this->poster()->save($file);
    }

    protected function encodeVideoToMp4()
    {
        // no need to encode video file now
        $exists = Storage::disk('public')->exists('encodedFiles/' . $this->original_full_name);
        if ($exists) {
            return;
        }
        Storage::disk('public')
            ->copy($this->original_full_name, 'encodedFiles/' . $this->original_full_name);
        // $path = $this->getOriginPath();
        // $newPath = $this->getEncodePath();
        // $command = "ffmpeg -i {$path} -vcodec libx264 {$newPath}  2>&1";
        // return $this->execCommand($command);
    }

    protected function handleImage()
    {
        $this->encodeImageToWebp();

        $this->syncWidthAndHeight();
    }

    protected function handleAudio()
    {
        // no need to encode audio file now
        $exists = Storage::disk('public')->exists('encodedFiles/' . $this->original_full_name);
        if (!$exists) {
            return;
        }
        Storage::disk('public')
            ->copy($this->original_full_name, 'encodedFiles/' . $this->original_full_name);
    }

    protected function encodeImageToWebp(): bool
    {
        $path = $this->getOriginPath();
        $newPath = $this->getEncodePath();

        [$width] = getimagesize($path);
        $width = min($width, 800);

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

        if ($result != 0) {
            $error = implode('-', $output);

            logger(__("Encode file failed file - :fileId - error :error", [
                'fileId' => $this->id,
                'error' => $error,
            ]));
        }

        return !$result;
    }

    protected function syncWidthAndHeight(): bool
    {
        [$width, $height] = getimagesize($this->getEncodePath());

        $this->width = $width;
        $this->height = $height;
        return $this->save();
    }

    protected function putFileToB2(): bool
    {
        $content = Storage::disk('public')->get('encodedFiles/' . $this->encode_full_name);
        try {
            $result = Storage::disk('b2')->put("/{$this->encode_full_name}", $content);
        } catch (\Throwable $th) {
            logger(__("Put file to b2 failed - :fileId - error :error", [
                'fileId' => $this->id,
                'error' => $th->getMessage(),
            ]));
        }
        return $result;
    }

    protected function changeStatus(bool $isPutToB2Success)
    {
        if ($isPutToB2Success) {
            $this->updateStatusSuccess();
        } else {
            $this->updateStatusFail();
        }
    }

    protected function clearLocalFile(bool $isPutToB2Success)
    {
        //如果推送失败，不应该把源文件清除，因为下一次跑 command 的时候还需要用到源文件
        if ($isPutToB2Success) {
            $this->clearOriginalFile();
        }

        $this->clearEncodeFile();
    }

    protected function clearOriginalFile()
    {
        Storage::disk('public')->delete($this->original_full_name);
    }

    protected function clearEncodeFile()
    {
        Storage::disk('public')->delete('encodedFiles/' . $this->encode_full_name);
    }
}
