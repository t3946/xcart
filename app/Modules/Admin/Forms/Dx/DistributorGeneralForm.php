<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Editor\Fields\EditorField;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\Fields\UrlField;
use Xcart\App\Main\Xcart;

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
            'disabled_reason',
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        $user = Xcart::app()->user;
        $provider = $dx->pk ? "{$dx->provider_model} ({$dx->provider})" : "{$user} ({$user->login})";
        $created = $dx->getField('created_at')->getValue();
        return [
            'provider_name' => [
                'class' => CharField::class,
                'label' => 'Added by',
                'hint' => LanguageModel::translate('help_dx_provider_text'),
                'html' => [
                    'style' => 'border: none; width: 100%'
                ],
                'value' => $provider . ($created !== null) ? " on {$created->format('d F Y')}" : '',
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
                'label' => 'Logo (jpg, png, svg formats only)',
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
                'label' => 'Distributor warehouse will be closed until',
                'html' => ['style' =>'width:100px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('dx_eta_date'),
            ],
            'avail' => [
                'class' => CheckboxField::class,
                'html' => ['style' =>'width:16px;', 'onchange' => "this.checked ? $('[id$=disabled_reason]').closest('tr').hide() : $('[id$=disabled_reason]').closest('tr').show()"],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'hint' => LanguageModel::translate('help_dx_activate_text'),
            ],
            'disabled_reason' => [
                'class' => TextAreaField::class,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'label' => 'Why distributor products are disabled?',
                'html' => ['style' =>'height:100px;'],
                'hint' => LanguageModel::translate('help_dx_disabled_reason_text') ?? 'help_dx_disabled_reason_text',
                'hidden' => $dx->avail ?? true
            ]
        ];
    }
}