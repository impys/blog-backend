<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadService;
use App\Http\Resources\FileResource;

class Upload extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, UploadService $uploadService)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:image/png,image/jpeg,image/gif,audio/mp3,audio/mpeg',
        ]);

        $uploadedFile = $request->file;

        $file = $uploadService->store($uploadedFile);

        return new FileResource($file);
    }
}
