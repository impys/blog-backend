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
        $posts = Post::query()
            ->enable()
            ->with(['tags', 'files'])
            ->top()
            ->latest()
            ->paginate(Post::SIZE);

        return PostList::collection($posts);
    }
}
