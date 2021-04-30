<?php

namespace Xcart\App\Orm\Fields;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

class BigIntField extends IntField
{
    public function getSqlType()
    {
        return Type::getType(Types::BIGINT);
    }
}