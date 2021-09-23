<?php

namespace Modules\Core\Behaviours;

class CheckoutFormDisplayBehavior extends FrontendFormDisplayBehavior
{
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors_checkout.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];
}
