<?php

namespace App\Observers;

use App\Jobs\HandlePostCoverJob;
use App\Post;

class PostObserver
{
    public function saved(Post $post)
    {
        HandlePostCoverJob::dispatch($post);
    }
}
