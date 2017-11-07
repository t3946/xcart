<?php

namespace Xcart\App\Form\Fields;

/**
 * Class DateTimeField
 * @package Mindy\Form
 */
class DateTimeField extends DateField
{
//    public $type = 'datetime-local';

    public function getAirDPOptions()
    {
        return array_replace_recursive(parent::getAirDPOptions(), [
            'timepicker' => true,
        ]);
    }
}
