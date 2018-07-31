<?php

namespace Xcart\App\Form\Fields;

/**
 * Class TextAreaField
 * @package Mindy\Form
 */
class TextAreaField extends Field
{
    public $fieldType = 'textarea';
    public $inputTemplate = 'forms/field/textarea/input.tpl';

    public $readonly = false;
}
