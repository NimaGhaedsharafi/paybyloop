<?php

use Illuminate\Http\Request;

Route::group(['as' => 'v1.', 'prefix' => 'v1'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User'], function () {
        Route::group(['prefix' => 'vendor', 'as' => 'vendor.'], function () {
            Route::get('/', ['as' => 'list', 'uses' => 'VendorController@index']);
        });
        Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
            Route::get('/', ['as' => 'list', 'uses' => 'WalletController@index']);
        });
    });
});