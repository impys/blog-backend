<?php

namespace App\Observers;

use App\Post;

class PostObserver
{
    public function saved(Post $post)
    {
        if ($post->isDirty('body')) {
            $post->syncFiles();
        }
    }
}
