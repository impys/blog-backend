<?php

namespace App\Http\Controllers\Nova;

use Laravel\Nova\Nova;
use Laravel\Nova\GlobalSearch;
use Laravel\Nova\Http\Requests\NovaRequest;

class Search extends Controller
{
    public function __invoke(NovaRequest $request)
    {
        $searchableResources =  [
            "App\Nova\Book",
            "App\Nova\Post",
            "App\Nova\Tag",
        ];

        return (new GlobalSearch(
            $request,
            $searchableResources
        ))->get();
    }
}
