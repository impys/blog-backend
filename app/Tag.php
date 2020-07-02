<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function scopeInNames($query, array $names)
    {
        return $query->whereIn('name', $names);
    }

    public function scopeHasPosts($query)
    {
        return $query->whereHas('posts');
    }

    public static function findOrCreateByNames(array $names)
    {
        $names = collect($names)->unique()->all();
        $existNames = self::query()
            ->inNames($names)
            ->get()
            ->pluck('name')
            ->toArray();

        $notexistNames = Arr::where($names, function ($name) use ($existNames) {
            return !in_array($name, $existNames);
        });

        foreach ($notexistNames as $name) {
            $tag = new self(['name' => $name]);
            $tag->save();
        }

        return self::query()
            ->inNames($names)
            ->get();
    }

    public static function getAllValidTags()
    {
        return self::query()
            ->hasPosts()
            ->get()
            ->map(function ($tag) {
                return [
                    'value' => $tag->id,
                    'label' => $tag->name,
                ];
            });
    }
}
