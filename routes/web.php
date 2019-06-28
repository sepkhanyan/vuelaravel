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

    //post
    Route::get('/posts', 'PostController@index');
    Route::post('/post/create', 'PostController@store');
    Route::get('/post/show/{id}', 'PostController@show');
    Route::get('/post/edit/{id}', 'PostController@edit');
    Route::post('/post/update/{id}', 'PostController@update');
    Route::post('/post/delete/{id}', 'PostController@destroy');
    Route::post('/image/delete/{id}', 'PostController@deleteImage');

    //post comments
    Route::post('/add/comment', 'PostController@comment');
    Route::get('/post/comments/{id}', 'PostController@comments');
    Route::post('/comment/delete/{id}', 'PostController@deleteComment');

    //users
    Route::get('/users', 'UsersController@index');
    Route::get('/user/edit/{id}', 'UsersController@edit');
    Route::post('/user/update/{id}', 'UsersController@update');
    Route::post('/user/delete/{id}', 'UsersController@destroy');

});
