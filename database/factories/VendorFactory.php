<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/24/18
 * Time: 9:33 PM
 */

use Faker\Generator as Faker;

$factory->define(App\Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'vendor_id' => str_random(6),
        'address' => $faker->address,
        'coordinate' => '35.7698678,51.3739048',
        'phone' => $faker->phoneNumber,
        'description' => $faker->text,
        'photo' => $faker->imageUrl(),
        'is_enabled' => 1
    ];
});
