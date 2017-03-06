<?php

return [

    'default' => 'log',

    'gateways' => [

        'khan' => [
            'username'  => env('KHAN_USERNAME'),
            'password'  => env('KHAN_PASSWORD'),
            'returnUrl' => 'payways/khan',
            'currency'  => env('KHAN_CURRENCY', 'mnt'),
        ],

        'golomt' => [
            'key_number'    => env('GOLOMT_KEY_NUMBER'),
            'sub_id'        => env('GOLOMT_SUB_ID', 1),
            'soap_username' => env('GOLOMT_SOAP_USERNAME'),
            'soap_password' => env('GOLOMT_SOAP_PASSWORD'),
            'currency'      => env('GOLOMT_CURRENCY', 'mnt'),
        ],

        'state' => [
            'server'     => env('STATE_SERVER', 'https://202.131.225.149:2233/Exec'),
            'merchantId' => env('STATE_MERCHANT'),
            'username'   => env('STATE_USERNAME'),
            'password'   => env('STATE_PASSWORD'),
            'currency'   => env('STATE_CURRENCY', 'mnt'),
            'returnUrl'  => 'payways/state',
        ],

    ],

    'user' => [
        'table' => 'users',
        'model' => 'App\User',
    ],

];
