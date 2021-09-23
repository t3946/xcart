<?php

namespace Modules\Translate\Forms;

use Modules\Translate\Models\LanguageModel;
use Modules\Translate\Models\TranslateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class TranslateCreateForm extends ModelForm
{
    public array $exclude = [ 'msgid' ];

    public function getName(): string
    {
        return 'Edit Translate';
    }

    public function getFields()
    {
        return [
            'lang_code' => [
                'class' => DropDownField::class,
                'label' => 'Language',
                'choices' => function () {
                    /**
                     * @var LanguageModel $language
                     */
                    foreach ( LanguageModel::objects()->order( [ 'lang_code' ] ) as $language ) {
                        $languages[ $language->lang_code ] = $language->name;
                    }

                    return $languages ?? [];
                },
                'html' => [
                    'style' => 'width: 300px',
                ],
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
                'required' => true,
                'html' => [
                    'required' => true,
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