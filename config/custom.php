<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Password
    |--------------------------------------------------------------------------
    |
    | Default password for the accounts.
    |
    */

    'default_password' => env('DEFAULT_PASSWORD', null),

    /*
    |--------------------------------------------------------------------------
    | Default Pagination
    |--------------------------------------------------------------------------
    |
    | Default number of items per page.
    |
    */

    'default_pagination' => env('DEFAULT_PAGINATION', 10),

    /*
    |--------------------------------------------------------------------------
    | Default order receiver mail and name
    |--------------------------------------------------------------------------
    |
    | Default receiver of orders.
    |
    */

    'default_order_receiver_mail' => env('DEFAULT_ORDER_RECEIVER_MAIL'),
    'default_order_receiver_name' => env('DEFAULT_ORDER_RECEIVER_NAME'),
];
