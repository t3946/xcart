<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Admin\Admin\DxProductsAdmin;
use Xcart\App\Form\Fields\ListViewField;

class DistributorProductsForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'products',
        ]];
    }

    public function getFields()
    {
        return [
            'products' => [
                'class' => ListViewField::class,
                'adminClass' => DxProductsAdmin::class,
                'label' => 'Distributor products:',
                'hintTemplate' => $this->hintTemplate,
            ]
        ];
    }
}