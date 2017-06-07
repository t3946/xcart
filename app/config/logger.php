<?php
return [
    'class' => '\\Xcart\\App\\Logger\\LoggerManager',
    'handlers' => [
        'default' => [
//            'class' => defined('APP_DEBUG') ? '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler' : '\\Xcart\\App\\Logger\\Handler\\NullHandler',
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler' ,
            'level' => defined('APP_DEBUG') ? "DEBUG" : "ERROR"
        ],
        'sql' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler',
            'level' =>  "ERROR",
            'alias' => 'base.log.sql'
        ],
        'err' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler',
            'level' =>  "ERROR",
            'alias' => 'base.log.err'
        ],
        'null' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\NullHandler',
            'level' => 'ERROR'
        ],
        'console' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\StreamHandler',
            'formatter' => 'console'
        ],
        'users' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler',
            'alias' => 'base.logs.users',
            'level' => 'INFO',
            'formatter' => 'users'
        ],
//        'mail_admins' => [
//            'class' => '\\Xcart\\App\\Logger\\Handler\\SwiftMailerHandler',
//        ],
    ],
    'formatters' => [
        'users' => [
            'class' => '\\Xcart\\App\\Logger\\Formatters\\LineFormatter',
            'format' => "%datetime% %message%\\n"
        ],
        'console' => [
            'class' => '\\Monolog\\Formatter\\LineFormatter',
        ]
    ],
    'loggers' => [
        'users' => [
            'class' => '\\Monolog\\Logger',
            'handlers' => ['users'],
        ],
        'sql' => [
            'class' => '\\Xcart\\App\\Logger\\Logger',
            'handlers' => ['sql']
        ],
        'err' => [
            'class' => '\\Xcart\\App\\Logger\\Logger',
            'handlers' => ['console']
        ],
    ]
];