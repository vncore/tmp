<?php
return [
    'admin_password' => [
        'provider' => 'admin_provider',
        'table'    => env('VNCORE_DB_PREFIX', '').'admin_password_resets',
        'expire'   => 60,
    ],
];
