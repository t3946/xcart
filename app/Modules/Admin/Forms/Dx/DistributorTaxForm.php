<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Admin\Admin\DxTaxesAdmin;
use Xcart\App\Form\Fields\ListViewField;

class DistributorTaxForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces', 'feed_info'];
    public function getFieldsets()
    {
        return [[
            'taxes',
        ]];
    }

    public function getFields()
    {
        return [
            'taxes' => [
                'class' => ListViewField::class,
                'adminClass' => DxTaxesAdmin::class,
                'label' => 'Distributor charges us the following taxes:',
                'hintTemplate' => $this->hintTemplate,
            ]
        ];
    }
}