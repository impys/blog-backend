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

Route::combine([
    '/',
    '/search',
    '/posts',
    '/posts/{post}'
], function () {
    return view('app');
});

Route::middleware('auth')->group(function () {
    Route::post('/upload', 'Upload');
});

// Auth::routes();
