<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 29.05.2018
 * Time: 10:30
 */

namespace Modules\Order\Forms;


use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\FileField;
use Xcart\App\Form\Fields\NumberField;

class PurchaseOrderDetailsForm extends BaseForm
{
    public function getFields(): array
    {
        return [
            'po_number' => [
                'class' => CharField::class,
                'label' => 'PO number',
                'required' => true,
                'hint' => 'PO number or internal order code in your system',
                'html' => [
                    'class' => 'po_number',
                    'placeholder' => '14031879'
                ]
            ],
            'organization_name' => [
                'class' => CharField::class,
                'label' => 'Organization Name',
                'required' => true,
                'hint' => 'The name of your organization',
                'html' => [
                    'placeholder' => 'Eureka Inc.'
                ],
            ],
            'purchase_order_file' => [
                'class' => FileField::class,
                'label' => 'Attach original PO',
                'required' => false,
                'hint' => 'Alternatively fax PO to (813) 944-4516',
                'types' => ['.pdf'],
            ]
        ];
    }
}