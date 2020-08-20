<?php

namespace App\Providers;

use App\Services\BaiduTransService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Contracts\Support\DeferrableProvider;

class TransServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('trans', function () {
            return App::environment('production')
                ? new GoogleTranslate
                : new BaiduTransService(
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
            'trans'
        ];
    }
}
