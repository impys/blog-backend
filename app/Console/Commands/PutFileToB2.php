<?php

namespace App\Console\Commands;

use App\File;
use Illuminate\Console\Command;

class PutFileToB2 extends Command
{
    protected $signature = 'command:put-file-to-b2';

    protected $description = 'Put file to backblaze storage';

    public function handle()
    {
        File::query()
            ->notOfStatus(File::STATUS_SUCCESS)
            ->get()
            ->each
            ->handlePutFileToB2();
    }
}
