<?php
$base_config = __DIR__ . DIRECTORY_SEPARATOR .'settings.php';
$local_config = __DIR__ . DIRECTORY_SEPARATOR .'settings_admin_local.php';



$config = array_replace_recursive([
    'exit_on_end' => true,
    'components' => [
      'errorHandler' => [
          'class' => '\\Xcart\\App\\Main\\ErrorHandler',
          'debug' => true,
          'errHandler' => false
      ],
      'router' => [
//          'pathRoutes' => 'base.config.routes_admin',
      ],
    ],
    'autoloadComponents' => [
      'errorHandler'
    ]
], (is_file($local_config)) ? include $local_config : []);

return array_replace_recursive((is_file($base_config)) ? include $base_config : [], $config);