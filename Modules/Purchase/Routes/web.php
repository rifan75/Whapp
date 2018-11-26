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

Route::prefix('purchase')->group(function() {
    Route::get('/', 'PurchaseController@index');
    Route::get('/getpurchase', 'PurchaseController@getPurchase');
    Route::get('/order', 'PurchaseController@purchaseorder');
    Route::get('/getproduct', 'PurchaseController@getProduct');
    Route::get('/gen', 'PurchaseController@purchasegen');
    Route::post('/', 'PurchaseController@purchasestore');
    Route::get('/{id}/edit', 'PurchaseController@purchaseedit');
    Route::patch('/{id}', 'PurchaseController@purchaseupdate');
    Route::get('/{id}/print', 'PurchaseController@purchaseprint');
});
