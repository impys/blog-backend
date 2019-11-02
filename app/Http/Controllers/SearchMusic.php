<?php

namespace App\Http\Controllers;

use App\Tools\Music;
use Illuminate\Http\Request;


class SearchMusic extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Music $musicPhp)
    {
        $songs = $musicPhp->searchAll('成都');
        dump($songs);
    }
}
