<?php
namespace Xcart\App\Helpers;


trait ClassNames
{
    public static function className()
    {
        return get_called_class();
    }

    public static function classNameShort()
    {
        $class = get_called_class();
        $classParts = explode('\\', $class);
        return array_pop($classParts);
    }

    public static function classNameUnderscore()
    {
        return Text::camelCaseToUnderscores(static::classNameShort());
    }

    public static function getModuleName()
    {
        $class = get_called_class();
        $classParts = explode('\\', $class);
        if ($classParts[0] == 'Modules' && isset($classParts[1])) {
            return $classParts[1];
        }
        return null;
    }

    public static function getClassAbbr()
    {
        $result = '';

        $class = get_called_class();
        $classParts = explode('\\', $class);
        foreach ($classParts as $v) {
            $result .=  $v[0];
        }

        return $result;
    }
}