<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\OptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\CharField;

class OptionAdminForm extends ModelForm
{
    //public $exclude = ['name'];

    public function getModel()
    {
        return new OptionNewModel();
    }

    public function getFields()
    {
        return [
            'title' => [
                'class' => CharField::class,
                'value' => 'Test'
            ],
            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => OptionVariantsAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl'
            ]
        ];
    }
}