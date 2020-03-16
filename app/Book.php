<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return asset("assets/{$this->cover}");
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
}
