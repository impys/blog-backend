<?php

namespace App;

use App\Traits\HasEnable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Laravel\Scout\Searchable;
use Intervention\Image\Facades\Image;

class Post extends Model
{
    use HasEnable;
    use Searchable;

    const SIZE = 30;

    protected $fillable = [
        'title',
        'cover',
        'cover_width',
        'cover_height',
        'body',
        'is_top',
        'sort',
        'visited_count',
        'upvote_count',
    ];

    protected $appends = [
        'created_at_human',
        'updated_at_human',
        'length',
        'audio_count',
        'video_count',
        'first_video',
        'cover_aspect_ratio',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->user_id = auth()->user()->id;
        });
    }

    public function searchableAs()
    {
        return config('scout.posts_index');
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeInTagIds($query, $tagIds)
    {
        return $query->whereHas('tags', function ($query) use ($tagIds) {
            return $query->when(count($tagIds), function ($query) use ($tagIds) {
                return $query->whereIn('id', $tagIds);
            });
        });
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

    public function getLengthAttribute(): string
    {
        $length = mb_strlen($this->body, 'utf-8');

        if ($length > 300) {
            return 'more';
        } else {
            return 'less';
        }
    }

    public function getAudioCountAttribute(): int
    {
        preg_match_all("/<audio(.*)>(.*)<\/audio>/U", $this->body, $res);
        return count($res[0]);
    }

    public function getVideoCountAttribute(): int
    {
        preg_match_all("/<video(.*)>(.*)<\/video>/U", $this->body, $res);
        return count($res[0]);
    }

    public function getFirstVideoAttribute(): ?string
    {
        preg_match_all("/<video(.*)>(.*)<\/video>/U", $this->body, $res);

        if (count($res[0])) {
            return $res[0][0];
        }

        return null;
    }

    public function getCoverAspectRatioAttribute(): ?string
    {
        if (!$this->cover) {
            return null;
        }
        return round($this->cover_height / $this->cover_width * 100, 2) . '%';
    }

    public function getFirstImageUrl(): ?string
    {
        preg_match_all("/\!\[\]\((.*)\)/U", $this->body, $res);

        if (count($res[0])) {
            return $res[1][0];
        } else {
            return null;
        }
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

    public static function getPostPaginator(?array $tagIds)
    {
        return self::query()
            ->with('tags')
            ->when($tagIds, function ($query) use ($tagIds) {
                return $query->inTagIds($tagIds);
            })
            ->latest()
            ->paginate(self::SIZE);
    }

    public function handleCover()
    {
        $url = $this->getFirstImageUrl();

        try {
            $image = Image::make($url);
            $width = $image->width();
            $height = $image->height();

            $this->cover = $url;
            $this->cover_width = $width;
            $this->cover_height = $height;
            $this->save();
        } catch (\Throwable $th) {
            return;
        }
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
