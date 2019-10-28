<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ShowArticles extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $articles = Article::all();
        return view('ShowArticles', ['articles' => $articles]);
    }
}
