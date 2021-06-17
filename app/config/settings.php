<?php

use Xcart\App\Main\ErrorHandler;
use Modules\User\Components\Auth;
use Modules\Mail\Components\Mailer;
use Modules\Mail\Components\MailComponent;
use Xcart\App\Cache\Drivers\Redis;
use Xcart\App\Cache\Drivers\Memory;
use Xcart\App\Cache\Drivers\File;
use Xcart\App\Cache\Cache;
use Xcart\App\Orm\ConnectionManager;
use Xcart\App\Orm\Cache\FilesystemCache;
use Xcart\App\Orm\Cache\RedisCache;
use Xcart\App\Event\EventManager;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Components\Flash;
use Xcart\App\Finder\FinderFactory;
use Xcart\App\Middleware\MiddlewareManager;
use Xcart\App\Queue\QueueManager;
use Xcart\App\Request\RequestManager;
use Xcart\App\Request\HttpRequest;
use Modules\User\Components\XcartSession;
use Xcart\App\Request\CliRequest;
use Xcart\App\Router\Router;
use Xcart\App\Template\TemplateManager;
use Xcart\App\Storage\Storage;
use Xcart\App\Storage\Adapters\LocalAdapter;
use Xcart\App\Storage\Adapters\LocalZipAdapter;

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
       'dist'   => implode(DS, ['', 'static', 'frontend', 'dist']),
   ],
   'globals' => [
       'blowfish_key' => '8d5db63ada15e11643a0b1c3477c2c5c',
       'blowfish' => new \ctBlowfish(),
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
           'class' => ConnectionManager::class,
           'connections' => [
               'default' => [
                   'memory' => true,
                   'autoCommit' => true,
                   'driver' => 'pdo_mysql',
                   'dbname' => 'xcart_k',
                   'host' => '127.0.0.1',
                   'user' => 'xcart_k',
                   'password' => 'i250923lst',
                   'charset'  => 'utf8mb4',
                   'mapping' => [
                       'enum' => 'string'
                   ],
                   'wrapperClass' => false,
                   'cache' => (defined('APP_DEBUG') && APP_DEBUG) ? [
                       'class' => FilesystemCache::class,
                       'directory' => 'base.runtime.query_cache'
                   ] : [ //PRODUCTION CACHE
                       'class' => RedisCache::class,
                   ],
                   'driverOptions' => [
//                       PDO::ATTR_EMULATE_PREPARES => false,
                       PDO::ATTR_STRINGIFY_FETCHES => false,
//                       PDO::ATTR_PERSISTENT => true, //broken -> https://stackoverflow.com/questions/16217426/is-it-possible-to-use-doctrine-with-persistent-pdo-connections
                   ]
               ]
           ]
       ],

       'queue' => [
           'class' => QueueManager::class,
           'host' => '159.65.220.58',
           'port' => 5672,
           'user' => 'xcart',
           'password' => 'Uv5WxjbRj7pjqzY',
       ],

       'event' => [
           'class' => EventManager::class,
           'events' => include __DIR__ . DS .  'events.php'
       ],

       'breadcrumbs' => ['class' => Breadcrumbs::class],
       'flash' => ['class' => Flash::class],
       'finder' => ['class' => FinderFactory::class],

       'middleware' => [
           'class' => MiddlewareManager::class,
           'middleware' => include __DIR__ . DS . 'middleware.php',
       ],
       'request' => [
           'class' => RequestManager::class,
           'httpRequest' => [
               'class' => HttpRequest::class,
               'session' => [
                   'class' => XcartSession::class
               ]
           ],
           'cliRequest' => [
               'class' => CliRequest::class,
           ]
       ],
       'router' => [
           'class' => Router::class,
           'pathRoutes' => 'base.config.routes'
       ],
       'template' => [
           'class' => TemplateManager::class,
           'forceCompile' => false,
           'forceInclude' => true,
           'autoReload' => false,
           'autoEscape' => false,
       ],

       'storage' => [
           'class' => Storage::class,
           'default' => 'local',
           'adapters' => [
               'local' => [
                   'class' => LocalAdapter::class,
                   'root' => 'www.media',
               ],
               'www' => [
                   'class' => LocalAdapter::class,
                   'root' => 'www',
               ],
               'zip' => [
                   'class' => LocalZipAdapter::class,
                   'root' => 'www',
               ]
           ],
       ],

       'cache' => (defined('APP_DEBUG') && APP_DEBUG) ? [
           'class' => Cache::class,
           'saveInMemory' => true,
           'memoryDriver' => 'memory',
           'drivers' => [
               'default' =>  [
                   'class' => File::class,
               ],
               'memory' =>  [
                   'class' => Memory::class,
                   'numCacheQuery' => 30,
               ],
               'html' =>  [
                   'class' => File::class,
                   'extension' => '.html',
                   'path' => 'root.html_cache',
                   'autoGC' => false,
//                   'keySerialization' => false,
               ]
           ]
       ] : [ //PRODUCTION CACHE
           'class' => Cache::class,
           'drivers' => [
               'default' =>  [
                   'class' => Redis::class,
               ],
               'html' =>  [
                   'class' => File::class,
                   'extension' => '.html',
                   'autoGC' => false,
                   'path' => 'root.html_cache',
               ],
           ]
       ],

       'oldMail' => MailComponent::class,
       'mail' => [
           'class' => Mailer::class,
           'defaultFrom' => 'robot@s3stores.com',
       ],

       'auth' => [
           'class' => Auth::class
       ],

       'logger' => include __DIR__. DS . 'logger.php',
       'errorHandler' => [
           'class' => ErrorHandler::class,
           'debug' => false,
           'useTemplate' => true,
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