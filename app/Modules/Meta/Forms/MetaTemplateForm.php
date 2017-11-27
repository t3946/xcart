<?php

namespace Modules\Meta\Forms;

use Modules\Core\Fields\AceField;
use Modules\Meta\Models\MetaTemplate;
use Xcart\App\Form\ModelForm;

class MetaTemplateForm extends ModelForm
{
    public function getFieldsets()
    {
        return [
            'Main' => [
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
                'class' => AceField::className(),
                'language' => 'smarty',
            ],
            'description' => [
                'class' => AceField::className(),
                'language' => 'smarty',
            ],
            'keywords' => [
                'class' => AceField::className(),
                'language' => 'smarty',
            ],
        ];
    }
}