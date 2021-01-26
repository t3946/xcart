<?php

namespace Modules\Translate\Forms;

use Modules\Translate\Models\LanguageModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;
use Xcart\App\Form\Fields\CharField;

class TranslatesFilterForm extends Form
{
    public function getFields()
    {
        return [
            'name' => [
                'class' => Select2Field::class,
                'multiple' => true,
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
                    'style' => 'width: 300px'
                ],
            ],
            'text' => [
                'class' => CharField::class,
                'label' => 'Search text',
                'html' => [
                    'style' => 'width: 300px',
                    'autocomplete' => 'off',
                ],
            ],
            "not_translated" => [
                'class' => CheckboxField::class,
                'label' => 'Not Translated',
            ],
            'case_sensitivity' => [
                'class' => CheckboxField::class,
                'label' => 'Case-sensitivity',
            ],
        ];
    }
}