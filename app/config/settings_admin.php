<?php
$base_config = __DIR__ . DIRECTORY_SEPARATOR .'settings.php';

return array_replace_recursive((is_file($base_config)) ? include $base_config : [], [
    'exit_on_end' => true,
    'components' => [
        'errorHandler' => [
            'class' => '\\Xcart\\App\\Main\\ErrorHandler',
            'debug' => true,
            'errHandler' => false
        ],
        'router' => [
            'pathRoutes' => 'base.config.routes_admin',
        ],
    ]
]);