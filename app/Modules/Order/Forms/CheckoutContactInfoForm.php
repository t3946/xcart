<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Order\OrderModule;
use Modules\Order\Validation\CanadaCODSValidator;
use Modules\Order\Validation\PhoneValidator;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Validation\EmailValidator;
use Xcart\App\Validation\PhoneExtValidator;

class CheckoutContactInfoForm extends FrontendForm
{
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];

    public function getFields()
    {
        return [
            'firstname' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'Full name' ),
                'required' => true,
                'hint' => OrderModule::t( 'First and last name of the order contact person' ),
                'html' => [
                    'placeholder' => OrderModule::t( 'Albert H. Einstein' ),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'phone' => [
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
                    'inputmode' => 'numeric',
                    'autocomplete' => 'tel-national'
                ],
                'extend' => 'phone_ext',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-compound__phone-main-field checkout-field',
                'className' => 'checkout-compound-main-container common-input_checkout-phone',
                'containerClass' => 'checkout-compound-phone-container',
                'inputClass' => 'common-input',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'phone_ext' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t( 'ext' ),
                'html' => [
                    'class' => 'phone_ext',
                    'inputmode' => 'numeric',
                    'autocomplete' => 'tel-extension'
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

            'track_sms' => [
                'class' => CheckboxField::class,
                'label' => OrderModule::t( 'SMS notifications' ),
                'hint' => OrderModule::t( 'Get shipment status notifications by SMS (free service)' ),
                'labelTemplate' => 'forms/field/checkbox/label.tpl',
                'fieldClass' => 'checkout-field__checkbox',
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'inputClass' => 'common-checkbox',
            ],

            'email' => [
                'class' => EmailField::class,
                'label' => OrderModule::t( 'Email' ),
                'hint' => OrderModule::t( 'Order progress notifications will be sent here' ),
                'required' => true,
                'validators' => [
                    new EmailValidator(),
                ],
                'html' => [
                    'placeholder' => OrderModule::t( 'albert.einstein@gmail.com' ),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                    'autocomplete' => 'email'
                ],
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'labelCommentClass' => 'common-comment',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'non_us_confirmation' => [
                'class' => CheckboxField::class,
                'hint' => OrderModule::t( 'By checking this box I agree to be responsible for custom duties, CODs, and other charges associated with brining to Canada. All prices are in USD. ' ),
                'labelTemplate' => 'forms/field/checkbox/label.tpl',
                'fieldTemplate' => 'forms/field/checkbox/field_canada_cods_confirmation.tpl',
                'hintClass' => 'common-hint',
                'validators' => [
                    new CanadaCODSValidator(),
                ],
                'html' => [
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'fieldClass' => 'common-checkbox checkout-canada-cods-checkbox checkout__canada-cods-checkbox',
                'containerClass' => 'checkout__canada-cods-field hide',
                'inputClass' => 'common-checkbox checkout__canada-cods-checkbox',
            ],
        ];
    }

    public function getAttributes()
    {
        $data = parent::getAttributes();

        if ( $this->replacement ) {
            $t_data = [];
            $replace = $this->replacement;
            foreach ( $data as $key => $val ) {
                $t_data[ $replace[ $key ] ] = $val;
            }
            $data = $t_data;
        }

        return $data;
    }

    public function setAttributes( array $data )
    {
        $t_data = $data;

        if ( $this->replacement ) {
            $replace = array_flip( $this->replacement );
            foreach ( $data as $key => $val ) {
                if ( is_string( $val ) ) {
                    $t_data[ $replace[ $key ] ] = trim( $val );
                }
            }
        }

        return parent::setAttributes( $t_data );
    }

    public function renamedFields()
    {
        $fields = $this->getFields();

        if ( $this->replacement ) {
            $newFields = [];
            $replace = $this->replacement;
            foreach ( $fields as $fieldName => $fieldInfo ) {

                if ( isset( $fieldInfo[ 'extend' ], $this->replacement[ $fieldInfo[ 'extend' ] ] ) ) {
                    $fieldInfo[ 'extend' ] = $this->replacement[ $fieldInfo[ 'extend' ] ];
                }
                $newFields[ $replace[ $fieldName ] ] = $fieldInfo;
            }

            $fields = $newFields;
        }

        return $fields;
    }
}