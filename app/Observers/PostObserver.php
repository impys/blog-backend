<?php

namespace App\Observers;

use App\Jobs\HandlePostCoverJob;
use App\Post;

class PostObserver
{
    public function saved(Post $post)
    {
        $url = $post->getFirstImageUrl();

        if ($url && $url != $post->cover) {
            HandlePostCoverJob::dispatch($post);
        }
    }
}
