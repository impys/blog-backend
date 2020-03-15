<?php

namespace App\Observers;

use App\Book;

class BookObserver
{
    public function saved(Book $book)
    {
        if ($book->isDirty('cover')) {
            $book->syncFiles();
        }
    }
}
