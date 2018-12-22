<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/22/18
 * Time: 8:15 PM
 */

return [
    'version' => [
        1 => [ // Android
            'latest' => env('ANDROID_LATEST_VERSION', 1),
            'supported' => env('ANDROID_SUPPORTED_VERSION', 1),
            'url' => env('ANDROID_UPDATE_URL', 'https://paybyloop.app/download')
        ],
        2 => [ // iOS
            'latest' => env('IOS_LATEST_VERSION', 1),
            'supported' => env('IOS_SUPPORTED_VERSION', 1),
            'url' => env('IOS_UPDATE_URL', 'https://paybyloop.app/download')
        ],
    ]
];