<?php

namespace Modules\Order\Forms;


use Modules\Core\Forms\FrontendForm;
use Modules\Order\OrderModule;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\FileField;

class PurchaseOrderDetailsForm extends FrontendForm
{
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
                    'inputmode' => 'numeric',
                ]
            ],
            'organization_name' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Organization Name'),
                'hint' => OrderModule::t('The name of your organization'),
                'html' => [
                    'placeholder' => OrderModule::t('Eureka Inc.')
                ],
            ],
            'purchase_order_file' => [
                'class' => FileField::class,
                'label' => OrderModule::t('Attach original PO'),
                'required' => false,
                'hint' => OrderModule::t('Alternatively fax PO to 1-800-929-2835'),
                'types' => ['.pdf'],
            ]
        ];
    }
}