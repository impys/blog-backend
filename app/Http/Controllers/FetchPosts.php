<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\Post;
use Illuminate\Http\Request;

class FetchPosts extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $posts = Post::query()
            ->with('tags')
            ->latest()
            ->paginate(POST::SIZE);

        return PostsResource::collection($posts);
    }
}
