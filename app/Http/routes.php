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

// 数据录入
Route::get('input/search/{animeName}', 'AnimeInput@searchAnime');

Route::get('input/{id}', 'AnimeInput@show')->where('id', '^\d+$');

Route::get('input/{any?}', 'AnimeInput@showAbbr');

Route::get('input', 'AnimeInput@index');

Route::post('input', 'AnimeInput@store');

Route::put('input/{request}', 'AnimeInput@update');

Route::DELETE('input/title/{id}', 'titleController@destroy');

Route::DELETE('input/link/{id}', 'linkController@destroy');

Route::resource('input/staff', 'staffController');

Route::resource('input/cast', 'castController');

Route::resource('onair/input', 'onairController');

// 管理页面
//// 显示页面
Route::get('manager', 'AnimeManagerPage@index');
Route::get('manager/import', 'AnimeManagerPage@edit');

//// 操作数据
Route::post('manager/resource/filt?={conditon}', 'AnimeMangerResource@filt');
Route::resource('manager/resource', 'AnimeManagerResource');

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
