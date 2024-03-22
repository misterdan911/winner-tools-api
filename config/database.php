<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'strict' => env('DB_STRICT_MODE', true),
            'engine' => env('DB_ENGINE', null),
            'timezone' => env('DB_TIMEZONE', '+00:00'),
        ],

        'coregames' => [
            'driver' => 'mysql',
            'host' => env('DB_COREGAMES_HOST', '127.0.0.1'),
            'port' => env('DB_COREGAMES_PORT', 3306),
            'database' => env('DB_COREGAMES_DATABASE', 'forge'),
            'username' => env('DB_COREGAMES_USERNAME', 'forge'),
            'password' => env('DB_COREGAMES_PASSWORD', ''),
            'unix_socket' => env('DB_COREGAMES_SOCKET', ''),
            'charset' => env('DB_COREGAMES_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COREGAMES_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_COREGAMES_PREFIX', ''),
            'strict' => env('DB_COREGAMES_STRICT_MODE', true),
            'engine' => env('DB_COREGAMES_ENGINE', null),
            'timezone' => env('DB_COREGAMES_TIMEZONE', '+00:00'),
        ],

        'mvicall' => [
            'driver' => 'mysql',
            'host' => env('DB_MVICALL_HOST', '127.0.0.1'),
            'port' => env('DB_MVICALL_PORT', 3306),
            'database' => env('DB_MVICALL_DATABASE', 'forge'),
            'username' => env('DB_MVICALL_USERNAME', 'forge'),
            'password' => env('DB_MVICALL_PASSWORD', ''),
            'unix_socket' => env('DB_MVICALL_SOCKET', ''),
            'charset' => env('DB_MVICALL_CHARSET', 'utf8mb4'),
            'collation' => env('DB_MVICALL_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_MVICALL_PREFIX', ''),
            'strict' => env('DB_MVICALL_STRICT_MODE', true),
            'engine' => env('DB_MVICALL_ENGINE', null),
            'timezone' => env('DB_MVICALL_TIMEZONE', '+00:00'),
        ],

        'msg_core' => [
            'driver' => 'mysql',
            'host' => env('DB_MSG_CORE_HOST', '127.0.0.1'),
            'port' => env('DB_MSG_CORE_PORT', 3306),
            'database' => env('DB_MSG_CORE_DATABASE', 'forge'),
            'username' => env('DB_MSG_CORE_USERNAME', 'forge'),
            'password' => env('DB_MSG_CORE_PASSWORD', ''),
            'unix_socket' => env('DB_MSG_CORE_SOCKET', ''),
            'charset' => env('DB_MSG_CORE_CHARSET', 'utf8mb4'),
            'collation' => env('DB_MSG_CORE_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_MSG_CORE_PREFIX', ''),
            'strict' => env('DB_MSG_CORE_STRICT_MODE', true),
            'engine' => env('DB_MSG_CORE_ENGINE', null),
            'timezone' => env('DB_MSG_CORE_TIMEZONE', '+00:00'),
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'lumen'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
