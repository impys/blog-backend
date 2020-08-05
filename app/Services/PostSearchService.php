<?php

namespace App\Services;

use App\Post;
use ElasticScoutDriverPlus\Match;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostSearchService
{
    // TODO:拆分为小函数
    public static function boolSearch(string $keyword, int $size, int $page)
    {
        $searchResult = Post::boolSearch()
            ->should(
                'multi_match',
                [
                    'query' => $keyword,
                    'fields' => ['title^2', 'body'],
                ]
            )
            ->from($size * ($page - 1))
            ->size($size)
            ->highlightRaw(
                [
                    'fields' => [
                        'title' => [
                            'number_of_fragments' => 3
                        ],
                        'body' => [
                            'number_of_fragments' => 4,
                            'fragment_size' => 20
                        ]
                    ]
                ]
            )
            ->execute();

        $total = $searchResult->total();

        $models = new Collection();

        $searchResult->matches()->each(function (Match $match) use ($models) {
            $model = $match->model();
            $titleSnippets =  $match->highlight()->getSnippets('title');
            $bodySnippets =  $match->highlight()->getSnippets('body');

            if ($titleSnippets) {
                $model->title = $titleSnippets[0];
            }

            if ($bodySnippets) {
                $model->body_snippet = implode('  ', $bodySnippets);
                $model->body_snippets = $bodySnippets;
            }

            $models->push($model);
        });

        $models->load(['tags', 'files']);

        return new LengthAwarePaginator($models, $total, $size, $page);
    }
}
