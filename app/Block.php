<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'name',
        'label',
        'is_top',
        'sort'
    ];

    protected $casts = [
        'is_top' => 'boolean'
    ];
}
