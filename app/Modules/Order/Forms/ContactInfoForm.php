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
    public $replacement;

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
                    'placeholder' => '(609) 924-8399',
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

    public function getAttributes()
    {
        $data = parent::getAttributes();

        if ($this->replacement) {
            $t_data = [];
            $replace = $this->replacement;
            foreach ($data as $key => $val) {
                $t_data[$replace[$key]] = $val;
            }
            $data = $t_data;
        }

        return $data;
    }

    public function setAttributes(array $data)
    {
        $t_data = $data;

        if ($this->replacement) {
            $replace = array_flip($this->replacement);
            foreach ($data as $key => $val) {
                if (\is_string($val)) {
                    $t_data[$replace[$key]] = trim($val);
                }
            }
        }

        return parent::setAttributes($t_data);
    }
}