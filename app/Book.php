<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $appends = [
        'cover_url'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'entity');
    }

    public function getCoverUrlAttribute()
    {
        return Storage::url($this->cover);
    }

    public function syncFiles()
    {
        $this->dissociateAllFiles();
        $this->associateFiles();
    }

    protected function dissociateAllFiles()
    {
        $this->file()->update(
            [
                'entity_id' => null,
                'entity_type' => null,
            ]
        );
    }

    protected function associateFiles()
    {
        $file = File::ofName($this->cover)->first();

        $file->entity()->associate($this);
        $file->save();
    }

    public function getChapters(): Collection
    {
        return $this->posts()
            ->enabled()
            ->get()
            ->sortBy('chapter')
            ->values()
            ->map(function ($post, $index) {
                $post->chapter_index = $index;
                return $post;
            });
    }
}
