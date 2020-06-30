<?php


namespace Modules\Forms\Forms;


use Modules\Editor\Fields\EditorField;
use Modules\Forms\Models\EmailModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\Fields\FileField;
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
            'from_address' => ['class' => CharField::class, 'verboseName' => 'From'],
            'to_address' => ['class' => CharField::class, 'verboseName' => 'To'],
            'subject' => ['class' => CharField::class],
            'attachments' => ['class' => FileField::class],
            'body' => ['class' => EditorField::class, 'verboseName' => ' '],
            'date' => ['class' => DateTimeField::class],
        ];
    }
}