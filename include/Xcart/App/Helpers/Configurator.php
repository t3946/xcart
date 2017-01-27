<?php

namespace Xcart\App\Helpers;
use Xcart\App\Exceptions\InvalidConfigException;

/**
 * Helper class that create objects and configure it
 *
 * Class Configurator
 * @package Xcart\App\Helpers
 */
class Configurator
{
    /**
     *
     * @param $class string|array
     * @param array $config array
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function create($class, $config = [])
    {
        if (is_array($class) && isset($class['class'])) {
            $config = $class;
            $class = $config['class'];
            unset($config['class']);
        } elseif (!is_string($class)) {
            throw new InvalidConfigException("Class name must be defined");
        }
        
        $obj = new $class;
        $obj = self::configure($obj, $config);
        if (method_exists($obj, 'init')) {
            $obj->init();
        }
        return $obj;
    }

    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        
        return $object;
    }
}