<?php


namespace Modules\Forms\Forms;


use Modules\Forms\Models\EmailSorterModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class EmailSorterForm extends ModelForm
{
    public function getModel()
    {
        return new EmailSorterModel();
    }

    public function getFields()
    {
        return [
            'filter_field' => ['class' => DropDownField::class, 'verboseName' => 'Filter'],
            'entity' => ['class' => DropDownField::class, 'verboseName' => 'Entity'],
        ];
    }
}