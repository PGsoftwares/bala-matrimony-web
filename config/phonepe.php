<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mode (sandbox / production)
    |--------------------------------------------------------------------------
    */
    'mode' => env('PHONEPE_MODE', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'sandbox' => [
        'base_url'       => 'https://api-preprod.phonepe.com',
        'authorization'  => '/apis/pg-sandbox',
        'payment'        => '/apis/pg-sandbox',
        'order_status'   => '/apis/pg-sandbox',
    ],

    'production' => [
        'base_url'       => 'https://api.phonepe.com',
        'authorization'  => '/apis/identity-manager',
        'payment'        => '/apis/pg',
        'order_status'   => '/apis/pg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    */
    'client_id'     => env('PHONEPE_CLIENT_ID'),
    'client_secret' => env('PHONEPE_CLIENT_SECRET'),
    'client_version'=> env('PHONEPE_CLIENT_VERSION'),

];
