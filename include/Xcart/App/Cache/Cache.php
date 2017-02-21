<?php

namespace Xcart\App\Cache;


use Xcart\App\Helpers\Creator;
use Xcart\App\Helpers\SmartProperties;

class Cache
{
    use SmartProperties;

    protected $_config = [];

    protected $_drivers = [];

    protected $_last_active_cache = [];
    protected $_stack_lac = [];

    public $defaultDriver = 'default';
    public $saveInMemory = true;
    public $numSavedInMemory = 30;
    
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
        if ($this->saveInMemory) {
            $this->setInMemory($key, $value, $timeout);
        }

        return $this->getDriver($this->defaultDriver)->set($key, $value, $timeout);
    }

    public function get($key, $default = null)
    {
        if ($this->saveInMemory) {
            if ($value = $this->getInMemory($key)) {
                return $value;
            }
        }

        $value = $this->getDriver($this->defaultDriver)->get($key, $default);

        if ($this->saveInMemory) {
            $this->setInMemory($key, $value, 5);
        }

        return $value;
    }

    public function cleanUp($force = false)
    {
        $this->cleanUpInMemory();
        $this->getDriver($this->defaultDriver)->cleanUp($force);
    }

    private function setInMemory($key, $value, $timeout = null)
    {
        $this->gcInMemory();

        array_unshift($this->_stack_lac, $key);
        $this->_last_active_cache[$key] = ['value' => $value, 'timeout' => $timeout + time()];

        if (count ($this->_stack_lac) > $this->numSavedInMemory) {
            $count = count ($this->_stack_lac) - $this->numSavedInMemory;

            for ($i=1; $count >= $i; $i++)
            {
                $key = array_pop($this->_stack_lac);
                unset($this->_last_active_cache[$key]);
            }
        }
    }

    private function getInMemory($key)
    {
        $this->gcInMemory();

        if (isset($this->_last_active_cache[$key])) {
            return $this->_last_active_cache[$key]['value'];
        }

        return null;
    }

    private function gcInMemory()
    {
        foreach ($this->_last_active_cache as $key => $params)
        {
            if ($params['timeout'] < time())
            {
                if ($l_key = array_search($key,$this->_stack_lac)) {
                    unset($this->_stack_lac[$l_key]);
                }

                unset($this->_last_active_cache[$key]);
            }
        }
    }

    private function cleanUpInMemory()
    {
        $this->_last_active_cache = [];
        $this->_stack_lac = [];
    }
}