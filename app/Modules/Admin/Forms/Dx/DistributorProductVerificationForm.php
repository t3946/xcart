<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;

class DistributorProductVerificationForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'products_always_verify',
            'days_before_verify',
        ]];
    }

    public function getFields()
    {
        return [
            'products_always_verify' => [
                'class' => CheckboxField::class,
                'label' => 'Tick the checkbox if product verification is NOT required',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:1em;'],
            ],
            'days_before_verify' => [
                'class' => CharField::class,
                'label' => 'How long [in days] product verification remains valid?',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:100px;'],
            ],
        ];
    }
}