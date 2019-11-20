<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

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
}
