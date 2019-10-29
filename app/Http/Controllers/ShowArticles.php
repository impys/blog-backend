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
        $articles = Article::query()
            ->latest()
            ->paginate($request->input('size', 30));

        $data = [
            'url' => Article::URL,
            'label' => Article::LABEL,
            'color' => Article::COLOR,
            'data' => $articles
        ];

        return view('Article.ShowArticles', ['data' => $data]);
    }
}
