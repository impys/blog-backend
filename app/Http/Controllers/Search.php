<?php

namespace App\Http\Controllers;

use App\Post;
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

        $posts = handle_hits(Post::search($query)->raw()['hits']);

        $data = [
            [
                'label' => 'post',
                'label_zh' => '文章',
                'data' => $posts
            ]
        ];

        return response()->json($data);
    }
}
