<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Payment Drivers
    |--------------------------------------------------------------------------
    |
    | Each driver has its credentials and specific settings.
    | Add or modify drivers as needed.
    |
    */

    'drivers' => [

        'stripe' => [
            'public_key' => env('STRIPE_KEY'),
            'secret_key' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => 'usd',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Plans Configuration
    |--------------------------------------------------------------------------
    |
    | Define all subscription plans offered on the platform.
    | You can reference these in controllers or billing logic.
    |
    */

];
