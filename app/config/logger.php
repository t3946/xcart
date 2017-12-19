<?php
return [
    'class' => '\\Xcart\\App\\Logger\\LoggerManager',
    'defaultPatch' => 'base.log',
    'handlers' => [
        'default' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler' ,
            'level' => 'DEBUG',
            'alias' => 'base.log.log',
            'formatter' => 'log'
        ],
        'error' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler' ,
            'level' => 'DEBUG',
            'alias' => 'base.log.error',
            'formatter' => 'log'
        ],
        'sql' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler',
            'level' =>  'DEBUG',
            'alias' => 'base.log.sql'
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
            'allowInlineLineBreaks' => true,
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