<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Post::observe(\App\Observers\PostObserver::class);
        \App\Book::observe(\App\Observers\BookObserver::class);

        Relation::morphMap([
            'post' => 'App\Post',
            'files' => 'APP\File',
            'book' => 'App\Book',
        ]);
    }
}
