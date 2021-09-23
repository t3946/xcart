<?php

namespace Xcart\App\Form\Fields;

class UnixDateField extends DateField
{

    public function getValue()
    {
        return (int) parent::getValue();
    }
}
