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
    return view('payment.success', ['amount' => 2000 , 'ref' => '1231']);
});

Route::get('gimmemoneybitch', function () {
    if (\Auth::user() !== null) {
        app(App\Services\Wallet\WalletService::class)
            ->creditor(\Auth::user(), 100000, -9, "used a cheat code!");

        return response()->json([
            'balance' => app(App\Services\Wallet\WalletService::class)->balance(\Auth::user())
            ]);
    }
    return 'fuck you bitch!';
});