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

Route::get('/', 'Welcome');
Route::get('/articles', 'ShowArticles');
Route::get('/articles/{article}', 'ShowArticle');
Route::get('/search', 'Search');

// Auth::routes();
