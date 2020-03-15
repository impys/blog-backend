<?php

namespace App\Nova\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;

class PublicImage extends Image
{
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {
        $storageCallback = function (Request $request) {
            return (new UploadService())->store($request->cover)->name;
        };

        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->thumbnail(function ($value) use ($disk) {
            return $this->value ? Storage::disk($disk)->url("assets/{$value}") : null;
        })->preview(function ($value) use ($disk) {
            return $this->value ? Storage::disk($disk)->url("assets/{$value}") : null;
        })->rules(
            'required',
            'file',
            'mimetypes:image/png,image/jpeg,image/gif'
        )->disableDownload();
    }
}
