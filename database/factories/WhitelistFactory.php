<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 3:58 PM
 */

$factory->define(App\VendorWhitelist::class, function () {
    return [
        'voucher_id' => 0,
        'vendor_id' => 0
    ];
});

$factory->define(App\UserWhitelist::class, function () {
    return [
        'voucher_id' => 0,
        'user_id' => 0
    ];
});
