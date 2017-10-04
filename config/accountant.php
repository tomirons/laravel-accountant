<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination Limit
    |--------------------------------------------------------------------------
    |
    | This value is the the amount of results to display on each page.
    */

    'pagination' => [
        'limit' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | This is the middleware used for all routes of the package. By default
    | it uses the 'auth' middleware, but it can be configured how ever
    | you'd like. You can use a string of middleware(s) or an array.
    |
    | Supported types: string, array
    */

    'middleware' => 'auth',

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Here you can change the settings that are used when utilizing the cache
    | system.
    */

    'cache' => [
        'driver' => 'file',
        'minutes' => 60
    ]
];
