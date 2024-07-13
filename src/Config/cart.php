<?php

return [
    'expire' => [
        'cart' => env('VNCORE_CART_EXPIRE_CART', 7), //days
        'wishlist' => env('VNCORE_CART_EXPIRE_WISHLIST', 30), //days
        'compare' => env('VNCORE_CART_EXPIRE_COMPARE', 30), //days
        'lastview' => env('VNCORE_CART_EXPIRE_PRODUCT_LASTVIEW', 30), //days
    ],
    'process' => [
        'other_fee' => [
            'value' => env('VNCORE_PROCESS_OTHER_FEE', 0),
            'title' => env('VNCORE_PROCESS_OTHER_TITLE', 'Other fee'),
        ],
    ],
];
