<?php


namespace Modules\Forms\Forms;


use Modules\Forms\Models\TemplateCategoryModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Form;

class TemplateFilterForm extends Form
{
    public function getFields()
    {
        return [
            'department' => [
                'class' => DropDownField::class,
                'label' => 'Templates for communicating to',
                'choices' => [
                    '' => 'Please select',
                    'customer' => 'Customer',
                    'distributor' => 'Distributor',
                    'our_customer_service' => 'Our customer service',
                    'third_party' => 'Third party',
                ]
            ],
            'category' => [
                'class' => DropDownField::class,
                'choices' => function () {
                    $res[''] = 'None';
                    foreach (TemplateCategoryModel::objects() as $category) {
                        $res[$category->id] = $category;
                    }
                    return $res ?? [];
                }
            ],
            'active' => [
                'class' => DropDownField::class,
                'choices' => [
                    '' => '',
                    'Y' => 'Yes',
                    'N' => 'No',
                ],
            ]
        ];
    }
}