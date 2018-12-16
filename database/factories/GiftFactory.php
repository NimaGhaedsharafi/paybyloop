<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 2:36 PM
 */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Gift::class, function (Faker $faker) {
    return [
        'amount' => rand(1, 10) * 1000,
        'title' => $faker->name,
        'expires_in' => Carbon::tomorrow(),
        'code' => $faker->postcode,
        'max_use_time' => 10,
    ];
});
