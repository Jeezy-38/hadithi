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

    /*
    | Social login (Laravel Socialite). Callback URL ya kusajili kwa kila
    | provider: {APP_URL}/auth/{provider}/callback
    */
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI') ?: rtrim((string) env('APP_URL'), '/').'/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI') ?: rtrim((string) env('APP_URL'), '/').'/auth/facebook/callback',
    ],

    'x' => [
        'client_id' => env('X_CLIENT_ID'),
        'client_secret' => env('X_CLIENT_SECRET'),
        'redirect' => env('X_REDIRECT_URI') ?: rtrim((string) env('APP_URL'), '/').'/auth/x/callback',
    ],

    /*
    | Lango Lililochaguliwa la Malipo ('clickpesa', 'pesapal', 'simulation', 'auto')
    */
    'payment_gateway' => env('PAYMENT_GATEWAY', 'clickpesa'),

    /*
    | Lango la Malipo la Pesapal v3 (Tanzania & Kimataifa: M-Pesa, Tigo, Airtel, Halo, Visa/Mastercard)
    */
    'pesapal' => [
        'mode' => env('PESAPAL_MODE', 'simulation'), // 'live', 'sandbox', 'simulation'
        'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
        'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
        'ipn_id' => env('PESAPAL_IPN_ID'),
    ],

    /*
    | Lango la Malipo la ClickPesa (Tanzania: M-Pesa, Tigo Pesa, Airtel Money, HaloPesa, Visa/Mastercard)
    */
    'clickpesa' => [
        'client_id' => env('CLICKPESA_CLIENT_ID'),
        'api_key' => env('CLICKPESA_API_KEY'),
        'checksum_key' => env('CLICKPESA_CHECKSUM_KEY'),
        'mode' => env('CLICKPESA_MODE', 'live'), // 'live' au 'sandbox'
    ],

];
