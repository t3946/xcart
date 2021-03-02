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
                'hint' => LanguageModel::translate('help_distributor_contact_contact_name_text')
            ],
            'distributor_field_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 300px'],
                'hint' => LanguageModel::translate('help_distributor_contact_field_name_text')
            ],
            'email' => [
                'class' => EmailField::class,
                'html' => ['style' => 'width: 200px'],
                'hint' => LanguageModel::translate('help_distributor_contact_email_text')
            ],
            'phone' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px'],
                'extend' => 'ext',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'hint' => LanguageModel::translate('help_distributor_contact_phone_text')
            ],
            'ext' => [
                'label' => '<b>ext</b>',
                'extends' => 'ext',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'class' => CharField::class,
                'html' => ['style' => 'width: 70px'],
            ],
            'fax' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px'],
                'hint' => LanguageModel::translate('help_distributor_contact_fax_text')
            ],
            'distributor' => [
                'class' => HiddenField::class
            ],
            'utility' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'html' => ['style' => 'width:100%'],
                'placeholder' => 'Click to select a function',
                'hint' => LanguageModel::translate('help_distributor_contact_utility_text')
            ]
        ];
    }

    public function getName()
    {
        return '';
    }

}