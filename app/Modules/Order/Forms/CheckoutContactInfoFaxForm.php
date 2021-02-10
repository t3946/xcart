<?php

namespace Modules\Order\Forms;

use Modules\Order\OrderModule;
use Modules\Order\Validation\PhoneValidator;
use Xcart\App\Form\Fields\CharCleanField;

class CheckoutContactInfoFaxForm extends CheckoutContactInfoForm
{
    public $replacement = 'ci_';

    public function getFields(): array
    {
        $fields = parent::getFields();

        $fields['ci_fax'] = [
            'class' => CharCleanField::class,
            'label' => OrderModule::t('Fax'),
            'required' => false,
            'validators' => [
                new PhoneValidator(),
            ],
            'html' => [
                'placeholder' => OrderModule::t('(609) 924-8399'),
                'class' => 'phone'
            ],
            'labelClass' => 'common-label',
            'hintClass' => 'common-hint',
            'fieldClass' => 'common-field',
        ];

        return $fields;
    }
}