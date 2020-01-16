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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', 'PagesController@test');
Route::get('/test1', 'PagesController@test1');

Route::resource('posts', 'PostController');

Route::resource('repos', 'ReposConstroller');

Route::resource('commits', 'CommitsConstroller');

Route::get('orgs/details/{id}', 'ReposConstroller@details');

Route::get('stats', 'ReposConstroller@stats');