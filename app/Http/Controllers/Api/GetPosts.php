<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostList;

class GetPosts extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $tagId = $request->input('tag_id', null);
        $posts = Post::query()
            ->when($tagId, function ($query) use ($tagId) {
                return $query->ofTagId($tagId);
            })
            ->enabled()
            ->with(['tags', 'files'])
            ->top()
            ->latest()
            ->paginate($request->input('size', 10));

        return PostList::collection($posts);
    }
}
