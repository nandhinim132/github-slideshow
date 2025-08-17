<?php

return [
    'app_name' => 'SuperMarket POS',
    'timezone' => 'Asia/Kolkata',

    // Currency configuration
    'currency_code' => 'INR',
    'currency_symbol' => '₹',

    // Database configuration (SQLite)
    'db_path' => __DIR__ . '/../data/database.sqlite',

    // Security
    'session_name' => 'supermarket_session',
    'csrf_key' => 'csrf_token',
];