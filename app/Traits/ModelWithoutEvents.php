<?php

namespace App\Traits;

trait ModelWithoutEvents
{
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    public function withoutTimestamps()
    {
        $this->timestamps = false;
        return $this;
    }
}
