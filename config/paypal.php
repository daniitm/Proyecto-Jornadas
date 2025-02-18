<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
    ],
    'live' => [
        'client_id'     => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
    ],
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'DEBUG'
    ]
];
