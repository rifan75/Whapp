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
* Product Routes
*/
Route::get('product', 'ProductController@index');
Route::get('product/getproduct', 'ProductController@getProduct');
Route::post('product', 'ProductController@productstore');
Route::get('product/{id}/edit', 'ProductController@productedit');
Route::patch('product/{id}', 'ProductController@productupdate');
Route::delete('product/{id}', 'ProductController@productdelete');
Route::get('product/productgen/{string}', 'ProductController@productgen');

/**
* Product Image Routes
*/
Route::get('product/image', 'ProductimageController@index');
Route::get('product/getproductimage', 'ProductimageController@getProduct');
Route::patch('product/{id}/editimage', 'ProductimageController@productimageedit');
/**
* Beginning Balance Routes
*/
Route::get('product/manual', 'ManualController@index');
Route::get('product/getunsettleproduct', 'ManualController@getProduct');
Route::get('product/getinventorymanual', 'ManualController@getInventoryManual');
Route::post('product/inputmanual', 'ManualController@inputManual');
Route::get('product/manual/{id}/edit', 'ManualController@manualedit');
Route::patch('product/manualedit/{id}', 'ManualController@manualupdate');
Route::delete('product/manual/{id}', 'ManualController@manualdelete');
