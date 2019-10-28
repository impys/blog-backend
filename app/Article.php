<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const DISPLAY_NUMBER_ON_WELCOME = 9;

    protected $hidden = ['body'];

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_top',
        'sort',
        'visited_count',
        'upvote_count',
    ];

    protected $appends = [
        'created_at_human'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function scopeMostVisit($query)
    {
        return $query->orderBy('visited_count', 'desc');
    }

    public function scopeMostUpvote($query)
    {
        return $query->orderBy('upvote_count', 'desc');
    }

    public function scopeDefaultList($query)
    {
        return $query
            ->orderBy('is_top', 'desc')
            ->mostUpvote()
            ->mostVisit()
            ->limit(self::DISPLAY_NUMBER_ON_WELCOME);
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
