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
            ->top()
            ->mostVisit()
            ->latest()
            ->limit(8)
            ->get();

        return new HomeView(
            [
                'books' => $books,
                'posts' => $posts,
            ]
        );
    }

    public function booksInRandomOrder(Request $request)
    {
        $books = Book::query()
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return new HomeView(
            [
                'books' => $books,
            ]
        );
    }

    public function postsInRandomOrder(Request $request)
    {
        $posts = Post::query()
            ->enabled()
            ->with(['tags', 'files'])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return new HomeView(
            [
                'posts' => $posts,
            ]
        );
    }
}
