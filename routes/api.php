<?php

// Public
Route::group(['as' => 'v1.', 'prefix' => 'v1'], function () {

    Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User'], function () {
        Route::post('config', ['as' => 'config', 'uses' => 'ProfileController@config']);
        Route::post('otp', ['as' => 'otp', 'uses' => 'AuthController@otp']);
        Route::post('otp/login', ['as' => 'otp.login', 'uses' => 'AuthController@otpLogin']);
        Route::post('otp/register', ['as' => 'otp.register', 'uses' => 'AuthController@otpRegister']);
        Route::post('charge/ipg/callback/{code}', ['as' => 'charge.ipg.callback', 'uses' => 'PaymentController@ipgCallback']);
    });
});

// Authenticated
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
        Route::group(['prefix' => 'promotion', 'as' => 'promotion.'], function () {
            Route::post('redeem', ['as' => 'redeem', 'uses' => 'PromotionController@redeem']);
        });

        Route::group(['prefix' => 'charge', 'as' => 'charge.'], function () {
            Route::post('ipg', ['as' => 'ipg', 'uses' => 'PaymentController@ipg']);
        });
    });
});
