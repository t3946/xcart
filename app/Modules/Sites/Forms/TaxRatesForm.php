<?php


namespace Modules\Sites\Forms;


use Modules\Sites\Models\TaxRatesModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class TaxRatesForm extends ModelForm
{
    public $exclude = ['tax'];

    public function getFieldsets()
    {
        return [[
            'zone',
            'rate_value',
        ]];
    }

    public function getModel()
    {
        return new TaxRatesModel;
    }

    public function getName()
    {
        return 'Tax rates';
    }

    public function getFields()
    {
        return [
            'zone' => [
                'class' => DropDownField::class,
                'label' => 'View',
            ],
            'rate_value' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:50px'],
                'extend' => 'rate_type',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
            ],
            'rate_type' => [
                'class' => DropDownField::class,
            ]
        ];
    }
}