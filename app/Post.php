<?php

namespace App;

use App\Traits\HasEnable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasEnable;
    use Searchable;

    const SIZE = 30;

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
        'updated_at_human',
        'length',
        'audio_count',
        'cover_media',
    ];

    protected $attributes = [
        'is_enable' => true
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($self) {
            $self->user_id = auth()->user()->id;
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

    public function files()
    {
        return $this->hasMany(File::class);
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

    public function getLengthAttribute()
    {
        return mb_strlen($this->body, 'utf-8');
    }

    public function getAudioCountAttribute(): int
    {
        return $this->files->where('type', File::TYPE_AUDIO)->count();
    }

    public function getCoverMediaAttribute(): ?file
    {
        return $this->files->firstWhere('sort', 1);
    }

    public function getAllFilesName(): array
    {
        $prefix = preg_replace("/\//", "\\\/", Storage::disk('public')->url("assets/"));

        $pattern = "/$prefix(.*)(\)|\")/U";

        preg_match_all($pattern, $this->body, $results);

        return $results[1];
    }


    public function syncFiles()
    {
        $this->dissociateAllFiles();
        $this->associateFiles();
    }

    protected function dissociateAllFiles()
    {
        DB::table('files')
            ->where('post_id', $this->id)
            ->update(
                [
                    'post_id' => null,
                    'sort' => null
                ]
            );
    }

    protected function associateFiles()
    {
        $names = $this->getAllFilesName();

        $sort = array_flip($names);

        $files = File::inNames($names)->get();

        foreach ($files as $file) {
            $file->post()->associate($this);
            $file->sort = $sort[$file->name] + 1;
            $file->save();
        }
    }

    /**
     * 给文章打标签
     *
     * @param Collection $tags
     * @return self
     */
    public function makeTag(Collection $tags): self
    {
        $this->tags()->sync($tags->pluck('id')->toArray());
        return $this;
    }

    /**
     * get tags id and name for algolia
     *
     * @return array
     */
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

    public static function getPostsPaginator(?array $tagIds)
    {
        return self::query()
            ->enable()
            ->with(['tags', 'files'])
            ->when($tagIds, function ($query) use ($tagIds) {
                return $query->inTagIds($tagIds);
            })
            ->latest()
            ->paginate(self::SIZE);
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
