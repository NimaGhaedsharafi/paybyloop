<?php

Route::group(['as' => 'v1.', 'prefix' => 'v1', 'middleware' => 'jwt'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User'], function () {
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('/', ['as' => 'show', 'uses' => 'ProfileController@show']);
        });
        Route::group(['prefix' => 'vendor', 'as' => 'vendor.'], function () {
            Route::get('/', ['as' => 'list', 'uses' => 'VendorController@index']);
            Route::get('{vendor_id}/show', ['as' => 'show', 'uses' => 'VendorController@show']);
        });
        Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
            Route::get('/', ['as' => 'list', 'uses' => 'WalletController@index']);
            Route::post('pay', ['as' => 'pay', 'uses' => 'WalletController@pay']);
            Route::get('balance', ['as' => 'balance', 'uses' => 'WalletController@balance']);
        });
    });
});
