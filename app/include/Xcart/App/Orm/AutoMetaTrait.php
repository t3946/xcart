<?php

namespace Xcart\App\Orm;

trait AutoMetaTrait
{
    /**
     * @return AutoMetaData|MetaData
     */
    public static function getMeta(): AutoMetaData
    {
        return AutoMetaData::getInstance(static::class);
    }
}