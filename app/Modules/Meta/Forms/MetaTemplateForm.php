<?php

namespace Modules\Meta\Forms;

use Modules\Meta\Models\MetaTemplate;
use Xcart\App\Form\Fields\TextField;
use Xcart\App\Form\ModelForm;

class MetaTemplateForm extends ModelForm
{
    public function getFieldsets()
    {
        return [
            'main' => [
                'code', 'title', 'description', 'keywords'
            ]
        ];
    }

    public function getModel()
    {
        return new MetaTemplate();
    }

    public function getFields()
    {
        return [
            'title' => [
                'class' => TextField::className(),
            ]
        ];
    }
}