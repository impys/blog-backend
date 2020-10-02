<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\File;
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
            ->whereIn('id', [1, 2, 8, 7])
            ->get();

        $postQuery = Post::query()
            ->enabled()
            ->with(['tags', 'files']);

        $postsWithAudio = (clone $postQuery)
            ->whereHas('files', fn ($query) => $query->ofType(File::TYPE_AUDIO))
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $posts = (clone $postQuery)
            ->mostVisit()
            ->whereNotIn('id', $postsWithAudio->pluck('id')->toArray())
            ->limit(6 - $postsWithAudio->count())
            ->get();

        $posts = $posts->concat($postsWithAudio)->sort();

        return new HomeView(
            [
                'books' => $books,
                'posts' => $posts,
            ]
        );
    }
}
