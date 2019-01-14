<?php

// Public
Route::group(['as' => 'v1.', 'prefix' => 'v1'], function () {

    Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User'], function () {
        Route::post('config', ['as' => 'config', 'uses' => 'ProfileController@config', 'middleware' => 'jwt-optional']);
        Route::post('otp', ['as' => 'otp', 'uses' => 'AuthController@otp']);
        Route::post('otp/validate', ['as' => 'otp.validate', 'uses' => 'AuthController@otpValidate']);
        Route::post('otp/login', ['as' => 'otp.login', 'uses' => 'AuthController@otpLogin']);
        Route::post('otp/register', ['as' => 'otp.register', 'uses' => 'AuthController@otpRegister']);
        Route::any('charge/ipg/callback', ['as' => 'charge.ipg.callback', 'uses' => 'PaymentController@ipgCallback']);
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
            Route::post('voucher', ['as' => 'voucher', 'uses' => 'WalletController@voucherCheck']);
            Route::get('balance', ['as' => 'balance', 'uses' => 'WalletController@balance']);
            Route::get('receipt/{code}', ['as' => 'receipt', 'uses' => 'WalletController@receipt']);
        });
        Route::group(['prefix' => 'promotion', 'as' => 'promotion.'], function () {
            Route::post('gift', ['as' => 'gift', 'uses' => 'PromotionController@gift']);
        });

        Route::group(['prefix' => 'charge', 'as' => 'charge.'], function () {
            Route::post('ipg', ['as' => 'ipg', 'uses' => 'PaymentController@ipg']);
            Route::get('auto', ['as' => 'auto', 'uses' => 'PaymentController@auto']);
        });
    });
});
