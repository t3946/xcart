<?php
(defined('DS')?:define('DS', DIRECTORY_SEPARATOR));

$local_config = __DIR__ . DS .'settings_local.php';
$local_config = (is_file($local_config)) ? include $local_config : [];

return array_replace_recursive([
   'name' => 'Xcart',
   'exit_on_end' => true,
   'paths' => [
       'base'   => realpath(implode(DS, [__DIR__, '..'])),
       'root'   => realpath(implode(DS, [__DIR__, '..', '..'])),
       'www'    => realpath(implode(DS, [__DIR__, '..', '..', 'www'])),
   ],
   'globals' => [
//       'blowfish_key' => '8d5db63ada15e11643a0b1c3477c2c5c',
//       'blowfish' => new \ctBlowfish(),
       'sql_tbl' => include __DIR__ . DS . "xcart_tables.php",
   ],
   'modules' => include __DIR__ . DS . 'modules.php',
   'locale' => [
       'language' => 'en',
       'sourceLanguage' => 'en',
       'charset' => 'utf-8',
   ],
   'components' => [
       'db' => [
           'class' => '\\Xcart\\App\\Orm\\ConnectionManager',
           'connections' => [
               'default' => [
                   'memory' => true,
                   'autoCommit' => true,
                   'driver' => 'pdo_mysql',
                   'dbname' => 'xcart_k',
                   'host' => '127.0.0.1',
                   'user' => 'xcart_k',
                   'password' => 'i250923lst',
                   'charset'  => 'utf8',
                   'mapping' => [
                       'enum' => 'string'
                   ],
                   'cache' => (defined('APP_DEBUG') && APP_DEBUG) ? [
                       'class' => '\\Xcart\\App\\Orm\\Cache\\FilesystemCache',
                       'directory' => 'base.runtime.query_cache'
                   ] : [ //PRODUCTION CACHE
                       'class' => '\Xcart\App\Orm\Cache\RedisCache',
                   ],
                   'driverOptions' => [
//                       PDO::ATTR_EMULATE_PREPARES => false,
                       PDO::ATTR_STRINGIFY_FETCHES => false,
//                       PDO::ATTR_PERSISTENT => true, //broken -> https://stackoverflow.com/questions/16217426/is-it-possible-to-use-doctrine-with-persistent-pdo-connections
                   ]
               ]
           ]
       ],

       'event' => [
           'class' => '\\Xcart\\App\\Event\\EventManager',
           'events' => include __DIR__ . DS .  'events.php'
       ],

       'breadcrumbs' => ['class' => 'Xcart\App\Components\Breadcrumbs'],
       'flash' => ['class' => '\Xcart\App\Components\Flash'],
       'finder' => ['class' => '\Xcart\App\Finder\FinderFactory'],

       'middleware' => [
           'class' => '\\Xcart\\App\\Middleware\\MiddlewareManager',
           'middleware' => include __DIR__ . DS . 'middleware.php',
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
           'forceInclude' => true,
           'autoReload' => false,
           'autoEscape' => false,
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

       'cache' => (defined('APP_DEBUG') && APP_DEBUG) ? [
           'class' => '\\Xcart\\App\\Cache\\Cache',
           'saveInMemory' => true,
           'memoryDriver' => 'memory',
           'drivers' => [
               'default' =>  [
                   'class' => '\\Xcart\\App\\Cache\\Drivers\\File',
               ],
               'memory' =>  [
                   'class' => '\\Xcart\\App\\Cache\\Drivers\\Memory',
                   'numCacheQuery' => 30,
               ],
               'html' =>  [
                   'class' => '\\Xcart\\App\\Cache\\Drivers\\File',
                   'extension' => '.html',
                   'path' => 'root.html_cache',
//                   'keySerialization' => false,
                   'directoryLevel' => 2
               ]
           ]
       ] : [ //PRODUCTION CACHE
           'class' => '\Xcart\App\Cache\Cache',
           'drivers' => [
               'default' =>  [
                   'class' => '\Xcart\App\Cache\Drivers\Redis',
               ],
               'html' =>  [
                   'class' => '\\Xcart\\App\\Cache\\Drivers\\File',
                   'extension' => '.html',
                   'path' => 'root.html_cache'
               ],
           ]
       ],

       'oldMail' => '\Modules\Mail\Components\MailComponent',
       'mail' => [
           'class' => '\Modules\Mail\Components\Mailer',
           'defaultFrom' => 'robot@s3stores.com',
       ],

       'auth' => [
           'class' => '\\Modules\\User\\Components\\Auth'
       ],

       'logger' => include __DIR__. DS . 'logger.php',
       'errorHandler' => [
           'class' => '\\Xcart\\App\\Main\\ErrorHandler',
           'debug' => false,
           'ignoringTypes' => [
//               E_RECOVERABLE_ERROR,
               E_DEPRECATED,
               E_USER_DEPRECATED,
               E_NOTICE,
               E_USER_NOTICE,
               E_WARNING,
               E_USER_WARNING,
           ],
           'loggingIgnoredTypes' => [
               E_RECOVERABLE_ERROR,
//               E_DEPRECATED,
               E_USER_DEPRECATED,
//               E_WARNING,
               E_USER_WARNING,
               E_USER_NOTICE,
           ]
       ],
   ],
   'autoloadComponents' => [
       'errorHandler',
//       'logger',
       'db',
   ]
],  $local_config);