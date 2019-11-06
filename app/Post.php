<?php

namespace App;

use App\Traits\HasEnable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasEnable;
    use Searchable;

    protected $fillable = [
        'title',
        'body',
        'is_top',
        'sort',
        'visited_count',
        'upvote_count',
    ];

    protected $appends = [
        'created_at_human',
        'updated_at_human'
    ];

    public function searchableAs()
    {
        return 'moreless_post_index';
    }

    public function shouldBeSearchable()
    {
        return $this->is_enable;
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['body'] = strip_tags(Markdown::parse($this->body));

        return $array;
    }

    public function scopeMostVisit($query)
    {
        return $query->orderBy('visited_count', 'desc');
    }

    public function scopeMostUpvote($query)
    {
        return $query->orderBy('upvote_count', 'desc');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getUpdatedAtHumanAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    // public function fillSlug()
    // {
    //     $this->slug = $this->id;
    // }

    // public function transSlug()
    // {
    //     if (!$this->isDirty('title')) {
    //         return;
    //     }

    //     $slug = GoogleTranslate::trans($this->title);

    //     if ($slug != null) {
    //         $slug = Str::slug($slug);
    //         DB::table('articles')
    //             ->where('id', $this->id)
    //             ->update(['slug' => $slug]);
    //     }
    // }
}
