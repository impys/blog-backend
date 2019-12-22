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
        $files = File::query()
            ->notOfStatus(File::STATUS_SUCCESS)
            ->get();

        foreach ($files as $file) {
            $file->handlePutFileToB2();
            sleep(60);
        }
    }
}
