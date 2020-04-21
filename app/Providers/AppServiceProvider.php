<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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

        \App\Search\Feeds::bootSearchable();

        Relation::morphMap([
            'post' => 'App\Post',
            'files' => 'APP\File',
            'book' => 'App\Book',
        ]);
    }
}
