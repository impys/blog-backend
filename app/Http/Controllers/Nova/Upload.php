<?php

namespace App\Http\Controllers\Nova;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\StoreService;
use App\Http\Resources\FileResource;

class Upload extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:image/png,image/jpeg,image/gif,audio/mp3,audio/mpeg',
        ]);

        $uploadedFile = $request->file;

        $file = StoreService::store($uploadedFile);

        return new FileResource($file);
    }
}
