<?php

namespace App\Http\Controllers;

use App\Block;
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
        $blocks = Block::query()
            ->with(['articles' => function ($query) {
                return $query->defaultList();
            }])
            ->get();
        return view('Welcome', ['blocks' => $blocks]);
    }
}
