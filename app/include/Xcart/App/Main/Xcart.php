<?php

namespace Xcart\App\Main;

use Modules\Core\Components\Profiler;
use Xcart\App\Application\Application;
use Xcart\App\Helpers\Creator;

if (!function_exists('d')) {
    require_once __DIR__ . '/dump.php';
}

class Xcart
{
    public static function getVersion()
    {
        return '0.1';
    }

    /** @var \Xcart\App\Application\Application */
    protected static $_app;

    public static function init($configuration, $application = 'Xcart\App\Application\Application')
    {
        static::$_app = new $application;
        static::$_app = Creator::configure(static::$_app, $configuration);

        Profiler::getInstance()->addPoint('app b init');
        if (method_exists(static::$_app, 'init')) {
            static::$_app->init();
        }

        Profiler::getInstance()->addPoint('app init');
    }

    /**
     * @return \Xcart\App\Application\Application
     */
    public static function app()
    {
        return static::$_app;
    }
}