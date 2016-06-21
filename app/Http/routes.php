<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/','AnimeInput@index');

Route::get('anime/search/{animeName}', 'AnimeInput@searchAnime');

Route::DELETE('anime/title/{id}', 'titleController@destroy');

Route::DELETE('anime/link/{id}', 'linkController@destroy');

Route::resource('anime', 'AnimeInput');

Route::resource('anime/staff', 'staffController');

Route::resource('anime/cast', 'castController');

Route::resource('anime/onair', 'onairController');

/*
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){
    $api->group(['namespace' => 'App\Api\Controllers'], function($api){
        //
    });
});
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
