<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class Welcome extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $tagIds = json_decode($request->input('tags'));

        $posts = Post::getPostPaginator($tagIds);

        $tags = Tag::query()
            ->withCount('posts')
            ->get();

        return view('Welcome', [
            'data' => [
                'posts' => $posts,
                'tags' => $tags
            ],
        ]);
    }
}
