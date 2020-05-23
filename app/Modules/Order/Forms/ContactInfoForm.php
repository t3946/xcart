<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Order\OrderModule;
use Modules\Order\Validation\PhoneValidator;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\PhoneExtValidator;

class ContactInfoForm extends FrontendForm
{
    public $replacement;

    public function getFields()
    {
        return [
            'firstname' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Full name'),
                'required' => true,
                'hint' => OrderModule::t('First and last name of the order contact person'),
                'html' => [
                    'placeholder' => OrderModule::t('Albert H. Einstein')
                ],
            ],

            'phone' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Phone'),
                'required' => true,
                'hint' => OrderModule::t('Phone number at which you can be reached is a must, otherwise order processing will be delayed'),
                'validators' => [
                    new PhoneValidator(),
                ],
                'html' => [
                    'placeholder' => OrderModule::t('(609) 924-8399'),
                    'class' => 'phone'
                ],
                'extend' => 'phone_ext',
            ],

            'phone_ext' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('ext'),
                'html' => [
                    'class' => 'phone_ext',
                ],
                'extends' => true,
                'validators' => [
                    new PhoneExtValidator(),
                ],
            ],

            'track_sms' => [
                'class' => CheckboxField::class,
                'label' => OrderModule::t('SMS notifications'),
                'hint' => OrderModule::t('Get shipment status notifications by SMS (free service)'),
                'labelTemplate' => 'forms/field/checkbox/label.tpl',
            ],

            'email' => [
                'class' => EmailField::class,
                'label' => OrderModule::t('Email'),
                'hint' => OrderModule::t('Order progress notifications will be sent here'),
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'placeholder' => OrderModule::t('albert.einstein@gmail.com'),
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
                if (is_string($val)) {
                    $t_data[$replace[$key]] = trim($val);
                }
            }
        }

        return parent::setAttributes($t_data);
    }

    public function renamedFields()
    {
        $fields = $this->getFields();

        if ($this->replacement) {
            $newFields = [];
            $replace = $this->replacement;
            foreach ($fields as $fieldName => $fieldInfo) {

                if(isset($fieldInfo['extend'], $this->replacement[$fieldInfo['extend']])){
                    $fieldInfo['extend'] = $this->replacement[$fieldInfo['extend']];
                }
                $newFields[$replace[$fieldName]] = $fieldInfo;
            }

            $fields = $newFields;
        }

        return $fields;
    }
}