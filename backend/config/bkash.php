<?php

return [
    'mode' => env('BKASH_MODE', 'sandbox'),
    'app_key' => env('BKASH_APP_KEY'),
    'app_secret' => env('BKASH_APP_SECRET'),
    'username' => env('BKASH_USERNAME'),
    'password' => env('BKASH_PASSWORD'),
    'sandbox_url' => 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/',
    'production_url' => 'https://checkout.bka.sh/v1.2.0-beta/checkout/payment/',
];
