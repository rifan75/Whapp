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

Route::get('purchase', 'PurchaseController@index');
Route::get('purchase/getpurchase', 'PurchaseController@getPurchase');
Route::get('purchase/order', 'PurchaseController@purchaseorder');
Route::get('purchase/getproduct', 'PurchaseController@getProduct');
Route::get('purchase/gen', 'PurchaseController@purchasegen');
Route::post('purchase', 'PurchaseController@purchasestore');
Route::get('purchase/{id}/edit', 'PurchaseController@purchaseedit');
Route::patch('purchase/{id}', 'PurchaseController@purchaseupdate');
Route::get('purchase/{id}/print', 'PurchaseController@purchaseprint');
Route::delete('purchase/{id}', 'PurchaseController@purchasedelete');
