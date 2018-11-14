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

    /**
     * Registration Routes
     */
    Route::get('user', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    /**
     * User Routes
     */
    Route::get('/getuser', 'UserController@getUser');
    Route::get('/user/{id}/edit', 'UserController@useredit');
    Route::patch('/useract/{id}/{act}', 'UserController@useractupdate');
    Route::patch('/user/{id}', 'UserController@userupdate');
    Route::delete('/user/{id}', 'UserController@userdelete');

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

// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');



// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
