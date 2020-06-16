<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    //'default' =>env('DB_CONNECTION2', 'mysql'),
    // 'default' =>env('DB_CONNECTION', 'sqlsrv'),
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],
        'mysql2' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST2'),
            'port' => env('DB_PORT2'),
            'database' => env('DB_DATABASE2'),
            'username' => env('DB_USERNAME2'),
            'password' => env('DB_PASSWORD2'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],
    ],
];
