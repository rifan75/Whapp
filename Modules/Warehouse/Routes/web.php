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
 * Inventory Routes
 */
Route::get('warehouse/{id}', 'InventoryController@index');
Route::get('warehouse/getinventory/{id}', 'InventoryController@getinventory');
Route::get('warehouse/{id}/show/{idtab}', 'InventoryController@getDetailInventory');

/**
 * Send Routes
 */
Route::get('warehouse/{id}/send', 'SendController@index');
Route::get('warehouse/getsend/{id}', 'SendController@getSend');
Route::get('warehouse/addsend/{id}', 'SendController@AddSend');
Route::get('warehouse/sendinventory/{id}', 'SendController@getInventory');
Route::get('warehouse/sendgen/{id}', 'SendController@Sendgen');
Route::post('warehouse/send', 'SendController@Sendstore');
Route::get('warehouse/{id}/send_edit', 'SendController@Sendedit');
Route::patch('warehouse/send_edit/{id}', 'SendController@Sendupdate');
Route::delete('warehouse/send/{id}', 'SendController@Senddelete');
/**
* Accept Routes
*/
Route::get('warehouse/{id}/accept', 'AcceptController@index');
Route::get('warehouse/{id}/delivery', 'AcceptController@getDelivery');
Route::patch('warehouse/accept/{id}', 'AcceptController@Accept');

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
