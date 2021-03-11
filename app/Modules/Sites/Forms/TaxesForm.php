<?php


namespace Modules\Sites\Forms;


use Modules\Goods\Admin\OptionVariantsAdmin;
use Modules\Sites\Admin\SitesAdmin;
use Modules\Sites\Admin\TaxRatesAdmin;
use Modules\Sites\Models\TaxModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class TaxesForm extends ModelForm
{
    public array $exclude = ['position', 'sites'];

    public function getFieldsets()
    {
        return [[
            'tax_name',
            'regnumber',
            'apply_to',
            'address_type',
            'is_vat',
            'price_includes_tax',
            'rates',
            'active',
        ]];
    }

    public function getModel()
    {
        return new TaxModel();
    }

    public function getName()
    {
        return 'Tax details';
    }

    public function getFields()
    {
        return [
            'is_vat' => [
                'class' => DropDownField::class,
                'choices' => [
                    0 => 'Sales',
                    1 => 'VAT',
                ],
            ],
            'rates' => [
                'class' => ListViewField::class,
                'adminClass' => TaxRatesAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl'
            ],
        ];
    }
}