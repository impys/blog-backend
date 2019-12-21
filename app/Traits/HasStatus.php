<?php

namespace App\Traits;

trait HasStatus
{
    public function scopeOfStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInStatuses($query, array $statuses)
    {
        return $query->whereIn('status', $statuses);
    }

    public function scopeNotOfStatus($query, string $status)
    {
        return $query->where('status', '!=', $status);
    }

    public function scopeNotInStatuses($query, array $statuses)
    {
        return $query->whereNotIn('status', $statuses);
    }
}
