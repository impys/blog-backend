<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

                $hit['created_at_human'] = Carbon::parse($hit['created_at'])->diffForHumans();
                $hit['updated_at_human'] = Carbon::parse($hit['updated_at'])->diffForHumans();

                Arr::forget($hit, '_highlightResult');
                Arr::forget($hit, '_snippetResult');
                Arr::forget($hit, '_tags');

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
