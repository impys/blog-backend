<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostView;

class GetPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Post $post)
    {
        $post->beVisited();
        $post->load('tags');
        return new PostView($post);
    }
}
