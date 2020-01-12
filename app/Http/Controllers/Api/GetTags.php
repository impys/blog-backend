<?php

namespace App\Http\Controllers\Api;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagList;

class GetTags extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $tags = Tag::query()
            ->withCount(['posts' => function ($query) {
                return $query->enable();
            }])
            ->get();

        return TagList::collection($tags);
    }
}
