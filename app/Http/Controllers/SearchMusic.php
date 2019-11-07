<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Tools\Music;

class SearchMusic extends Controller
{
    protected $musicPhp;

    public function __construct(Music $musicPhp)
    {
        $this->musicPhp = $musicPhp;
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

        $songs = $this->musicPhp
            ->searchAll($query)
            ->map(function ($song) {
                return [
                    'title' => $song['name'],
                    'src' => $song['url'],
                    'artist' => implode('/', $song['artist']),
                ];
            })
            ->sortBy(function ($song) {
                return strlen($song['title'] . $song['artist']);
            })
            ->values()
            ->toArray();

        return response()->json(['data' => $songs]);
    }
}
