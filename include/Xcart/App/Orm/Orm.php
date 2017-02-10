<?php

namespace Xcart\App\Orm;

use Doctrine\DBAL\Driver\Connection;
use Xcart\Connection as XcartConnection;

class Orm
{
    protected static $connection;

    public static function setDefaultConnection(Connection $connection)
    {
        self::$connection = $connection;
    }

    public static function getDefaultConnection()
    {
        if (self::$connection === null) {
            self::$connection = XcartConnection::getInstance();
        }
        return self::$connection;
    }
}