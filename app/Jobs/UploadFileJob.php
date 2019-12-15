<?php

namespace App\Jobs;

use App\File;
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
    protected $extension;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($base64File, string $name, string $extension)
    {
        $this->base64File = $base64File;
        $this->name = $name;
        $this->extension = $extension;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = base64_decode($this->base64File);

        $image = (string) Image::make($file)->encode($this->extension, 80);

        $result = Storage::disk('b2')->put('/' . $this->name, $image);

        if ($result) {
            File::updateStatusSuccess($this->name);
        } else {
            File::updateStatusFail($this->name);
        }
    }
}
