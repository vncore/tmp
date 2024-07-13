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
    'api_link'         => env('VNCORE_API_LINK', 'https://api.vncore.org/v3'),
    'ecommerce_mode'   => env('VNCORE_ECOMMERCE_MODE', 1),
    'search_mode'      => env('VNCORE_SEARCH_MODE', 'PRODUCT'), //PRODUCT,NEWS,CMS
    'const' => [
        'VNCORE_DB_PREFIX'    => env('VNCORE_DB_PREFIX', ''),
        'VNCORE_ADMIN_PREFIX' => env('VNCORE_ADMIN_PREFIX', 'vncore_admin'),
        'PMO_PREFIX'          => env('PMO_PREFIX', 'vncore_pmo'),
        'MAIL_HOST'           => env('MAIL_HOST', ''),
        'MAIL_PORT'           => env('MAIL_PORT', ''),
        'MAIL_ENCRYPTION'     => env('MAIL_ENCRYPTION', ''),
        'MAIL_USERNAME'       => env('MAIL_USERNAME', ''),
        'MAIL_PASSWORD'       => env('MAIL_PASSWORD', ''),
    ],
];
