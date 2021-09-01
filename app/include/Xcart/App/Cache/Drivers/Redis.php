<?php
namespace Xcart\App\Cache\Drivers;

use Doctrine\Common\Cache\RedisCache;
use Xcart\App\Cache\CacheDriver;

class Redis extends CacheDriver
{
    public $address = '127.0.0.1';
    public $port = 6379;
    public $wait = 0;

    /** @var RedisCache */
    protected $dbal_redis;


    public function init()
    {
        $r = new \Redis();

        if ( $r->connect($this->address,null, $this->wait) )
        {
            $this->dbal_redis = new RedisCache();
            $this->dbal_redis->setRedis($r);
        }
    }

    public function mget($keys, $default = null)
    {
        $values = $this->dbal_redis->fetchMultiple($keys);

        if ($t_keys = array_diff($keys, array_keys($values))) {
            foreach ($t_keys as $t_key) {
                $values[$t_key] = $default;
            }
        }

        return $values;
    }

    protected function getValue($key)
    {
        return $this->dbal_redis->fetch($key) ?: null;
    }

    protected function setValue($key, $data, $timeout)
    {
        if (is_null($data)) {
            $this->dbal_redis->delete($key);
        }
        else {
            $this->dbal_redis->save($key, $data, $timeout);
        }
    }

    public function serialize($value)
    {
        if ($this->serializer) {
            return call_user_func($this->serializer[0], $value);
        } else {
            return $value;
        }
    }

    public function unserialize($value)
    {
        if ($this->serializer) {
            return call_user_func($this->serializer[1], $value);
        } else {
            return $value;
        }
    }


    public function buildKey($key)
    {
        return $key;
    }

    public function gc($force = false, $expiredOnly = true)
    {
        //@NOTE: Automate gc in redis;
    }

    public function cleanUp($force = false)
    {
        $this->dbal_redis->flushAll();
    }
}