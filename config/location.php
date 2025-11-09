<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Location Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "ip-api", "ipinfo", "ipdata", "ipstack", "maxmind_database", "maxmind_web"
    |
    */

    'driver' => env('LOCATION_DRIVER', 'ip-api'),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    */

    'drivers' => [

        'ip-api' => [
            'class' => \Stevebauman\Location\Drivers\IpApi::class,
            'key' => null,
        ],

        'ipinfo' => [
            'class' => \Stevebauman\Location\Drivers\IpInfo::class,
            'key' => env('IPINFO_TOKEN'),
        ],

        'ipstack' => [
            'class' => \Stevebauman\Location\Drivers\IpStack::class,
            'key' => env('IPSTACK_KEY'),
        ],

        'maxmind_database' => [
            'class' => \Stevebauman\Location\Drivers\MaxMindDatabase::class,
            'database_path' => storage_path('app/GeoLite2-City.mmdb'),
        ],

        'maxmind_web' => [
            'class' => \Stevebauman\Location\Drivers\MaxMindWeb::class,
            'user_id' => env('MAXMIND_USER_ID'),
            'license_key' => env('MAXMIND_LICENSE_KEY'),
        ],

    ],

];
