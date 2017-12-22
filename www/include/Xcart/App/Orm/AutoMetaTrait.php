<?php

namespace Xcart\App\Orm;

trait AutoMetaTrait
{
    /**
     * @return MetaData
     */
    public static function getMeta()
    {
        return AutoMetaData::getInstance(get_called_class());
    }
}