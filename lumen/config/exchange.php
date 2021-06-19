<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    */

    'rate' => [
        'domain' => env('EXCHANGE_RATE_DOMAIN', 'http://api.exchangeratesapi.io/v1/latest'),
        'access_key' => env('EXCHANGE_RATE_ACCESS_KEY', '7dbb10ea6432c296192973df2cdcc96a'),
        'allowed_currency' => env('EXCHANGE_RATE_CURRENCY', ['USD', 'GBP', 'RUB']),
    ],

];
