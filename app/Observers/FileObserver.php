<?php

namespace App\Observers;

use App\File;
use App\Jobs\PutFileToBackblazeJob;

class FileObserver
{
    public function created(File $file)
    {
        PutFileToBackblazeJob::dispatch($file);
    }
}
