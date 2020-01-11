<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostSearchCollection;

class SimpleSearch extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Post $post)
    {
        $query = $request->input('query');

        $posts = Post::search($query)->raw();

        return new PostSearchCollection($posts);
    }
}
