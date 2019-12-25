<?php

namespace App\Observers;

use App\File;

class FileObserver
{
    public function created(File $file)
    {
        if ($file->type === File::TYPE_IMAGE) {
            $file->syncWidthAndHeight();
        }
    }
}
