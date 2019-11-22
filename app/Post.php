<?php

namespace App;

use App\Traits\HasEnable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasEnable;
    use Searchable;

    const SIZE = 21;

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

        $array['tags'] = $this->buildTagsForSearch();

        return $array;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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

    public function makeTag(Collection $tags)
    {
        $this->tags()->sync($tags->pluck('id')->toArray());
        return $this;
    }

    public function buildTagsForSearch(): array
    {
        return $this->tags
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            })
            ->toArray();
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
