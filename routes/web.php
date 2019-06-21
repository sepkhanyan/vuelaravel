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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/posts', 'PostController@index');
    Route::post('/post/create', 'PostController@store');
    Route::get('/post/edit/{id}', 'PostController@edit');
    Route::get('/post/comments/{id}', 'PostController@comments');
    Route::get('/post/show/{id}', 'PostController@show');
    Route::post('/post/update/{id}', 'PostController@update');
    Route::post('/post/delete/{id}', 'PostController@destroy');
});
