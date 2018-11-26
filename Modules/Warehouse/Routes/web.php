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

Route::prefix('warehouse')->group(function() {
    /**
     * Inventory Routes
     */
    Route::get('/{id}', 'InventoryController@index');
    Route::get('/getinventory/{id}', 'InventoryController@getinventory');
    Route::get('/{id}/show/{idtab}', 'InventoryController@getDetailInventory');

    /**
     * Send Routes
     */
   Route::get('/{id}/send', 'SendController@index');
   Route::get('/getsend/{id}', 'SendController@getSend');
   Route::get('/addsend/{id}', 'SendController@AddSend');
});
