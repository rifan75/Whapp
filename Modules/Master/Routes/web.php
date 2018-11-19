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

Route::prefix('master')->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');

    /**
     * Brand Routes
     */
    Route::get('/brand', 'BrandController@index');
    Route::get('/getbrand', 'BrandController@getBrand');
    Route::post('/brand', 'BrandController@brandstore');
    Route::get('/brand/{id}/edit', 'BrandController@brandedit');
    Route::patch('/brand/{id}', 'BrandController@brandupdate');
    Route::delete('/brand/{id}', 'BrandController@branddelete');

    /**
     * Measure Routes
     */
    Route::get('/measure', 'MeasureController@index');
    Route::get('/getmeasure', 'MeasureController@getMeasure');
    Route::post('/measure', 'MeasureController@measurestore');
    Route::get('/measure/{id}/edit', 'MeasureController@measureedit');
    Route::patch('/measure/{id}', 'MeasureController@measureupdate');
    Route::delete('/measure/{id}', 'MeasureController@measuredelete');

    /**
     * Supplier Routes
     */
    Route::get('/supplier', 'SupplierController@index');
    Route::get('/getsupplier', 'SupplierController@getSupplier');
    Route::post('/supplier', 'SupplierController@supplierstore');
    Route::get('/supplier/{id}/edit', 'SupplierController@supplieredit');
    Route::patch('/supplier/{id}', 'SupplierController@supplierupdate');
    Route::delete('/supplier/{id}', 'SupplierController@supplierdelete');

    /**
     * Warehouse Routes
     */
    Route::get('/warehouse', 'WarehouseController@index');
    Route::get('/getwarehouse', 'WarehouseController@getWarehouse');
    Route::post('/warehouse', 'WarehouseController@warehousestore');
    Route::get('/warehouse/{id}/edit', 'WarehouseController@warehouseedit');
    Route::patch('/warehouse/{id}', 'WarehouseController@warehouseupdate');
    Route::delete('/warehouse/{id}', 'WarehouseController@warehousedelete');
});
