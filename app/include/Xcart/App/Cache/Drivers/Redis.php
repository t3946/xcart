<?php
namespace Xcart\App\Cache\Drivers;

use Doctrine\Common\Cache\RedisCache;
use Xcart\App\Cache\CacheDriver;

class Redis extends CacheDriver
{
    public $address = '127.0.0.1';
    public $port = 6379;
    public $timeout = 0;

    protected $dbal_redis;


    public function init()
    {
        $r = new \Redis();

        if (strpos($this->address, '/') !== -1) {
            $r->connect($this->address,null, $this->timeout);
        }
        else {
            $r->connect($this->address, $this->port, $this->timeout);
        }

        $this->dbal_redis = new RedisCache();
        $this->dbal_redis->setRedis($r);
    }

    protected function getValue($key)
    {

    }

    protected function setValue($key, $data, $timeout)
    {

    }


    public function gc($force = false, $expiredOnly = true)
    {

    }

    public function cleanUp($force = false)
    {
        $this->gc(true, !$force);
    }
}