<?php

defined('APP_DEBUG')?:define('APP_DEBUG', true);
$base_config = __DIR__ . DIRECTORY_SEPARATOR .'settings.php';
$local_config = __DIR__ . DIRECTORY_SEPARATOR .'settings_console_local.php';

$config = array_replace_recursive([
    'exit_on_end' => true,
    'components' => [
        'errorHandler' => [
            'debug' => true,
        ],
        'db' => [
            'connections' => [
                'default' => [
                    'wrapperClass' => false,
                ]
            ]
        ],
    ],
    'autoloadComponents' => [
        'errorHandler'
    ]
], (is_file($local_config)) ? include $local_config : []);

return array_replace_recursive((is_file($base_config)) ? include $base_config : [], $config);