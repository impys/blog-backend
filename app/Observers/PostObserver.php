<?php

namespace App\Observers;

use App\Post;
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
    }

    public function deleting(Post $post)
    {
        $post->tags()->detach();
    }
}
