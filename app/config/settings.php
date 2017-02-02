<?php

return [
    'name' => 'Xcart',
    'paths' => [
        'base' => realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..'])),
        'www' => realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..'])),
    ],
    'modules' => [
        'Core',
        'Dashboard',
    ],
    'components' => [
        'db' => [
            'class' => '\\Xcart\\Connection',
        ],
        'errorHandler' => [
            'class' => '\\Xcart\\App\\Main\\ErrorHandler',
            'debug' => true
        ],
        'request' => [
            'class' => '\\Xcart\\App\\Request\\RequestManager',
            'httpRequest' => [
                'class' => '\\Xcart\\App\\Request\\HttpRequest',
//                'session' => [
//                    'class' => '\\Xcart\\App\\Request\\Session'
//                ]
            ],
            'cliRequest' => [
                'class' => '\\Xcart\\App\\Request\\CliRequest',
            ]
        ],
        'router' => [
            'class' => '\\Xcart\\App\\Router\\Router',
            'pathRoutes' => 'base.config.routes',
        ],
        'template' => [
            'class' => '\\Xcart\\App\\Template\\TemplateManager',
            'forceCompile' => true
        ],
    ],
    'autoloadComponents' => [
        'errorHandler'
    ]
];