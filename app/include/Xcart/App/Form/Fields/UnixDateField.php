<?php

namespace Xcart\App\Form\Fields;

class UnixDateField extends DateField
{

    public function getValue()
    {
        return (int) parent::getValue();
    }
    public function getRenderValue()
    {
        if (!is_null($this->value) && $this->value != 0) {
            $date = $this->getDateFromValue();

            return ($date) ? $date->format('Y-m-d') : '';
        }
        return '';
    }
}
