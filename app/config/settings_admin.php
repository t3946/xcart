<?php
$base_config = __DIR__ . DIRECTORY_SEPARATOR .'settings.php';

return array_replace_recursive((is_file($base_config)) ? include $base_config : [], [
    'exit_on_end' => true,
    'modules' => [
        'Core',
        'Dashboard',
        'User',
        'Sites',
        'Amazon',
    ],
    'components' => [
        'errorHandler' => [
            'class' => '\\Xcart\\App\\Main\\ErrorHandler',
            'debug' => true,
            'errHandler' => false
        ],
        'request' => [
            'class' => '\\Xcart\\App\\Request\\RequestManager',
            'httpRequest' => [
                'class' => '\\Xcart\\App\\Request\\HttpRequest',
                'from_get' => 'path',
                'session' => [
                    'class' => '\\Xcart\\App\\Request\\XcartSession'
                ]
            ],
        ],
        'router' => [
            'class' => '\\Xcart\\App\\Router\\Router',
            'pathRoutes' => 'base.config.routes_admin',
            'basePath' => '/admin/controllers.php?',
            'mode' => 'get',
        ],
    ]
]);