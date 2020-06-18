<?php

namespace App\Services;

use App\Post;
use ElasticScoutDriverPlus\Match;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PostSearchService
{
    // TODO:拆分为小函数
    public static function boolSearch(string $keyword, int $perPage = 10, int $page = 1)
    {
        $searchResult = Post::boolSearch()
            ->should(
                'multi_match',
                [
                    'query' => $keyword,
                    'fields' => ['title^2', 'body'],
                ]
            )
            ->from($perPage * ($page - 1))
            ->size($perPage)
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

        $models = new EloquentCollection();

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

        return new LengthAwarePaginator($models, $total, $perPage, $page);
    }
}
