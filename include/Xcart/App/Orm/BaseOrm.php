<?php
namespace Xcart\App\Orm;

use Xcart\App\Helpers\ClassNames;

class BaseOrm
{
    use ClassNames;

    /**
     * @param array $row
     *
     * @return static
     */
    public static function create(array $row)
    {
        $className = static::className();
        /** @var static $record */
        $record = new $className;
        $record->fill($row);

        return $record;
    }


    public static function objects($instance = null)
    {
        $className = get_called_class();

        return self::getManager($instance ? $instance : new $className);
    }

    public static function getManager($instance)
    {
        return new Manager($instance);
    }

    public function fill($row)
    {

    }
}