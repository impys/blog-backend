<?php

namespace App\Nova\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use App\Services\StorageService;

class PublicImage extends Image
{
    public function disk($disk)
    {
        $this->disk = config('filesystems.default');
    }

    protected function prepareStorageCallback($storageCallback)
    {
        $this->storageCallback = function (Request $request) {
            return (new StorageService)->store($request->cover)->name;
        };
    }
}
