<?php

namespace Modules\Order\Forms;

use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Validation\EmailValidator;

class ContactInfoForm extends BaseForm
{
    public function getFields()
    {
        return [
            'firstname' => [
                'class' => CharField::class,
                'label' => 'Full name',
                'required' => true,
                'hint' => 'First and last name of the order contact person',
                'html' => [
                    'placeholder' => 'Albert H. Einstein'
                ],
            ],

            'phone' => [
                'class' => CharField::class,
                'label' => 'Phone',
                'required' => true
            ],

            'phone_ext' => [
                'class' => NumberField::class,
                'label' => 'ext'
            ],

            'email' => [
                'class' => EmailField::class,
                'label' => 'Email',
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ],
            ],
        ];
    }
}