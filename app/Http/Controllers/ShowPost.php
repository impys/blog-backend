<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class ShowPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Post $post)
    {
        return view('ShowPost', ['post' => $post]);
    }
}
