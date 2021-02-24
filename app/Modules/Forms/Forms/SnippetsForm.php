<?php


namespace Modules\Forms\Forms;


use Modules\Core\Fields\AceField;
use Modules\Editor\Fields\EditorField;
use Modules\Forms\Models\SnippetModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\ModelForm;

class SnippetsForm extends ModelForm
{
    public function getModel()
    {
        return new SnippetModel();
    }

    public function getFields()
    {
        return [
            'code' => ['class' => CharField::class, 'required' => true],
            'name' => ['class' => CharField::class, 'required' => true],
            'description' => CharField::class,
            'template' => [
                'class' => AceField::class,
                'language' => 'smarty',
                'label' => 'Template',
            ],
        ];
    }
}