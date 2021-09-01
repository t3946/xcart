<?php

namespace Modules\Order\Forms;


use Modules\Core\Forms\FrontendForm;
use Modules\Order\OrderModule;
use Xcart\App\Form\Fields\CharCleanField;

class CheckoutPurchaseOrderDetailsForm extends FrontendForm
{
    protected array $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];

    public function getFields(): array
    {
        return [
            'po_number' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('PO number'),
                'required' => true,
                'hint' => OrderModule::t('PO number or internal order code in your system'),
                'html' => [
                    'class' => 'po_number',
                    'placeholder' => OrderModule::t('14031879'),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                    'inputmode' => 'numeric',
                ],
                'requiredClass' => 'common-required',
                'labelClass' => 'common-label common-label_required',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],
            'organization_name' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Organization Name'),
                'hint' => OrderModule::t('The name of your organization'),
                'html' => [
                    'placeholder' => OrderModule::t('Eureka Inc.'),
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelCommentClass' => 'common-comment',
                'labelClass' => 'common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],
        ];
    }
}