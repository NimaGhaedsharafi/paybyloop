<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 3:58 PM
 */

$factory->define(App\Voucher::class, function () {
    $absolute = rand(1, 5) * 1000;
    return [
        'title' => 'My Awesome Campaign',
        'code' => 'Loop',
        'percent' => rand(10, 90),
        'absolute' => $absolute,
        'total_use' => rand(1, 100),
        'per_user' => rand(1, 5),
        'cap' => 0,
        'min' => 0,
        'only_on_first' => 0,
        'is_enabled' => 1,
        'whitelist_parent_id' => 0,
    ];
});
