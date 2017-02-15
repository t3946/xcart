<?php

namespace Xcart;

use \Doctrine\DBAL\DriverManager;
use Xcart\App\Helpers\SmartProperties;

/**
 * Class Connection
 *
 * @package Xcart
 */
class Connection
{
    use SmartProperties;

    private static $_instance = null;
    private $config = [];

    public function __call($name, $arguments)
    {
        if (method_exists(self::$_instance, $name))
        {
            call_user_func_array([self::$_instance, $name], $arguments);
        }
    }

    public function init()
    {
        self::getInstance($this->config);
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $params
     * @return \Doctrine\DBAL\Connection
     */
    static public function getInstance($params = [])
    {
        if (is_null(self::$_instance)) {
            self::$_instance = DriverManager::getConnection($params);
            $platform = self::$_instance->getDatabasePlatform();
            $platform->registerDoctrineTypeMapping('enum', 'string');
        }
        return self::$_instance;
    }
}