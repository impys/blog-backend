<?php

namespace App\Observers;

use App\Jobs\TranslatePostTitleJob;
use App\Post;

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
}
