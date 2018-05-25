<?php

namespace Modules\Order\Forms;

use Modules\Order\Validation\PhoneValidator;
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
                'required' => true,
                'hint' => 'Phone number at which you can be reached is a must, otherwise order processing will be delayed',
                'validators' => [
                    new PhoneValidator(),
                ],
                'html' => [
                    'placeholder' => '(609) 734-8000',
                    'class' => 'phone'
                ],
            ],

            'phone_ext' => [
                'class' => NumberField::class,
                'label' => 'ext',
                'html' => [
                    'class' => 'phone_ext',
                ]
            ],

            'email' => [
                'class' => EmailField::class,
                'label' => 'Email',
                'hint' => 'Order progress notifications will be sent here',
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'placeholder' => 'albert.einstein@gmail.com',
                ],
            ],
        ];
    }
}