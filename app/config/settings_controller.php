<?php
$base_config = __DIR__ . DIRECTORY_SEPARATOR .'settings.php';
$local_config = __DIR__ . DIRECTORY_SEPARATOR .'settings_controller_local.php';

$config = array_replace_recursive((is_file($local_config)) ? include $local_config : [], [
    'exit_on_end' => true,
    'components' => [
      'errorHandler' => [
          'class' => '\\Xcart\\App\\Main\\ErrorHandler',
          'debug' => false,
//          'errHandler' => true,
//          'excHandler' => false,
          'useTemplate' => true,
          'ignoreDeprecated' => true,
      ],
    ],
    'autoloadComponents' => [
      'errorHandler'
    ]
]);

return array_replace_recursive((is_file($base_config)) ? include $base_config : [], $config);