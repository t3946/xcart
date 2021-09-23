<?php

namespace Modules\Meta\Forms;

use Modules\Meta\MetaModule;
use Modules\Meta\Models\Meta;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\Fields\TextField;
use Xcart\App\Form\ModelForm;

class MetaCreateForm extends ModelForm
{
    /**
     * @return array
     */
    public function getFields()
    {
        return [
            'is_custom' => [
                'class' => CheckboxField::className(),
                'label' => 'Is custom',
            ],
            'title' => [
                'class' => TextField::className(),
                'label' => 'Title'
            ],
            'description' => [
                'class' => TextAreaField::className(),
                'label' => 'Description'
            ],
            'keywords' => [
                'class' => TextAreaField::className(),
                'label' => 'Keywords'
            ],
        ];
    }

    public function getModel()
    {
        return new Meta;
    }
}
