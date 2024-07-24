<?php
return [
    'core'             => '9.0',
    'core-sub-version' => '9.0.3',
    'homepage'         => 'https://vncore.org',
    'name'             => 'S-Cart',
    'github'           => 'https://github.com/vncore/vncore',
    'facebook'         => 'https://www.facebook.com/SCart.Ecommerce',
    'auth'             => 'Lanh Le',
    'email'            => 'lanhktc@gmail.com',
    'api_link'         => env('VNCORE_API_LINK', 'https://api.vncore.org/v1'),
    'ecommerce_mode'   => env('VNCORE_ECOMMERCE_MODE', 1),
    'search_mode'      => env('VNCORE_SEARCH_MODE', 'PRODUCT'), //PRODUCT,NEWS,CMS
    'VNCORE_ACTIVE'    => env('VNCORE_ACTIVE', 0), // 1: active, 0: deactive - prevent load vencore package
    'VNCORE_DB_PREFIX' => env('VNCORE_DB_PREFIX', 'vncore_'), //Cannot change after install vncore
    'VNCORE_DB_CONNECTION' => env('VNCORE_DB_CONNECTION', env('DB_CONNECTION', 'mysql')), 
    'VNCORE_ADMIN_PREFIX' => env('VNCORE_ADMIN_PREFIX', 'vncore_admin'), //Prefix url admin, ex: domain.com/vncore_admin
];
