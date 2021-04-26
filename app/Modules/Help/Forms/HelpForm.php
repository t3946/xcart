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

    public function getFields()
    {
        return [
          'items' => [
               'class' => ListViewField::class,
               'adminClass' => HelpItemsAdmin::class,
               ]
        ];
    }
}