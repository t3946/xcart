<?php

namespace Xcart;


class Connection
{
    private static $_instance = null;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @param array $params
     * @return \Doctrine\DBAL\Connection
     */
    static public function getInstance($params = null)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = \Doctrine\DBAL\DriverManager::getConnection($params);
        }
        return self::$_instance;
    }
}