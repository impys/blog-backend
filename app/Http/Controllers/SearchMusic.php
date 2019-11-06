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

        // $articleBLock = $this->searchArticle($query);


        $data = [];

        $includeSearchMusic = $request->input('includeSearchMusic');
        if ($includeSearchMusic == 'true') {
            $musicBLock = $this->searchMusic($query);
            array_unshift($data, $musicBLock);
        }


        return response()->json(['data' => $data]);
    }

    protected function searchArticle(string $query): array
    {
        $articles = handle_hits(Post::search($query)->raw()['hits']);
        return [
            'url' => null,
            'resource' => null,
            'label' => null,
            'color' => null,
            'data' => ['data' => $articles]
        ];
    }
    protected function searchMusic(string $query): array
    {
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

        return [
            'url' => null,
            'resource' => 'music',
            'label' => '音乐',
            'color' => null,
            'data' => ['data' => $songs]
        ];
    }
}
