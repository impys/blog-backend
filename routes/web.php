<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('Welcome');
});
Route::get('/posts', 'FetchPosts');
Route::get('/posts/{post}', 'ShowPost');
Route::get('/search', 'Search');

// Auth::routes();
