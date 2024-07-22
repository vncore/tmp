<?php
return [
    'admin' => [
        'driver'   => 'session',
        'provider' => 'admin',
    ],
    'api' => [
        'driver'   => 'sanctum',
        'provider' => 'users',
    ],
    'admin-api' => [
        'driver'   => 'sanctum',
        'provider' => 'admins',
    ],
];
