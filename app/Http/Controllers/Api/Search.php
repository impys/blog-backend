<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\PostSearchCollection;

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

        $posts = Post::search($keyword)->paginateRaw();

        return new PostSearchCollection($posts);
    }
}
