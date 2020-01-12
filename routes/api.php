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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['api', 'throttle']], function () {
    Route::get('/posts', 'GetPosts');
    Route::get('/posts/{post}', 'GetPost');
    Route::get('/simple-search', 'SimpleSearch');
    Route::get('/search', 'Search');
    Route::get('/tags', 'GetTags');
});
