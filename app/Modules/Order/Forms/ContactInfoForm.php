<?php

namespace Modules\Order\Forms;

use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\NumberField;

class ContactInfoForm extends BaseForm
{
    public function getFields()
    {
        return [
            'firstname' => [
                'class' => CharField::class,
                'label' => 'fullname',
                'required' => true
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
                'required' => true
            ],
        ];
    }
}