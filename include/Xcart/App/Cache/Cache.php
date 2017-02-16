<?php

namespace Xcart\App\Cache;


use Xcart\App\Helpers\Creator;
use Xcart\App\Helpers\SmartProperties;

class Cache
{
    use SmartProperties;

    protected $_config = [];

    protected $_drivers = [];
    
    public $defaultDriver = 'default';
    
    public function setDrivers($config)
    {
        $this->_config = $config;
    }

    /**
     * @param string $name
     * @return CacheDriver|null
     * @throws \Xcart\App\Exceptions\InvalidConfigException
     */
    public function getDriver($name = 'default')
    {
        if (!isset($this->_drivers[$name])) {
            if (isset($this->_config[$name])) {
                $this->_drivers[$name] = Creator::create($this->_config[$name]);
            } else {
                return null;
            }
        }
        return $this->_drivers[$name];
    }
    
    public function set($key, $value, $timeout = null)
    {
        return $this->getDriver($this->defaultDriver)->set($key, $value, $timeout);
    }

    public function get($key, $default = null)
    {
        return $this->getDriver($this->defaultDriver)->get($key, $default);
    }
}