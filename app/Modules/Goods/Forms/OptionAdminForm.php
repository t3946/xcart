<?php

namespace Modules\Goods\Forms;


use Modules\Goods\Admin\OptionVariantsAdmin;
use Modules\Goods\Models\OptionNewModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\CharField;

class OptionAdminForm extends ModelForm
{

    public function getModel()
    {
        return new OptionNewModel();
    }

    public function getFields()
    {
        return [

            'type' => [
                'class' => DropDownField::class,
                'choices' => [
                    'color' => 'Color',
                    'select' => 'Select Box',
                    'radio' => 'Radio Box',
                ],
                'required' => true,
            ],
            'variants' => [
                'class' => ListViewField::class,
                'adminClass' => OptionVariantsAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl'
            ]
        ];
    }
}