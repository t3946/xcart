<?php


namespace Modules\Help\Forms;


use Modules\Help\Admin\HelpItemsAdmin;
use Modules\Help\Models\HelpListModel;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class HelpForm extends ModelForm
{
    public function getModel()
    {
        return new HelpListModel();
    }

    public function getFieldsets()
    {
        return [[
            'icon',
            'active_icon',
            'title',
            'menu_items',
        ]];
    }

    public function getFields()
    {
        return [
            'menu_items' => [
                'class' => ListViewField::class,
                'adminClass' => HelpItemsAdmin::class,
                'defaultOrder' => 'order_by'
            ]
        ];
    }
    public function getName()
    {
        return '';
    }
}