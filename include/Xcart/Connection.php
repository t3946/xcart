<?php

namespace Xcart;

use \Doctrine\DBAL\DriverManager;
use Xcart\App\Main\Xcart;

/**
 * Class Connection
 *
 * @package Xcart
 */
class Connection
{
    private static $_instance = null;

    /**
     * @return \Doctrine\DBAL\Connection
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
     * @return \Doctrine\DBAL\Connection
     */
    static public function getInstance($params = [])
    {
        if (!self::$_instance) {
            self::$_instance = DriverManager::getConnection($params);

            self::$_instance
                ->getDatabasePlatform()
                ->registerDoctrineTypeMapping('enum', 'string');
        }
        return self::$_instance;
    }
}