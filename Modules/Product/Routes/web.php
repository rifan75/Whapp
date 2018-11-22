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

Route::prefix('product')->group(function() {
    /**
    * Product Routes
    */
    Route::get('/', 'ProductController@index');
    Route::get('/getproduct', 'ProductController@getProduct');
    Route::post('/', 'ProductController@productstore');
    Route::get('/{id}/edit', 'ProductController@productedit');
    Route::patch('/{id}', 'ProductController@productupdate');
    Route::delete('/{id}', 'ProductController@productdelete');
    Route::get('/productgen/{string}', 'ProductController@productgen');

    /**
    * Product Image Routes
    */
    Route::get('/image', 'ProductimageController@index');
    Route::get('/getproductimage', 'ProductimageController@getProduct');
    Route::patch('/{id}/editimage', 'ProductimageController@productimageedit');
    /**
    * Beginning Balance Routes
    */
    Route::get('/manual', 'ManualController@index');
    Route::get('/getunsettleproduct', 'ManualController@getProduct');
    Route::get('/getinventorymanual', 'ManualController@getInventoryManual');
    Route::post('/inputmanual', 'ManualController@inputManual');
    Route::get('/manual/{id}/edit', 'ManualController@manualedit');
    Route::patch('/manualedit/{id}', 'ManualController@manualupdate');
    Route::delete('/manual/{id}', 'ManualController@manualdelete');
});
