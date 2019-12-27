<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class BaseSearchCollection extends ResourceCollection
{
    public function getHits(): array
    {
        return collect($this->collection->get('hits'))
            ->map(function ($hit) {
                $snippetResult = $hit['_snippetResult'];
                foreach ($snippetResult as $attr => $snippet) {
                    $hit[$attr] = $snippet['value'];
                }

                Arr::forget($hit, '_highlightResult');
                Arr::forget($hit, '_snippetResult');

                return $hit;
            })
            ->values()
            ->toArray();
    }

    public function getMeta()
    {
        return $this->collection->except('hits')->toArray();
    }
}
