<?php


namespace Modules\Help\Forms;


use Modules\Help\Models\HelpListModel;
use Xcart\App\Form\ModelForm;

class HelpForm extends ModelForm
{
    public function getModel()
    {
        return new HelpListModel();
    }
}