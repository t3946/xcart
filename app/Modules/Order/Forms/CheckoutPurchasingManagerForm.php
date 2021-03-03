<?php

namespace Modules\Order\Forms;

use Modules\Order\OrderModule;
use Modules\Order\Validation\PhoneValidator;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\PhoneExtValidator;

class CheckoutPurchasingManagerForm extends CheckoutContactInfoFaxForm
{
    public $replacement = [
        'pm_firstname' => 'purchasing_manager_firstname',
        'pm_phone' => 'purchasing_manager_phone',
        'pm_track_sms' => 'purchasing_manager_track_sms',
        'pm_email' => 'purchasing_manager_email',
        'pm_fax' => 'purchasing_manager_fax',
    ];

    public function getFields(): array
    {
        return [
            'pm_firstname' => [
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

            'pm_phone' => [
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
                'extend' => 'pm_phone_ext',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-compound__phone-main-field checkout-field',
                'className' => 'checkout-compound-main-container common-input_checkout-phone',
                'containerClass' => 'checkout-compound-phone-container',
                'inputClass' => 'common-input',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'pm_phone_ext' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('ext'),
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

            'pm_track_sms' => [
                'class' => CheckboxField::class,
                'label' => OrderModule::t( 'SMS notifications' ),
                'hint' => OrderModule::t( 'Get shipment status notifications by SMS (free service)' ),
                'labelTemplate' => 'forms/field/checkbox/label.tpl',
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-checkbox',
            ],

            'pm_email' => [
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

            'pm_fax' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Fax' ),
                'required' => false,
                'validators' => [
                    new PhoneValidator(),
                ],
                'html' => [
                    'placeholder' => OrderModule::t( '(609) 924-8399' ),
                    'class' => 'phone'
                ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
                'labelCommentClass' => 'common-comment',
            ],
        ];
    }
}
