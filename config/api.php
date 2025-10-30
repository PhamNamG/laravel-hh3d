<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for external API endpoints used by the application
    |
    */

    'base_url' => env('API_URL', 'http://localhost:8001/api'),

    'timeout' => env('API_TIMEOUT', 10),

    'cache_ttl' => env('API_CACHE_TTL', 300), // 5 minutes

    'endpoints' => [
        'categories' => '/category/latest/next',
        'category_detail' => '/category',
        'category_filters' => '/category/filters',
        'products' => '/products',
    ],

];

