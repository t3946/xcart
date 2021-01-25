<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorContactsModel;
use Xcart\App\Form\Fields\CharField;
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
                'html' => ['style' => 'width: 300px']
            ],
            'distributor_field_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 300px']
            ],
            'email' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px']
            ],
            'phone' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px'],
                'extend' => 'ext',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
            ],
            'ext' => [
                'label' => 'ext',
                'extends' => 'ext',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'class' => CharField::class,
                'html' => ['style' => 'width: 70px']
            ],
            'fax' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 200px']
            ],
            'distributor' => [
                'class' => HiddenField::class
            ],
            'utility' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'html' => ['style' => 'width:100%'],
            ]
        ];
    }

    public function getName()
    {
        return '';
    }

}