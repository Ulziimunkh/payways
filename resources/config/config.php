<?php

return [

    'default' => 'log',

    'gateways' => [

        'khan' => [
            'username' => env('KHAN_USERNAME'),
            'password' => env('KHAN_PASSWORD'),
            'returnUrl' => 'payways/khan',
            'currency' => env('KHAN_CURRENCY', ['mnt']),
        ],

        'golomt' => [
            'key_number' => env('GOLOMT_KEY_NUMBER'),
            'sub_id' => env('GOLOMT_SUB_ID', 1),
            'soap_username' => env('GOLOMT_SOAP_USERNAME'),
            'soap_password' => env('GOLOMT_SOAP_PASSWORD'),
            'currency'  => env('GOLOMT_CURRENCY', ['mnt'])
        ]

    ]

];
