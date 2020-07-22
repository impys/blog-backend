<?php

namespace App\Observers;

use App\Post;
use App\Jobs\SyncPostChapterJob;
use App\Jobs\TranslatePostTitleJob;

class PostObserver
{
    public function saved(Post $post)
    {
        if ($post->isDirty('body')) {
            $post->syncFiles();
        }

        if ($post->isDirty('title')) {
            TranslatePostTitleJob::dispatch($post);
        }

        if ($post->book_id) {
            if ($post->isDirty('chapter') || $post->isDirty('book_id')) {
                SyncPostChapterJob::dispatch($post);
            }
        }
    }

    public function saving(Post $post)
    {
        if (!$post->book_id) {
            $post->chapter = null;
        }
    }

    public function deleting(Post $post)
    {
        $post->tags()->detach();
    }
}
