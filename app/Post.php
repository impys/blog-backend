<?php

namespace App;

use App\Traits\HasEnabled;
use Illuminate\Support\Str;
use Illuminate\Mail\Markdown;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Post extends Model
{
    use HasEnabled;
    use Searchable;

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
        'created_at_human',
        'updated_at_human',
        'length',
        'audio_count',
        'cover_media',
        'summary',
    ];

    protected $attributes = [
        'is_enabled' => true
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($self) {
            $self->user_id = auth()->user()->id;
        });
    }

    public function shouldBeSearchable()
    {
        return $this->is_enabled;
    }

    public function splitBody()
    {
        return array_filter(explode('<split>', $this->getCleanBody()));
    }

    public function toSearchableArray()
    {
        $array['id'] = $this->id;

        $array['title'] = $this->title;

        $array['body'] = $this->body;

        $array['tags'] = $this->buildTagsForSearch();

        $array['visited_count'] = $this->visited_count;

        $array['cover_media'] = $this->cover_media;

        $array['created_at'] = $this->created_at;

        $array['updated_at'] = $this->updated_at;

        return $array;
    }

    public function getCleanBody(): string
    {
        return collect(
            explode(PHP_EOL, Markdown::parse($this->body)
                ->toHtml())
        )
            ->map(function ($dom) {
                $value = strip_tags($dom);
                if (Str::startsWith($dom, '<h2>')) {
                    $value = '<split>' . strip_tags($dom);
                }
                return $value;
            })->reduce(function ($carry, $item) {
                return $carry . $item;
            });
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
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
        return $this->morphMany(File::class, 'entity');
    }

    public function scopeInTagIds($query, $tagIds)
    {
        return $query->whereHas('tags', function ($query) use ($tagIds) {
            return $query->when(count($tagIds), function ($query) use ($tagIds) {
                return $query->whereIn('id', $tagIds);
            });
        });
    }

    public function scopeOfTagId($query, $tagId)
    {
        return $query->inTagIds([$tagId]);
    }

    public function scopeMostVisit($query)
    {
        return $query->orderBy('visited_count', 'desc');
    }

    public function scopeTop($query)
    {
        return $query->orderBy('is_top', 'desc');
    }

    public function scopeMostUpvote($query)
    {
        return $query->orderBy('upvote_count', 'desc');
    }

    public function scopeOfChapter($query, int $chapter)
    {
        return $query->where('chapter', $chapter);
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getUpdatedAtHumanAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getLengthAttribute(): int
    {
        return mb_strlen($this->body, 'utf-8');
    }

    public function getSummaryAttribute(): ?string
    {
        preg_match("/<p>(.)*<\/p>/U", Markdown::parse($this->body), $results);

        if (!count($results)) {
            return null;
        }

        return Str::limit(strip_tags($results[0]), 300);
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
        $prefix = preg_replace("/\//", "\\\/", Storage::url('/'));

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
        $this->files()->update(
            [
                'entity_id' => null,
                'entity_type' => null,
                'sort' => null,
            ]
        );
    }

    protected function associateFiles()
    {
        $names = $this->getAllFilesName();

        $sort = array_flip($names);

        $files = File::inNames($names)->get();

        foreach ($files as $file) {
            $file->entity()->associate($this);
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

    public function beVisited()
    {
        DB::table('posts')
            ->where('id', $this->id)
            ->increment('visited_count', 1);
    }

    public function syncSlug()
    {
        if (App::environment('production')) {
            try {
                $slug = GoogleTranslate::trans($this->title);
            } catch (\Throwable $th) {
                $slug = $this->id;
            }
        } else {
            $slug = $this->id;
        }

        DB::table('posts')
            ->where('id', $this->id)
            ->update(['slug' => $slug]);
    }

    public function getPrevChapter(): ?self
    {
        return $this->getAdjacentChapters('prev');
    }

    public function getNextChapter(): ?self
    {
        return $this->getAdjacentChapters('next');
    }

    /**
     * get prev chapter or next chapter of this post in the book
     *
     * @param string $selector prev or next
     * @return self|null
     */
    protected function getAdjacentChapters(string $selector): ?self
    {
        if (!$this->book) {
            return null;
        }

        $posts = $this->book
            ->posts()
            ->enabled()
            ->whereNotNull('chapter')
            ->get();

        [$prevPosts, $nextPosts] = $posts->filter(function ($post) {
            return $post->chapter != $this->chapter;
        })->partition(function ($post) {
            return $post->chapter < $this->chapter;
        });

        $prevPost = $prevPosts->sortByDesc('chapter')->first();
        $nextPost = $nextPosts->sortBy('chapter')->first();

        if ($selector === 'prev') {
            return $prevPost;
        }

        if ($selector === 'next') {
            return $nextPost;
        }

        return null;
    }
}
