<?php

namespace Xcart\App\Form\Fields;


class PercentField extends CharField
{
    public $type = 'number';

    public $inputTemplate = 'forms/field/percent/input.tpl';
}
