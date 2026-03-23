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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'izibiz' => [
        'auth_url' => env('IZIBIZ_AUTH_URL', 'https://api.izibiz.com.tr/v1/auth/token'),
        'username' => env('IZIBIZ_USERNAME'),
        'password' => env('IZIBIZ_PASSWORD'),
        'customers_url' => env('IZIBIZ_CUSTOMERS_URL', 'https://api.izibiz.com.tr/v1/customers'),
        'tariffs_url' => env('IZIBIZ_TARIFFS_URL', 'https://api.izibiz.com.tr/v1/tariffs'),
        'channel_id' => env('IZIBIZ_CHANNEL_ID'),
        'base_url' => env('IZIBIZ_BASE_URL', 'https://api.izibiz.com.tr'),
        'version' => env('IZIBIZ_API_VERSION', 'v1'),
    ],

];
