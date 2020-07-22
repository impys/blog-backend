<?php

namespace App;

use App\Traits\SyncFiles;
use App\Traits\HasEnabled;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Database\Eloquent\Collection;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Post extends Model
{
    use HasEnabled;
    use SyncFiles;
    use Searchable;
    use CustomSearch;

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
        'summary',
        'full_title',
    ];

    protected $attributes = [
        'is_enabled' => true
    ];

    protected $touches = ['book'];

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

    public function toSearchableArray()
    {
        $array['id'] = $this->id;

        $array['title'] = $this->title;

        $array['body'] = $this->getPlainBodyText();

        $array['tags'] = $this->buildTagsForSearch();

        $array['visited_count'] = $this->visited_count;

        $array['is_top'] = $this->is_top;

        $array['created_at'] = $this->created_at;

        $array['updated_at'] = $this->updated_at;

        return $array;
    }

    public function getPlainBodyText(): string
    {
        return collect(explode(PHP_EOL, Markdown::parse($this->body)->toHtml()))
            ->map(function ($stringWithHtml) {
                return strip_tags($stringWithHtml);
            })->reduce(function ($carry, $pureString) {
                return $carry . $pureString;
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

    public function getFullTitleAttribute()
    {
        if ($this->book_id) {
            return "{$this->title}『{$this->book->title}』";
        } else {
            return $this->title;
        }
    }

    public function setChapterAttribute($value)
    {
        if (!$this->book_id) {
            $this->attributes['chapter'] = null;
        } else {
            if ($value) {
                $this->attributes['chapter'] = $value;
            } else {
                $lastChapter = $this->book->posts()->where('id', '!=', $this->id)->max('chapter');
                $this->attributes['chapter'] = $lastChapter ? $lastChapter + 1 : 1;
            }
        }
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
        return collect(
            explode(PHP_EOL, Markdown::parse($this->body)->toHtml())
        )->filter(
            fn ($html) => Str::containsAll($html, ['<p>', '</p>'])
                && !Str::contains($html, ['code>', 'audio>', 'img>', 'a>'])
        )->map(
            fn ($html) => html_entity_decode(strip_tags($html))
        )->sortByDesc(
            fn ($html) => strlen($html)
        )->first();
    }

    public function getAudioCountAttribute(): int
    {
        return $this->files->where('type', File::TYPE_AUDIO)->count();
    }

    public function getCoverMedia(): ?file
    {
        $name = $this->getFirstFileName();

        return $this->files->firstWhere('name', $name);
    }

    /**
     * get the first image or audio of this post
     *
     * @return string|null
     */
    public function getFirstFileName(): ?string
    {
        return $this->getAllFilesName()[0] ?? null;
    }

    /**
     * get images and audios from post body by regex match
     *
     * @return array
     */
    public function getAllFilesName(): array
    {
        $prefix = preg_replace("/\//", "\\\/", Storage::url('/'));

        // 匹配以 https://cdn.atom.ac.cn/ 开头，中间是任意字符，然后以 \n 或者 ) 结尾的字符串
        // https://cdn.atom.ac.cn/33BfxLQFsUUEcXWFYvHvf8IRkiJ7as31NoVQiSMC.mpga\n
        // https://cdn.atom.ac.cn/toC9A5RxKkQI43voYcznhPgIkROshplyhFVOvciz.png)
        $pattern = "/$prefix(.*)(\)|\\n)/U";

        preg_match_all($pattern, $this->body, $results);

        return $results[1];
    }

    /**
     * Get the files belongs to this post
     *
     * @return Collection
     */
    public function getAllFiles(): Collection
    {
        $names = $this->getAllFilesName();

        return File::query()
            ->inNames($names)
            ->get();
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
     * get tags id and name for elasticsearch
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
                $slug = GoogleTranslate::trans($this->full_title);
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

        [$prevPosts, $nextPosts] = $posts
            ->filter(fn ($post) => $post->chapter != $this->chapter)
            ->partition(fn ($post) => $post->chapter < $this->chapter);

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
