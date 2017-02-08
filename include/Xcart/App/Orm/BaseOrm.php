<?php
namespace Xcart\App\Orm;

use Exception;
use Xcart\App\Helpers\ClassNames;

class BaseOrm
{
    use ClassNames;

    /**
     * @param array $attributes
     *
     * @return static
     */
    public static function create(array $attributes)
    {
        $className = static::className();

        /** @var static $record */
        $record = new $className;
        if (!empty($attributes)) {
            $record->setAttributes($attributes);
        }

        return $record;
    }


    public static function objectsManager($instance = null)
    {
        $className = get_called_class();

        return self::getManager($instance ? $instance : new $className);
    }

    public static function getManager($instance)
    {
        return new Manager($instance);
    }

    public function setAttributes($attributes)
    {
        foreach ($attributes as $name => $value)
        {
            $this->setAttribute($name, $value);
        }
    }

    //@TODO: Дописать, это тупо костыль
    public function setAttribute($name, $value)
    {
        $this->{$name} = $value;
    }

    public static function __callStatic($method, $args)
    {
        $manager = $method . 'Manager';
        $className = get_called_class();
        if (method_exists($className, $manager) && is_callable([$className, $manager])) {
            return call_user_func_array([$className, $manager], $args);
        } elseif (method_exists($className, $method) && is_callable([$className, $method])) {
            return call_user_func_array([$className, $method], $args);
        } else {
            throw new Exception("Call unknown method {$method}");
        }
    }

    public function __call($method, $args)
    {
        $manager = $method . 'Manager';
        if (method_exists($this, $manager)) {
            return call_user_func_array([$this, $manager], array_merge([$this], $args));
        } elseif (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        } else {
            throw new Exception("Call unknown method {$method}");
        }
    }
}