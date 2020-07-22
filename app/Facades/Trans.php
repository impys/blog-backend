<?php

namespace App\Facades;

use App\Services\BaiduTransService;
use Illuminate\Support\Facades\Facade;

class Trans extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaiduTransService::class;
    }
}
