<?php

namespace App\Traits;

trait HasEnabled
{
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeDisabled($query)
    {
        return $query->where('is_enabled', false);
    }
}
