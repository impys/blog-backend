<?php

namespace App\Http\Controllers\Nova;

use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class TagPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Post $post)
    {
        $tagNames = $request->input('tags');

        $tags = Tag::findOrCreateByNames($tagNames);

        $post->makeTag($tags);
    }
}
