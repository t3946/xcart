<?php

namespace Xcart\App\Form\Fields;

class CharSwitcherField extends CharField
{
    public string $switcherClass = 'switcher-button';

    public $fieldTemplate = 'forms/field/default/custom/field_switcher.tpl';
}
