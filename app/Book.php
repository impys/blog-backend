<?php

namespace App;

use App\Traits\SyncFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

class Book extends Model
{
    use SyncFiles;

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

    /**
     * Get the files belongs to this book
     *
     * @return Collection
     */
    public function getAllFiles(): Collection
    {
        return File::query()
            ->ofName($this->cover)
            ->get();
    }

    public function getChapters(): BaseCollection
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
