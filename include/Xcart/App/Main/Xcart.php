<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 10/04/16 10:20
 */

namespace Xcart\App\Main;

use Xcart\App\Application\Application;
use Xcart\App\Helpers\Configurator;

class Xcart
{
    protected static $_app;

    public static function init($configuration, $application = 'Xcart\App\Application\Application')
    {
        static::$_app = Configurator::create($application, $configuration);
    }

    /**
     * @return \Xcart\App\Application\Application
     */
    public static function app()
    {
        return static::$_app;
    }
}