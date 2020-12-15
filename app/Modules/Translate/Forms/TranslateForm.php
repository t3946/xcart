<?php

namespace Modules\Translate\Forms;

use Modules\Translate\Models\TranslateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\ModelForm;

class TranslateForm extends ModelForm
{
    public function getName(): string
    {
        return 'Edit Translate';
    }

    public function getFields()
    {
        return [
            'lang_code' => [
                'class' => HiddenField::class,
            ],
            'msgid' => [
                'class' => HiddenField::class,
            ],
            'msgctxt' => [
                'class' => CharField::class,
                'label' => 'Context',
                'required' => true,
                'html' => [
                    'required' => true,
                    'autocomplete' => 'off',
                ],
            ],
            'msgstr' => [
                'class' => CharField::class,
                'label' => 'Message',
                'html' => [
                    'autocomplete' => 'off',
                ],
            ],
        ];
    }

    public function getModel()
    {
        return new TranslateModel;
    }

    /**
     * form name
     */
    public function get()
    {
        return 'Language';
    }
}