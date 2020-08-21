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
            ->whereIn('id', [2, 4, 1, 5])
            ->get();

        $posts = Post::query()
            ->enabled()
            ->with(['tags', 'files'])
            ->limit(4)
            ->get();

        return new HomeView(
            [
                'posts' => $posts,
            ]
        );
    }
}
