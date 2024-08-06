<?php
return [
    'core'             => '1.0',
    'core-sub-version' => '1.0.0',
    'homepage'         => 'https://vncore.net',
    'name'             => 'Vncore',
    'github'           => 'https://github.com/vncore/core',
    'facebook'         => 'https://www.facebook.com/Vncore',
    'auth'             => 'Vncore Team',
    'email'            => 'vncore.net@gmail.com',
    'api_link'         => env('VNCORE_API_LINK', 'https://api.vncore.net/v1'),
    'VNCORE_ACTIVE'    => env('VNCORE_ACTIVE', 0), // 1: active, 0: deactive - prevent load vencore package
    'VNCORE_DB_PREFIX' => env('VNCORE_DB_PREFIX', 'vncore_'), //Cannot change after install vncore
    'VNCORE_DB_CONNECTION' => env('VNCORE_DB_CONNECTION', env('DB_CONNECTION', 'mysql')), 
    'VNCORE_ADMIN_PREFIX' => env('VNCORE_ADMIN_PREFIX', 'vncore_admin'), //Prefix url admin, ex: domain.com/vncore_admin
];
