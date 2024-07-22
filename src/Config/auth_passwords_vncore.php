<?php
return [
    'admins' => [
        'provider' => 'admins',
        'table'    => env('VNCORE_DB_PREFIX', '').'admin_password_resets',
        'expire'   => 60,
    ],
];
