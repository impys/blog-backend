<?php

namespace App\Http\Controllers;

use App\Post;
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
        $query = $request->input('query');

        $posts = Post::search($query)->paginateRaw();

        return new PostSearchCollection($posts);
    }
}
