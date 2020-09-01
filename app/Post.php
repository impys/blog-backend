<?php

namespace App;

use App\Facades\Trans;
use App\Traits\SyncFiles;
use App\Traits\HasEnabled;
use App\Traits\ModelExtend;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Mail\Markdown;
use App\Exceptions\PostException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Post extends Model
{
    use HasEnabled;
    use SyncFiles;
    use Searchable;
    use ModelExtend;

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
        'length',
        'audio_count',
        'summary',
        'full_title',
        'full_title_with_dash',
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
        $data["id"] = $this->id;
        $data["title"] = $this->title;
        $data['body'] = $this->body;
        $data["is_top"] = $this->is_top;
        $data['visited_count'] = $this->visited_count;
        $data["updated_at"] = $this->updated_at;
        $data["created_at"] = $this->created_at;
        $data["cover_media"] = $this->getCoverMedia();
        $data["tags"] = $this->tags;
        $data["tag_names"] = $this->tags->map(fn ($tag) => $tag->name);
        $data["book"] = $this->book;
        $data["book_name"] = optional($this->book)->name;

        return $data;
    }

    public function splitBody()
    {
        return array_filter(explode('<split>', $this->getPlainBody()));
    }

    public function getPlainBody(): string
    {
        return collect(explode(PHP_EOL, Markdown::parse($this->body)->toHtml()))
            ->map(
                fn ($html) => Str::startsWith($html, '<h2>')
                    ? '<split>' . strip_tags($html)
                    : strip_tags($html)
            )->reduce(
                fn ($carry, $item) => $carry . $item
            );
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

    public function getFullTitleWithDashAttribute()
    {
        if ($this->book_id) {
            return "{$this->title} - {$this->book->title}";
        } else {
            return $this->title;
        }
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

        // 匹配以 https://xxx.com/ 开头，中间是任意字符，然后以 \n 或者 ) 结尾的字符串
        // https://xxx.com/33BfxLQFsUUEcXWFYvHvf8IRkiJ7as31NoVQiSMC.mpga\n
        // https://xxx.com/toC9A5RxKkQI43voYcznhPgIkROshplyhFVOvciz.png)
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

    public function beVisited()
    {
        DB::table('posts')
            ->where('id', $this->id)
            ->increment('visited_count', 1);
    }

    public function syncSlug()
    {
        try {
            $slug = Trans::trans($this->full_title_with_dash);
        } catch (\Throwable $th) {
            $slug = $this->id;
        }

        $this->slug = $slug;

        $this->saveQuietly();
    }

    public function syncChapter()
    {
        if (!$this->book_id) {
            throw new PostException("Book id is required");
        }

        if (!$this->chapter) {
            $this->setDefaultChapter();
        }

        $this->reorderChapters();
    }

    /**
     * Set default chapter(max chapter + 1)
     *
     * @return void
     */
    public function setDefaultChapter()
    {
        $lastChapter = $this->book->posts()->max('chapter') ?? 0;

        $this->chapter = $lastChapter + 1;

        $this->withoutTimestamps()  // 不更新时间
            ->saveQuietly();  // 不触发 saved 事件，避免递归
    }

    public function reorderChapters()
    {
        $index = 1;

        $this->book
            ->posts()
            ->where('id', '!=', $this->id)
            ->orderBy('chapter')
            ->each(function ($post) use (&$index) {
                if ($index === $this->chapter) {
                    $index++;
                }

                $post->chapter = $index;

                if ($post->isDirty('chapter')) {
                    $post->withoutTimestamps()  // 不更新时间
                        ->saveQuietly();  // 不触发 saved 事件，避免递归
                }

                $index++;
            });
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

    public function upvote()
    {
        $this->upvote_count++;
        $this->withoutTimestamps()->saveQuietly();
    }
}
