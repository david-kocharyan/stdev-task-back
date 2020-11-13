<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['namespace' => 'Api', "prefix" => "v1"], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post('sign-up', 'AuthController@signup');
        Route::post('sign-in', 'AuthController@login');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('get-user', 'AuthController@user');
            Route::post('edit', 'AuthController@edit');
            Route::post('change-password', 'AuthController@changePassword');
        });
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('get-all-article', 'ArticleController@index');
        Route::post('create-article', 'ArticleController@store');
        Route::get('get-article', 'ArticleController@getArticle');

        Route::get('get-comments', 'CommentController@getComments');
        Route::post('add-comment', 'CommentController@store');
    });

    Route::get('get-all-categories', 'CategoryController@index');
});
