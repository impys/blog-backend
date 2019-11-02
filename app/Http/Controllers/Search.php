<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class Search extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $query = $request->input('query');
        $articleResult = Article::search($query)->raw();
        $articleHits = $articleResult['hits'];
        $articles = handle_hits($articleHits);

        $data = [
            [
                'url' => Article::URL,
                'resource' => Article::RESOURCE,
                'label' => Article::LABEL,
                'color' => Article::COLOR,
                'data' => ['data' => $articles]
            ],
        ];


        return response()->json(['data' => $data]);
    }
}
