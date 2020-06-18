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

        $paginator = PostSearchService::boolSearch($keyword);

        return PostSearchList::collection($paginator);
    }
}
