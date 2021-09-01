<?php

namespace Xcart\App\Orm\Fields;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

/**
 * Class TextField
 * @package Xcart\App\Orm
 */
class TextField extends Field
{
    public $formField = '\Xcart\App\Form\Fields\TextField';

    /**
     * @return string
     */
    public function getSqlType()
    {
        return Type::getType(Types::TEXT);
    }
}