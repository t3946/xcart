<?php

namespace Modules\Order\Forms;


use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Validation\EmailValidator;

class PurchaseOrderForm extends BaseForm
{
    public function getFields()
    {
        return [
            'po_number' => [
                'class' => CharField::class,
                'label' => 'PO number',
                'required' => true
            ],
            'company_name' => [
                'class' => CharField::class,
                'label' => 'Organization name',
                'required' => true
            ],
            'name_of_purchaser' => [
                'class' => CharField::class,
                'label' => 'Full name',
                'required' => true
            ],
            'purchase_manager_phone' => [
                'class' => CharField::class,
                'label' => 'Phone',
                'required' => true,
            ],
            'purchase_manager_fax' => [
                'class' => CharField::class,
                'label' => 'Fax',
            ],
            'purchase_manager_email' => [
                'class' => CharField::class,
                'label' => 'Email',
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ]
            ],
            'accounts_payable_full_name' => [
                'class' => CharField::class,
                'label' => 'Full name',
                'required' => true,
            ],
            'accounts_payable_phone' => [
                'class' => CharField::class,
                'label' => 'Phone',
                'required' => true,
            ],
            'accounts_payable_fax' => [
                'class' => CharField::class,
                'label' => 'Fax',
            ],
            'accounts_payable_email' => [
                'class' => CharField::class,
                'label' => 'Email',
                'required' => true,
                'validators' => [
                    new EmailValidator()
                ]
            ],

        ];
    }
}