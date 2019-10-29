<?php

namespace App\Traits;

trait HasEnable
{
    public function scopeEnable($query)
    {
        return $query->where('is_enable', true);
    }

    public function scopeDisable($query)
    {
        return $query->where('is_enable', false);
    }
}
