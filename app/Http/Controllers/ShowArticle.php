<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ShowArticle extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Article $article)
    {
        return view('ShowArticle', ['article' => $article]);
    }
}
