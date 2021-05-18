<?php

namespace Xcart\App\Orm\Fields;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

/**
 * Class FloatField
 * @package Xcart\App\Orm
 */
class FloatField extends Field
{
    /**
     * @return string
     */
    public function getSqlType()
    {
        return Type::getType(Types::FLOAT);
    }

    public function getValue()
    {
        return floatval(parent::getValue());
    }
}
