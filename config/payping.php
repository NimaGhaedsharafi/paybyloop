<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 11/3/18
 * Time: 9:04 PM
 */

return [
    'token' => env('PAYPING_TOKEN'),
    'base_uri' => env('PAYPING_BASE_URI', 'https://api.payping.ir/'),
    'ips' => [], // TODO: needs to be defined
];