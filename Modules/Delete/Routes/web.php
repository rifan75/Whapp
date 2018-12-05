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

Route::prefix('delete')->group(function() {
    Route::get('/user', 'DeleteController@user');
    Route::get('/brand', 'DeleteController@brand');
    Route::get('/measure', 'DeleteController@measure');
    Route::get('/supplier', 'DeleteController@supplier');
    Route::get('/warehouse', 'DeleteController@warehouse');
    Route::get('/product', 'DeleteController@product');
    Route::get('/purchase', 'DeleteController@purchase');
    Route::get('/send', 'DeleteController@send');
    Route::get('/inventory', 'DeleteController@inventory');
    Route::post('/{id}/restore/{model}', 'DeleteController@restore');
    Route::delete('/{id}/{model}', 'DeleteController@delete');
});
