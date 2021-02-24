<?php

namespace Modules\Order\Forms;

use Modules\Order\OrderModule;
use Modules\Order\Validation\PhoneValidator;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\FileField;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\PhoneExtValidator;

class CheckoutAccountsPayableForm extends ContactInfoFaxForm
{
    public $replacement = [];

    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];

    public function getFields(): array
    {
        return [
            'ap_firstname' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Full name' ),
                'required' => true,
                'hint' => OrderModule::t( 'First and last name of the order contact person' ),
                'html' => [
                    'placeholder' => OrderModule::t( 'Albert H. Einstein' ),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'ap_phone' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Phone' ),
                'required' => true,
                'hint' => OrderModule::t( 'Phone number at which you can be reached is a must, otherwise order processing will be delayed' ),
                'validators' => [
                    new PhoneValidator(),
                ],
                'html' => [
                    'placeholder' => OrderModule::t( '(609) 924-8399' ),
                    'class' => 'phone',
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'extend' => 'ci_phone_ext',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-compound__phone-main-field checkout-field',
                'className' => 'checkout-compound-main-container common-input_checkout-phone',
                'containerClass' => 'checkout-compound-phone-container',
                'inputClass' => 'common-input',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'ap_phone_ext' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'ext' ),
                'html' => [
                    'class' => 'phone_ext',
                ],
                'extends' => true,
                'validators' => [
                    new PhoneExtValidator(),
                ],
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-phone-ext-field',
                'containerClass' => 'checkout-phone-ext-container',
                'shortHintClass' => 'checkout-phone-ext-short-hint',
                'longHintClass' => 'checkout-phone-ext-long-hint',
            ],

            'ap_track_sms' => [
                'class' => CheckboxField::class,
                'label' => OrderModule::t( 'SMS notifications' ),
                'hint' => OrderModule::t( 'Get shipment status notifications by SMS (free service)' ),
                'labelTemplate' => 'forms/field/checkbox/label.tpl',
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-checkbox',
            ],

            'ap_email' => [
                'class' => EmailField::class,
                'label' => OrderModule::t( 'Email' ),
                'hint' => OrderModule::t( 'Order progress notifications will be sent here' ),
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'placeholder' => OrderModule::t( 'albert.einstein@gmail.com' ),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'purchase_order_file' => [
                'class' => FileField::class,
                'label' => OrderModule::t( 'Attach original PO' ),
                'required' => false,
                'hint' => OrderModule::t( 'Alternatively fax PO to 1-800-929-2835' ),
                'types' => [ '.pdf' ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
            ],
        ];
    }
}
