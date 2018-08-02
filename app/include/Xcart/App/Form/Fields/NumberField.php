<?php

namespace Xcart\App\Form\Fields;

use Xcart\App\Exceptions\Exception;
use Xcart\App\Translate\Translate;
use Xcart\App\Validation\NumberValidator;

/**
 * Class CharField
 * @package Mindy\Form
 */
class NumberField extends CharField
{
    public $type = 'number';

    public function init()
    {
        parent::init();
        $this->validators[] = new NumberValidator();
    }

    public function getValue()
    {
        $value = parent::getValue();

        if ($value instanceof \Xcart\App\Orm\Manager) {
            throw new Exception("Value must be a string, not a manager");
        }
        return $this->value;
    }
}
