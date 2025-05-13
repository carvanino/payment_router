<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Router Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring the payment router package.
    |
    */

    // Default routing strategy
    'default_strategy' => 'best_price', // Options: 'best_price', 'highest_reliability', 'balanced'

    // Logging configuration
    'logging' => [
        'enabled' => true,
        'channel' => env('PAYMENT_ROUTER_LOG_CHANNEL', 'stack'),
    ],

    // Processors configuration
    'processors' => [
        // Example processor configurations
        // 'stripe' => [
        //     'name' => 'Stripe',
        //     'class' => \Akinola\PaymentRouter\Adapters\StripeAdapter::class,
        //     'config' => [
        //         'api_key' => env('STRIPE_API_KEY'),
        //     ],
        //     'active' => true,
        //     'transaction_fee_percentage' => 2.9,
        //     'transaction_fee_fixed' => 0.30,
        //     'reliability_score' => 95,
        //     'supported_currencies' => ['USD', 'EUR', 'GBP', 'NGN'],
        //     'supported_countries' => ['US', 'UK', 'NG', '*'],
        // ],
    ],
];