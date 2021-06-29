<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Modules\Distributor\Models\DistributorContactsModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class DistributorContactsForm extends ModelForm
{
    public array $exclude = ['pq'];
    public $hintTemplate = 'admin/distributor/form/hint.tpl';

    public function getFieldsets()
    {
        return [[
            'contact_name',
            'distributor_field_name',
            'email',
            'phone',
            'fax',
            'distributor',
            'utility'
        ]];
    }

    public function getModel()
    {
        return new DistributorContactsModel();
    }

    public function getFields()
    {
        return [
            'contact_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 300px'],
                'hint' => LanguageModel::translate('help_distributor_contact_contact_name_text'),
                'hintTemplate' => $this->hintTemplate,
            ],
            'distributor_field_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 300px'],
                'hint' => LanguageModel::translate('help_distributor_contact_field_name_text'),
                'hintTemplate' => $this->hintTemplate,
            ],
            'email' => [
                'class' => EmailField::class,
                'html' => ['style' => 'width: 200px'],
                'hint' => LanguageModel::translate('help_distributor_contact_email_text'),
                'hintTemplate' => $this->hintTemplate,
            ],
            'phone' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px'],
                'extend' => 'ext',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'hint' => LanguageModel::translate('help_distributor_contact_phone_text'),
                'hintTemplate' => $this->hintTemplate,
            ],
            'ext' => [
                'extends' => '<b>ext</b>',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'class' => CharField::class,
                'html' => ['style' => 'width: 70px'],
            ],
            'fax' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px'],
                'hint' => LanguageModel::translate('help_distributor_contact_fax_text'),
                'hintTemplate' => $this->hintTemplate,
            ],
            'distributor' => [
                'class' => HiddenField::class
            ],
            'utility' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width:100%',
                    'class' => 'select2-field',
                    'data-placeholder' => 'Click to select a function',
                ],
                'multiple' => true,
                'hint' => LanguageModel::translate('help_distributor_contact_utility_text'),
                'hintTemplate' => $this->hintTemplate,
            ]
        ];
    }

    public function getName()
    {
        return '';
    }

}