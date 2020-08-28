<?php

namespace App\Observers;

use App\File;
use App\Jobs\PutFileToCloudJob;

class FileObserver
{
    public function created(File $file)
    {
        PutFileToCloudJob::dispatch($file);
    }
}
