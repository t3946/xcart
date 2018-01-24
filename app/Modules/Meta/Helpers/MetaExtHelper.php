<?php
namespace Modules\Meta\Helpers;


class MetaExtHelper
{
    private static $_instance;
    private static $_params = [];

    public static function setParams(iterable $params):void
    {
        static::$_params = $params;
    }

    public static function addParam(string $property, $val):void
    {
        static::$_params[$property] = $val;
    }

    public static function getParams():iterable
    {
        return static::$_params;
    }

    public static function getInstance():self
    {

        if (empty(static::$_instance)) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }



}