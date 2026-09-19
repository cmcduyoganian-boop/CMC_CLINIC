<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default SMS provider that will be used
    | by the application. Supported: "log", "semaphore", "android"
    |
    */
    'provider' => env('SMS_PROVIDER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | SMS Providers Configuration
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'log' => [
            'channel' => 'sms',
        ],

        'semaphore' => [
            'api_key' => env('SEMAPHORE_API_KEY'),
            'sender_name' => env('SEMAPHORE_SENDER_NAME'),
        ],

        'android' => [
            'url' => env('ANDROID_SMS_URL'),
            'token' => env('ANDROID_SMS_TOKEN'),
        ],
    ],
];