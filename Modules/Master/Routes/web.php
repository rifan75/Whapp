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
Route::get('master/home', 'HomeController@index')->name('home');

/**
 * Brand Routes
 */
Route::get('master/brand', 'BrandController@index');
Route::get('master/getbrand', 'BrandController@getBrand');
Route::post('master/brand', 'BrandController@brandstore');
Route::get('master/brand/{id}/edit', 'BrandController@brandedit');
Route::patch('master/brand/{id}', 'BrandController@brandupdate');
Route::delete('master/brand/{id}', 'BrandController@branddelete');

/**
 * Measure Routes
 */
Route::get('master/measure', 'MeasureController@index');
Route::get('master/getmeasure', 'MeasureController@getMeasure');
Route::post('master/measure', 'MeasureController@measurestore');
Route::get('master/measure/{id}/edit', 'MeasureController@measureedit');
Route::patch('master/measure/{id}', 'MeasureController@measureupdate');
Route::delete('master/measure/{id}', 'MeasureController@measuredelete');

/**
 * Supplier Routes
 */
Route::get('master/supplier', 'SupplierController@index');
Route::get('master/getsupplier', 'SupplierController@getSupplier');
Route::post('master/supplier', 'SupplierController@supplierstore');
Route::get('master/supplier/{id}/edit', 'SupplierController@supplieredit');
Route::patch('master/supplier/{id}', 'SupplierController@supplierupdate');
Route::delete('master/supplier/{id}', 'SupplierController@supplierdelete');

/**
 * Warehouse Routes
 */
Route::get('master/warehouse', 'WarehouseController@index');
Route::get('master/getwarehouse', 'WarehouseController@getWarehouse');
Route::post('master/warehouse', 'WarehouseController@warehousestore');
Route::get('master/warehouse/{id}/edit', 'WarehouseController@warehouseedit');
Route::patch('master/warehouse/{id}', 'WarehouseController@warehouseupdate');
Route::delete('master/warehouse/{id}', 'WarehouseController@warehousedelete');
