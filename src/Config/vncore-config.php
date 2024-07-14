<?php

return [
    'admin'                   => [
        'theme'               => ['lightblue', 'dark', 'blue', 'white', 'pink'],
        'theme_default'       => 'lightblue',
        'theme_define'        => [
            'lightblue'       => [
                'body'        => 'accent-lightblue',
                'main-header' => 'navbar-dark navbar-lightblue',
                'sidebar'     => 'sidebar-lightblue',
            ],
            'dark'            => [
                'body'        => 'accent-navy',
                'main-header' => 'navbar-dark navbar-gray-dark',
                'sidebar'     => 'sidebar-gray-dark',
            ],
            'blue'            => [
                'body'        => 'accent-success',
                'main-header' => 'navbar-dark navbar-success',
                'sidebar'     => 'sidebar-success',
            ],
            'white'           => [
                'body'        => 'accent-lightblue',
                'main-header' => 'navbar-light navbar-white',
                'sidebar'     => 'sidebar-white',
            ],
            'pink'            => [
                'body'        => 'accent-fuchsia',
                'main-header' => 'navbar-dark navbar-pink',
                'sidebar'     => 'sidebar-pink',
            ],
        ],
        //Enable, disable page libary online
        'settings'            => [
            'api_plugin'      => env('VNCORE_ADMIN_API_PLUGIN', 1),
            'api_template'    => env('VNCORE_ADMIN_API_TEMPLATE', 1),
        ],
        //Prefix path view admin
        'path_view'           => 'vncore-admin::',
    
        //Config global
        'admin_log'           => env('VNCORE_ADMIN_LOG', 1), //Log access admin
    ],
    'api' => [
        'auth' => [
            'api_remmember' => env('VNCORE_API_RECOMMEMBER', 30), //days - expires_at
            'api_token_expire_default' => env('VNCORE_API_TOKEN_EXPIRE_DEFAULT', 7), //days - expires_at default
            'api_remmember_admin' => env('VNCORE_API_RECOMMEMBER_ADMIN', 30), //days - expires_at
            'api_token_expire_admin_default' => env('VNCORE_API_TOKEN_EXPIRE_ADMIN_DEFAULT', 7), //days - expires_at default
            'api_scope_type' => env('VNCORE_API_SCOPE_TYPE', 'ability'), //ability|abilities
            'api_scope_type_admin' => env('VNCORE_API_SCOPE_TYPE_ADMIN', 'ability'), //ability|abilities
            'api_scope_user' => env('VNCORE_API_SCOPE_USER', 'user'), //string, separated by commas
            'api_scope_user_guest' => env('VNCORE_API_SCOPE_USER_GUEST', 'user-guest'), //string, separated by commas
            'api_scope_admin' => env('VNCORE_API_SCOPE_ADMIN', 'admin-supper'),//string, separated by commas
        ],
    ],
    'middleware'              => [
        'admin'               => [
            1 => 'admin.auth',
            2 => 'admin.permission',
            3 => 'admin.log',
            4 => 'admin.storeId',
            5 => 'admin.theme',
            6 => 'localization',
        ],
        'front'               => [
            1 => 'localization',
            2 => 'currency',
            3 => 'checkdomain',
        ],
        'api_extend'          => [
            1 => 'json.response',
            2 => 'api.connection',
            3 => 'throttle:1000',
        ],
    ],
];
