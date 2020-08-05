<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\PostSearchService;
use App\Http\Resources\PostSearchList;

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
        $keyword = $request->input('keyword', null);
        $size = $request->input('size', 15);
        $page = $request->input('page', 1);

        $paginator = PostSearchService::boolSearch($keyword, $size, $page);

        return PostSearchList::collection($paginator);
    }
}
