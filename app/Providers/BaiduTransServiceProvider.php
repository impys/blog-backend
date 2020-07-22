<?php

namespace App\Providers;

use App\Services\BaiduTransService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class BaiduTransServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BaiduTransService::class, function () {
            return new BaiduTransService(
                config('services.baidufanyi.app_id'),
                config('services.baidufanyi.secret')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides()
    {
        return [
            BaiduTransService::class
        ];
    }
}
