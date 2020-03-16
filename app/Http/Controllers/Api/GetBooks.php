<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\Http\Resources\BookList;
use Illuminate\Http\Request;

class GetBooks extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $books = Book::query()
            ->withCount(['posts' => function ($query) {
                return $query->enable();
            }])
            ->get();

        return BookList::collection($books);
    }
}