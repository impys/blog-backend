<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Services\MusicService;

class SearchMusic extends Controller
{
    protected $MusicService;

    public function __construct(MusicService $MusicService)
    {
        $this->MusicService = $MusicService;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $query = $request->input('query');

        $songs = $this->MusicService->searchAll($query);

        return response()->json(['data' => $songs]);
    }
}
