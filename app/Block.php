<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'name',
        'label',
        'is_top',
        'sort'
    ];

    protected $appends = [
        'entity_count'
    ];

    protected $casts = [
        'is_top' => 'boolean'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function getEntityCountAttribute()
    {
        $count = $this->articles()->count();
        return $count;
    }
}
