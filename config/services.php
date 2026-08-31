<?php

return [
    'bkash' => [
        'sandbox_url' => env('BKASH_SANDBOX_URL', 'https://sandbox.bkash.com'),
        'production_url' => env('BKASH_PRODUCTION_URL', 'https://api.bkash.com'),
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
        'callback_url' => env('BKASH_CALLBACK_URL'),
    ],

    'mailgun' => [
        'secret' => env('MAILGUN_SECRET'),
    ],
];
