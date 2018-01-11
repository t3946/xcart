<?php
namespace Xcart\App\Cache\Drivers;

use Doctrine\Common\Cache\RedisCache;
use Xcart\App\Cache\CacheDriver;

class Redis extends CacheDriver
{
    public $address = '127.0.0.1';
    public $port = 6379;
    public $timeout = 0;

    /** @var RedisCache */
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
        $this->dbal_redis->fetch($key);
    }

    protected function setValue($key, $data, $timeout)
    {
        $this->dbal_redis->save($key, $data, $timeout);
    }

    public function serialize($value)
    {
        return $value;
    }

    public function unserialize($value)
    {
        return $value;
    }


    public function gc($force = false, $expiredOnly = true)
    {

    }

    public function cleanUp($force = false)
    {
        $this->gc(true, !$force);
    }
}