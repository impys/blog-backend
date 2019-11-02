<?php

use Illuminate\Support\Arr;

if (!function_exists('unslug')) {
    function unslug($slug)
    {
        return ucfirst(str_replace('-', ' ', $slug));
    }
}

if (!function_exists('handle_hits')) {
    function handle_hits(array $hits)
    {
        return collect($hits)
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
}
