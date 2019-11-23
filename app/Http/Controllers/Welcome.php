<?php

namespace App\Http\Controllers;

use App\Post;
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
        $posts = Post::query()
            ->with('tags')
            ->latest()
            ->paginate(POST::SIZE);

        return view('Welcome', ['data' => $posts]);
    }
}
