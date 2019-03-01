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

Route::get('/', function () {
    return view('welcome');
});


Route::namespace('Admin\Product')->name('products.')->middleware('guest')->group(function () {
    Route::get('download-sample', 'DownloadController@downloadSample')->name('products.download.sample');
});
