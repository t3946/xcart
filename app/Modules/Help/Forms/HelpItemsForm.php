<?php


namespace Modules\Help\Forms;


use Modules\Editor\Fields\EditorField;
use Modules\Help\Models\HelpMenuContentModel;
use Xcart\App\Form\ModelForm;

class HelpItemsForm extends ModelForm
{
    public array $exclude = ['menu'];

    public function getModel()
    {
        return new HelpMenuContentModel();
    }
    public function getFieldsets()
    {
        return [[
            'form_type',
            'question',
            'answer',
        ]];
    }

    public function getFields()
    {
        return [
            'answer' => [
                'class' => EditorField::class,
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
        ];
    }
}