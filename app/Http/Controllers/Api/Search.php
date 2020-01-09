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

        $posts = Feeds::search($keyword)->paginateRaw();

        return new PostSearchCollection($posts);
    }
}
