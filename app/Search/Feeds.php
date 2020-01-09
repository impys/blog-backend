<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;

class Feeds extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        \App\Post::class,
    ];

    public function shouldBeSearchable()
    {
        return $this->model->shouldBeSearchable();
    }
}
