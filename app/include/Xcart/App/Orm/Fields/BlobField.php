<?php

namespace Xcart\App\Orm\Fields;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

/**
 * Class BlobField
 * @package Xcart\App\Orm
 */
class BlobField extends Field
{
    public function getSqlType()
    {
        return Type::getType(Types::BLOB);
    }
}

