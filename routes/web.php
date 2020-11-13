<?php

use Illuminate\Support\Facades\Route;

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

//Auth::routes();

Route::get('/admin-login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin-login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
Route::post('/admin-logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

Route::group(['namespace' => 'Admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::resource('/users', 'UserController');
    Route::resource('/categories', 'CategoryController');
});

