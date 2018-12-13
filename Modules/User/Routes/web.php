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

/**
 * Registration Routes
 */
Route::get('user/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('user/register', 'Auth\RegisterController@register');

/**
 * User Routes
 */
Route::get('user/getuser', 'UserController@getUser');
Route::get('user/{id}/edit', 'UserController@useredit');
Route::patch('user/useract/{id}/{act}', 'UserController@useractupdate');
Route::patch('user/{id}', 'UserController@userupdate');
Route::delete('user/{id}', 'UserController@userdelete');

/**
 * Profile Routes
 */
Route::get('user/profile', 'ProfileController@index');
Route::get('user/profileedit', 'ProfileController@edit');
Route::get('user/getprofile', 'ProfileController@getProfile');
Route::patch('user/profile/{id}/edit', 'ProfileController@profileupdate');
Route::get('user/changepasswd', 'UserController@changepasswd');
Route::patch('user/changepasswd/{id}', 'UserController@updatepasswd');

// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
