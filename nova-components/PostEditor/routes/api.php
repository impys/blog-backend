<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::post('/upload', \App\Http\Controllers\Nova\Upload::class);
Route::get('/search', \App\Http\Controllers\Nova\Search::class);
