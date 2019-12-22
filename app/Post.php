<?php

namespace App;

use App\Traits\HasEnable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;

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
        'video_count',
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

    public function scopeAllFileSuccess($query)
    {
        return $query->whereIn('name');
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

    public function getCoverMediaAttribute(): ?file
    {
        return $this->files()->with('poster')->ofSort(1)->first();
    }

    public function getAllFileNames(): array
    {
        $prefix = preg_replace("/\//", "\\\/", config('filesystems.disks.b2.asset_prefix'));

        $pattern = "/$prefix(.*)\./U";

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
        $names = $this->getAllFileNames();

        $sort = array_flip($names);

        $files = File::inNames($names)->get();

        foreach ($files as $file) {
            $file->post()->associate($this);
            $file->sort = $sort[$file->name] + 1;
            $file->save();
        }
    }

    // /**
    //  * get the first image or video or audio file of this post
    //  *
    //  * @param string $type
    //  * @return File|null
    //  */
    // public function getFirstFileFor(string $type): ?File
    // {
    //     $assetPrefix = config('filesystems.disks.b2.asset_prefix');

    //     $assetPrefix = preg_replace("/\//", "\\\/", $assetPrefix);

    //     switch ($type) {
    //         case File::TYPE_VIDEO:
    //             $pattern = "/<source src=\"" . $assetPrefix . "(.*)\">/U";
    //             break;
    //         case File::TYPE_IMAGE:
    //             $pattern = "/\!\[\]\(" . $assetPrefix . "(.*)." . File::ENCODE_IMAGE_EXT . "\)/U";
    //             break;
    //         default:
    //             break;
    //     }

    //     preg_match_all($pattern, $this->body, $res);

    //     if (!count($res[0])) {
    //         return null;
    //     }

    //     $fileName = $res[1][0];

    //     return File::query()
    //         ->with('poster')
    //         ->ofName($fileName)
    //         ->first();
    // }

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
            ->with('tags')
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
