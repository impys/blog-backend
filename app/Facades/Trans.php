<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Trans extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'trans';
    }
}
