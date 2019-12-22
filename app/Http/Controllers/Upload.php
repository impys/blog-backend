<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Services\UploadService;
use Illuminate\Http\Request;

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
            'file' => 'required|file|mimetypes:video/mp4,image/png,image/jpeg,image/gif',
        ]);

        $uploadedFile = $request->file;

        $file = $uploadService->store($uploadedFile);

        return new FileResource($file);
    }
}
