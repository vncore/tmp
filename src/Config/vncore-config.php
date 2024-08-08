<?php
define('VNCORE_ADMIN_PATH_VIEW', 'vncore-admin');
define('VNCORE_FRONT_PATH_VIEW', 'vncore-front');

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
        'path_view'           => VNCORE_ADMIN_PATH_VIEW,
    
        //Config global
        'admin_log'           => env('VNCORE_ADMIN_LOG', 1), //Log access admin

        //Config for header
        //Path view for header
        'module_header_left' => [
            VNCORE_ADMIN_PATH_VIEW.'::component.language',
            VNCORE_ADMIN_PATH_VIEW.'::component.admin_theme',
            ]
        ],
        
        'module_header_right' => [
            VNCORE_ADMIN_PATH_VIEW.'::component.notice',
            VNCORE_ADMIN_PATH_VIEW.'::component.admin_profile',
        ],
        
    // Config for front
    'front' => [
        'path_view'           => VNCORE_FRONT_PATH_VIEW,
    ],

    //Config for api
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

    //Config for middleware
    'middleware'     => [
            'admin'      => [
                1        => 'admin.auth',
                2        => 'admin.permission',
                3        => 'admin.log',
                4        => 'admin.storeId',
                5        => 'admin.theme',
                6        => 'localization',
            ],
            'api_extend' => [
                1        => 'json.response',
                2        => 'api.connection',
                3        => 'throttle: 1000',
            ],
    ],

    //Config for plugin
    'plugin' => [
        'plugin_protected' => [
            //
        ],
    ],


    //Config for route
    'route' => [
        //Prefix member, as domain.com/customer/login
        'VNCORE_PREFIX_MEMBER' => env('VNCORE_PREFIX_MEMBER', 'customer'), 

        //Prefix lange on url, as domain.com/en/abc.html
        //If value is empty, it will not be displayed, as dommain.com/abc.html
        'VNCORE_PREFIX_LANG' => env('VNCORE_PREFIX_LANG', '{lang?}/'),
    ],
];
