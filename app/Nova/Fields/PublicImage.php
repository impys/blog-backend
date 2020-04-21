<?php

namespace App\Nova\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use App\Services\CosService;
use Closure;
use Illuminate\Support\Facades\Storage;

class PublicImage extends Image
{
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {

        parent::__construct($name, $attribute, $disk, $this->getStorageCallback());

        $urlCallback = $this->getUrlCallback();

        $this->thumbnail($urlCallback)->preview($urlCallback)
            ->rules(
                'required',
                'file',
                'mimetypes:image/png,image/jpeg,image/gif'
            )->disableDownload();
    }

    protected function getStorageCallback(): Closure
    {
        return function (Request $request) {
            return (new CosService)->store($request->cover)->name;
        };
    }

    protected function getUrlCallback(): Closure
    {
        return function ($value) {
            return $value ? CosService::url($value) : null;
        };
    }
}
