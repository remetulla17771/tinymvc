<?php


return [
    'appName' => 'TinyMVC',
    'components' => [
        'user' => [
            'class' => '\app\AuthService'
        ],
        'urlManager' => [
            'class' => '\app\UrlManager'
        ],
        'response' => [
            'class' => '\app\Response'
        ],
        'arrayHelper' => [
            'class' => '\app\helpers\ArrayHelper'
        ],
        'language' => [
            'class' => '\app\helpers\I18n'
        ],
        'session' => [
            'class' => '\app\helpers\Session'
        ],
        'stringer' => [
            'class' => '\app\Request'
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'modules\admin\Module',
            'layout' => 'admin',
        ]
    ],
    'alias' => [
        '@web' => '/',
        '@uploads' => '/uploads/'
    ]
];