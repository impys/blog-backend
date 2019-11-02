<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Tools\Music;

class Search extends Controller
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
        $articles = $this->searchArticle($query);
        $musics = $this->searchMusic($query);

        $data = [
            [
                'url' => null,
                'resource' => 'music',
                'label' => '音乐',
                'color' => Article::COLOR,
                'data' => ['data' => $musics]
            ],
            [
                'url' => Article::URL,
                'resource' => Article::RESOURCE,
                'label' => Article::LABEL,
                'color' => Article::COLOR,
                'data' => ['data' => $articles]
            ],
        ];

        return response()->json(['data' => $data]);
    }

    protected function searchArticle(string $query): array
    {
        return handle_hits(Article::search($query)->raw()['hits']);
    }
    protected function searchMusic(string $query): array
    {
        return $this->musicPhp
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
    }
}
