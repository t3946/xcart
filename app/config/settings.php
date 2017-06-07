<?php
$local_config = __DIR__ . DIRECTORY_SEPARATOR .'settings_local.php';

return array_replace_recursive([
   'name' => 'Xcart',
   'exit_on_end' => false,
   'paths' => [
       'base' => realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..'])),
       'www' => realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..'])),
   ],
   'modules' => include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules.php',
   'locale' => [
       'language' => 'ru',
       'sourceLanguage' => 'en',
       'charset' => 'utf-8',
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
//       'errorHandler' => [
//           'class' => '\\Xcart\\App\\Main\\ErrorHandler',
//           'debug' => true,
//           'errHandler' => false
//       ],
       'event' => [
           'class' => '\\Xcart\\App\\Event\\EventManager',
           'events' => include dirname(__FILE__) . DIRECTORY_SEPARATOR .  'events.php'
       ],

       'logger' => include __DIR__. DIRECTORY_SEPARATOR . 'logger.php',

       'middleware' => [
           'class' => '\\Xcart\\App\\Middleware\\MiddlewareManager',
           'middleware' => [
//               'RedirectMiddleware' => [
//                   'class' => '\Modules\Redirect\Middleware\RedirectMiddleware'
//               ],
               'CurrentSiteMiddleware' => [
                   'class' => '\\Modules\\Sites\\Middleware\\CurrentSiteMiddleware'
               ],
                'BotsMiddleware' => [
                   'class' => '\\Modules\\User\\Middleware\\BotsMiddleware'
               ],
           ]
       ],
       'request' => [
           'class' => '\\Xcart\\App\\Request\\RequestManager',
           'httpRequest' => [
               'class' => '\\Xcart\\App\\Request\\HttpRequest',
               'session' => [
                   'class' => '\\Modules\\User\\Components\\XcartSession'
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
           'forceCompile' => false,
           'autoReload' => false
       ],

       'storage' => [
           'class' => '\\Xcart\\App\\Storage\\Storage',
           'default' => 'local',
           'adapters' => [
               'local' => [
                   'class' => '\\Xcart\\App\\Storage\\Adapters\\LocalAdapter',
                   'root' => 'www.media',
               ],
               'www' => [
                   'class' => '\\Xcart\\App\\Storage\\Adapters\\LocalAdapter',
                   'root' => 'www',
               ]
           ],
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
//       'global_config' => [
//           'class' => '\\Modules\\Core\\Components\\GlobalConfig'
//       ],
   ],
   'autoloadComponents' => [
       'db',
       'logger'
//       'errorHandler'
   ]
],  (is_file($local_config)) ? include $local_config : []);