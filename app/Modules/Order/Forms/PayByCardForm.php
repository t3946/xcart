<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Order\OrderModule;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Validation\EmailValidator;

class PayByCardForm extends FrontendForm
{
    protected array $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];

    public function getFields()
    {
        return [
            'pbc_card_holder_name' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Cardholder name' ),
                'required' => true,
                'html' => [
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required checkout__single-common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'pbc_card_details' => [
                'class' => EmailField::class,
                'label' => OrderModule::t( 'Credit / Debit card details' ),
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required checkout__single-common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],
        ];
    }
}