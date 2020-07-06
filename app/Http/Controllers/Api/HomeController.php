<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\Post;
use App\Http\Resources\BookList;
use Illuminate\Http\Request;
use App\Http\Resources\BookView;
use App\Http\Resources\HomeView;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $books = Book::query()
            ->latest()
            ->when(
                $request->input('random', false) == 'book',
                function ($query) {
                    $query->inRandomOrder();
                }
            )
            ->limit(4)
            ->get();

        $posts = Post::query()
            ->enabled()
            ->with(['tags', 'files'])
            ->top()
            ->mostVisit()
            ->latest()
            ->when(
                $request->input('random', false) == 'post',
                function ($query) {
                    $query->inRandomOrder();
                }
            )
            ->limit(8)
            ->get();

        return new HomeView(
            [
                'books' => $books,
                'posts' => $posts,
            ]
        );
    }
}
