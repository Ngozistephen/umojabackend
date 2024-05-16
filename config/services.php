<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // 'redirect' => 'http://localhost:8000/api/auth/google/callback'
         'redirect' => config('app.frontend_url') .'/auth/google/callback',
        //  'vendor_redirect' => config('app.frontend_url') .'/auth/google/vendor/callback'
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect' => 'http://localhost:8000/api/auth/apple/callback'
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        // 'redirect' => 'http://localhost:8000/api/auth/facebook/callback'
         'redirect' =>  config('app.frontend_url') .'/auth/facebook/callback',
        //  'vendor_redirect' =>  config('app.frontend_url') .'/auth/facebook/vendor/callback'
    ],  

    'stripe' => [
        'publishable_key' => env('STRIPE_KEY'),
        'secret_key' => env('STRIPE_SECRET'),
        //  'redirect' =>  config('app.frontend_url') .'/auth/facebook/callback'
    ],  

];
