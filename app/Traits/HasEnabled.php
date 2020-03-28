<?php

namespace App\Traits;

trait HasEnabled
{
    public function scopeEnable($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeDisable($query)
    {
        return $query->where('is_enabled', false);
    }
}
