<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Marketplace\Models\ExternalMarketPlaceModel;
use Xcart\App\Form\Fields\Select2Field;

class DistributorExcludedMarketplacesForm extends DistributorForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model'];

    public function getFieldsets()
    {
        return [[
            'disabled_marketplaces',
        ]];
    }

    public function getFields()
    {
        return [
            'disabled_marketplaces' => [
                'class' => Select2Field::class,
                'label' => 'Excluded marketplaces:',
                'placeholder' => 'Click to select',
                'multiple' => true,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:400px;']
            ],
        ];
    }
}