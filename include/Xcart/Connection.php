<?php

namespace Xcart;


class Connection
{
    private static $_instance = null;
    private static $_connection = null;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @return Connection
     */
    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param string $sql_host
     * @param string $sql_db
     * @param string $sql_user
     * @param string $sql_password
     * @return static
     */
    static public function init($sql_host = null, $sql_db = null, $sql_user = null, $sql_password = null)
    {
        self::$_connection = new \Doctrine\DBAL\Driver\PDOConnection("mysql:host={$sql_host};dbname={$sql_db}", $sql_user, $sql_password);
        return self::$_instance;
    }

    static public function getConnection()
    {
        return self::$_connection;
    }
}