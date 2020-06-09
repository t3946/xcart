<?php


namespace Modules\Forms\Forms;


use Modules\Editor\Fields\EditorField;
use Modules\Forms\Models\EmailModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\ModelForm;

class EmailForm extends ModelForm
{
    public function getModel()
    {
        return new EmailModel();
    }

    public function getFields()
    {
        return [
            'date' => ['class' => DateTimeField::class],
            'from_address' => ['class' => CharField::class, 'verboseName' => 'From'],
            'to_address' => ['class' => CharField::class, 'verboseName' => 'To'],
            'subject' => ['class' => CharField::class],
            'body' => ['class' => EditorField::class, 'verboseName' => ' '],
        ];
    }
}