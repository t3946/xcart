<?php
return [
    'class' => '\\Xcart\\App\\Logger\\LoggerManager',
    'defaultPatch' => 'root.log',
    'handlers' => [
        'default' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler' ,
            'level' => 'DEBUG',
            'alias' => 'root.log.log',
            'formatter' => 'log'
        ],
        'error' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler' ,
            'level' => 'DEBUG',
            'alias' => 'root.log.error',
            'formatter' => 'log'
        ],
        'sql' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler',
            'level' =>  'DEBUG',
            'alias' => 'root.log.sql'
        ],
        'null' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\NullHandler',
            'level' => 'ERROR'
        ],
        'console' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\StreamHandler',
            'formatter' => 'console'
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
        'log' => [
            'class' => '\\Xcart\\App\\Logger\\Formatters\\LineFormatter',
            'includeStacktrace' => true
        ],
        'console' => [
            'class' => '\\Monolog\\Formatter\\LineFormatter',
        ]
    ],
    'loggers' => [
        'sql' => [
            'class' => '\\Xcart\\App\\Logger\\Logger',
            'handlers' => ['sql']
        ],
        'info' => [
            'class' => '\\Xcart\\App\\Logger\\Logger',
            'handlers' => ['default']
        ],
//        'error' => [
//            'class' => '\\Xcart\\App\\Logger\\Logger',
//            'handlers' => ['error', 'mail_admins']
//        ],
    ]
];