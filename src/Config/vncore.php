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
];
