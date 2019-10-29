<?php

namespace App\Http\Controllers;

use App\Article;
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
        $articles = Article::query()
            ->listForWelcome()
            ->get();

        $data = [
            [
                'url' => Article::URL,
                'label' => Article::LABEL,
                'color' => Article::COLOR,
                'data' => ['data' => $articles]
            ],
        ];


        return view('Welcome', ['data' => $data]);
    }
}
