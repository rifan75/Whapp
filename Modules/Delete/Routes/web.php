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
Route::get('delete/user', 'DeleteController@user');
Route::get('delete/brand', 'DeleteController@brand');
Route::get('delete/measure', 'DeleteController@measure');
Route::get('delete/supplier', 'DeleteController@supplier');
Route::get('delete/warehouse', 'DeleteController@warehouse');
Route::get('delete/product', 'DeleteController@product');
Route::get('delete/purchase', 'DeleteController@purchase');
Route::get('delete/send', 'DeleteController@send');
Route::get('delete/inventory', 'DeleteController@inventory');
Route::post('delete/{id}/restore/{model}', 'DeleteController@restore');
Route::delete('delete/{id}/{model}', 'DeleteController@delete');
