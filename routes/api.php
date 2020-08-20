<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api', 'throttle']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/books-in-random-order', 'HomeController@booksInRandomOrder');
    Route::get('/posts-in-random-order', 'HomeController@postsInRandomOrder');
    Route::get('/posts', 'GetPosts');
    Route::get('/posts/{post}', 'GetPost');
    Route::get('/tags', 'GetTags');
    Route::get('/books', 'GetBooks');
    Route::get('/books/{book}', 'GetBook');
});
