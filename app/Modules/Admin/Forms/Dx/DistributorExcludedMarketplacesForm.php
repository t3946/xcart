<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\Select2Field;

class DistributorExcludedMarketplacesForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'taxes', 'feed_info'];

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
                'label' => 'Forbidden API interactions',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_disabled_marketplaces_text'),
                'html' => [
                    'style' => 'width:400px;',
                    'data-placeholder' => 'Click to select',
                ],
                'multiple' => true,
            ],
        ];
    }
}