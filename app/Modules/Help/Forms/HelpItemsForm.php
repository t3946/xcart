<?php


namespace Modules\Help\Forms;


use Modules\Help\Models\HelpMenuContentModel;
use Xcart\App\Form\ModelForm;

class HelpItemsForm extends ModelForm
{
    public array $exclude = ['menu'];

    public function getModel()
    {
        return new HelpMenuContentModel();
    }
}