<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $base64File;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($base64File, string $name)
    {
        $this->base64File = $base64File;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = base64_decode($this->base64File);

        $image = (string) Image::make($file)->encode('webp');

        Storage::disk('b2')->put('/' . $this->name, $image);
    }
}
