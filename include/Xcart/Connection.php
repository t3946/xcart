<?php

namespace Xcart;

use \Doctrine\DBAL\DriverManager;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\DefaultConnection;

/**
 * Class Connection
 *
 * @package Xcart
 */
class Connection
{
    /** @var DefaultConnection null  */
    private static $_instance = null;

    /**
     * @return DefaultConnection
     */
    public static function getInstanceFromApp()
    {
        if (!self::$_instance) {
            self::$_instance = Xcart::app()->db->getConnection();
        }

        return self::$_instance;
    }

    /**
     * @param array $params
     * @return DefaultConnection
     */
    static public function getInstance($params = [])
    {
        if (!self::$_instance) {
            if ($params) {
                self::$_instance = DriverManager::getConnection($params);
            }
            else {
                self::getInstanceFromApp();
            }

            self::$_instance
                ->getDatabasePlatform()
                ->registerDoctrineTypeMapping('enum', 'string');
        }
        return self::$_instance;
    }
}