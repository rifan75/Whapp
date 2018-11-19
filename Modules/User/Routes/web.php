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

Route::prefix('user')->group(function() {

      /**
       * Registration Routes
       */
      Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
      Route::post('register', 'Auth\RegisterController@register');

      /**
       * User Routes
       */
      Route::get('/getuser', 'UserController@getUser');
      Route::get('/{id}/edit', 'UserController@useredit');
      Route::patch('/useract/{id}/{act}', 'UserController@useractupdate');
      Route::patch('/{id}', 'UserController@userupdate');
      Route::delete('/{id}', 'UserController@userdelete');

      /**
       * Profile Routes
       */
      Route::get('/profile', 'ProfileController@index');
      Route::get('/profileedit', 'ProfileController@edit');
      Route::get('/getprofile', 'ProfileController@getProfile');
      Route::patch('/profile/{id}/edit', 'ProfileController@profileupdate');
      Route::get('/changepasswd', 'UserController@changepasswd');
      Route::patch('/changepasswd/{id}', 'UserController@updatepasswd');
});

// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
