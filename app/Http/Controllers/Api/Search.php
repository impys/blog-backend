<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\PostSearchCollection;
use App\Search\Feeds;

class Search extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $keyword = $request->input('keyword');
        $ranking = $request->input('ranking', null);

        $query = Feeds::search($keyword);

        if ($ranking) {
            $index = config('scout.prefix') . 'feeds-' . $ranking;
            $query->within($index);
        }

        $feeds = $query->paginateRaw();

        return new PostSearchCollection($feeds);
    }
}
