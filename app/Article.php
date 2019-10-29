<?php

namespace App;

use App\Traits\HasEnable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasEnable;

    const DISPLAY_NUMBER_ON_WELCOME = 8;

    const URL = 'articles';
    const LABEL = '文章';
    const COLOR = '#fc8181';

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

    public function scopeListForWelcome($query)
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
