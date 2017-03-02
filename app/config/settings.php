<?php
$local_config = __DIR__ . DIRECTORY_SEPARATOR .'settings_local.php';

return array_replace_recursive([
   'name' => 'Xcart',
   'exit_on_end' => false,
   'paths' => [
       'base' => realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..'])),
       'www' => realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..'])),
   ],
   'modules' => [
       'Core',
       'Dashboard',
       'User',
       'Sites',
       'Amazon',
   ],
   'components' => [
       'db' => [
           'class' => '\\Xcart\\App\\Orm\\ConnectionManager',
           'connections' => [
               'default' => [
                   'memory' => true,
                   'driver' => 'pdo_mysql',
                   'dbname' => 'xcart_k',
                   'host' => '127.0.0.1',
                   'user' => 'xcart_k',
                   'password' => 'i250923lst',
                   'charset'  => 'utf8',
//                   'prefix' => 'xcart_',
                   'mapping' => [
                       'enum' => 'string'
                   ]
               ]
           ]
       ],
       'errorHandler' => [
           'class' => '\\Xcart\\App\\Main\\ErrorHandler',
           'debug' => true,
           'errHandler' => false
       ],
//       'middleware' => [
//           'class' => '\Mindy\Middleware\MiddlewareManager',
//           'middleware' => [
//               'RedirectMiddleware' => [
//                   'class' => '\Modules\Redirect\Middleware\RedirectMiddleware'
//               ],
//           ]
//       ],
       'request' => [
           'class' => '\\Xcart\\App\\Request\\RequestManager',
           'httpRequest' => [
               'class' => '\\Xcart\\App\\Request\\HttpRequest',
               'session' => [
                   'class' => '\\Xcart\\App\\Request\\XcartSession'
               ]
           ],
           'cliRequest' => [
               'class' => '\\Xcart\\App\\Request\\CliRequest',
           ]
       ],
       'router' => [
           'class' => '\\Xcart\\App\\Router\\Router',
           'pathRoutes' => 'base.config.routes'
       ],
       'template' => [
           'class' => '\\Xcart\\App\\Template\\TemplateManager',
           'forceCompile' => true,
//           'autoReload' => false
       ],
       'cache' => [
           'class' => '\\Xcart\\App\\Cache\\Cache',
           'saveInMemory' => true,
           'memoryDriver' => 'memory',
           'drivers' => [
               'default' =>  [
                   'class' => '\\Xcart\\App\\Cache\\Drivers\\File'
               ],
               'memory' =>  [
                   'class' => '\\Xcart\\App\\Cache\\Drivers\\Memory',
                   'numCacheQuery' => 30,
               ]
           ]
       ],
       'mail' => [
           'class' => '\\Modules\\Mail\\Components\\MailComponent'
       ],
       'auth' => [
           'class' => '\\Modules\\User\\Components\\Auth'
       ],
   ],
   'autoloadComponents' => [
       'errorHandler'
   ]
],  (is_file($local_config)) ? include $local_config : []);