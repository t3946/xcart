<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Editor\Fields\EditorField;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\UrlField;

class DistributorGeneralForm extends DistributorForm
{
    public function getFieldsets()
    {
        return [[
            'provider_name',
            'manufacturer',
            'code',
            'url',
            'logo',
            'sites',
            'd_specific_instructions',
            'dx_eta_date',
            'avail',
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        return [
            'provider_name' => [
                'class' => CharField::class,
                'label' => 'Added by',
                'hint' => LanguageModel::translate('help_dx_provider_text'),
                'html' => [
                    'style' => 'border: none;'
                ],
                'value' => "{$dx->provider_model} ({$dx->provider})",
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'manufacturer' => [
                'class' => CharField::class,
                'label' => 'Distributor company name',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_comapny_name_text'),
                'required' => true,
            ],
            'code' => [
                'class' => CharField::class,
                'label' => 'Distributor prefix',
                'html' => ['style' =>'width:100px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_prefix_text'),
                'required' => true,
            ],
            'url' => [
                'class' => UrlField::class,
                'label' => 'Distributor website URL (main page)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_website_text'),
                'extend' => 'Website'
            ],
            'logo' => [
                'class' => ImageField::class,
                'label' => 'Logo',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_logo_text'),
            ],
            'sites' => [
                'class' => Select2Field::class,
                'label' => 'Main SF',
                'multiple' => true,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_site_text'),
                'required' => true
            ],
            'd_specific_instructions' => [
                'class' => EditorField::class,
                'label' => 'Distributor notes for dispatcher (Dx notes)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_instructions_text'),
            ],
            'dx_eta_date' => [
                'class' => DateField::class,
                'label' => 'Dx warehouse is closed until',
                'html' => ['style' =>'width:100px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('dx_eta_date'),
            ],
            'avail' => [
                'class' => CheckboxField::class,
                'html' => ['style' =>'width:16px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_activate_text'),
            ]
        ];
    }
}