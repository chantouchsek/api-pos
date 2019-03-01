<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Admin')->group(function () {
    Route::namespace('User')->prefix('users')->name('users.')->group(function () {
        Route::post('{user}/upload-avatar', 'UploadAvatarController@upload')->name('upload-avatar');
        Route::get('notifications', 'NotificationController@index')->name('notifications.index');
        Route::get('notifications/markAsRead', 'NotificationController@readNotification')->name('notifications.read');
        Route::get('notifications/unReads', 'NotificationController@unReads')->name('notifications.unread');
        Route::put('devices/{playerId}', 'DeviceController@update');
        Route::resource('devices', 'DeviceController', ['only' => ['index', 'store']]);
    });
    Route::resource('users', 'UserController');
    Route::resource('categories', 'CategoryController');
    Route::resource('roles', 'RoleController', ['except' => ['create', 'edit']]);
    Route::resource('permissions', 'PermissionController', ['except' => ['create', 'edit']]);
    Route::namespace('Product')->prefix('products')->name('products.')->group(function () {
        Route::resource('{product}/revisions', 'RevisionController', ['only' => ['index']]);
        Route::resource('imports', 'ImportController', ['only' => ['store']]);
    });
    Route::resource('products', 'ProductController', ['except' => ['create', 'edit']]);
    Route::resource('gift-cards', 'GiftCardController', ['except' => ['create', 'edit']]);
    Route::resource('expenses', 'ExpenseController', ['except' => ['create', 'edit']]);
    Route::resource('suppliers', 'SupplierController', ['except' => ['create', 'edit']]);
    Route::resource('customers', 'CustomerController', ['except' => ['create', 'edit']]);
});

