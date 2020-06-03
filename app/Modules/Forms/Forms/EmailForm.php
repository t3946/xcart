<?php


namespace Modules\Forms\Forms;


use Modules\Forms\Models\EmailModel;
use Xcart\App\Form\Fields\CharField;
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
            'snippet' => ['class' => CharField::class],
            'subject' => ['class' => CharField::class],
            'body' => CharField::class,
        ];
    }
}