<?php

return [

    'default' => 'log',

    'gateways' => [

        'khan' => [
            'username'  => env('KHAN_USERNAME'),
            'password'  => env('KHAN_PASSWORD'),
            'returnUrl' => url('payways/khan')
        ]

    ]

];