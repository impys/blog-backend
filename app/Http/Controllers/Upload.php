<?php

namespace App\Http\Controllers;

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
        $uploadedFile = $request->file;

        $url = $uploadService->store($uploadedFile);

        return response()->json($url);
    }
}
