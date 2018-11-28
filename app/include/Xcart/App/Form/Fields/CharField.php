<?php

namespace Xcart\App\Form\Fields;

use Xcart\App\Exceptions\Exception;

/**
 * Class CharField
 * @package Mindy\Form
 */
class CharField extends Field
{

    public $userClear = true;

    public function getValue()
    {
        $value = parent::getValue();

        if ($value instanceof \Xcart\App\Orm\Manager) {
            throw new Exception('Value must be a string, not a manager');
        }

        return htmlentities($this->value);
    }
}
