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
   Route::get('/sendinventory/{id}', 'SendController@getInventory');
   Route::get('/sendgen/{id}', 'SendController@Sendgen');
   Route::post('/send', 'SendController@Sendstore');
   Route::get('/{id}/send_edit', 'SendController@Sendedit');
   Route::patch('/send_edit/{id}', 'SendController@Sendupdate');
   Route::delete('/send/{id}', 'SendController@Senddelete');
   /**
    * Accept Routes
    */
    Route::get('/{id}/accept', 'AcceptController@index');
    Route::get('/{id}/delivery', 'AcceptController@getDelivery');
    Route::patch('/accept/{id}', 'AcceptController@Accept');
});

/**
 * Stockopname Routes
 */
 Route::get('stockopname', 'OpnameController@index');
 Route::get('/stockopname/getopname', 'OpnameController@getOpname');
 Route::post('/opname', 'OpnameController@opname');
 Route::get('/stockopname/{id}', 'OpnameController@detailopname');
 Route::get('/stockopname/getopnamedetail/{id}', 'OpnameController@getOpnamedetail');
 Route::get('/stockopname/{id}/show/{idwh}', 'OpnameController@getDataopname');
 Route::post('/opnamestore', 'OpnameController@opnamestore');
 Route::post('/opnamefinish/{id}', 'OpnameController@opnamefinish');
