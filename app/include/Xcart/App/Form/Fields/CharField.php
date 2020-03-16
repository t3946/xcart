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

        if (is_string($this->value)) {
            return htmlentities(html_entity_decode($this->value));
        }
        return $this->value;
    }
}
