<?php

namespace Xcart\App\Form\Fields;

use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\JavaScript;
use Xcart\App\Helpers\JavaScriptExpression;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\RelatedField;
use Xcart\App\Translate\Translate;

/**
 * Class Select2Field
 * @package Mindy\Form
 */
class Select2Field extends DropDownField
{
    public $options = [];

    public $pageSize = 30;

    public $modelField = 'name';

    public $placeholder = 'Please select value';

    public $ajaxUrl = "";

    public function render()
    {
        $label = $this->renderLabel();

        $hint = $this->hint ? $this->renderHint() : '';
        $errors = $this->renderErrors();
        $name = $this->getHtmlName();

        $data = [];
        $s_options = [];
        $choices = $this->getChoices();
        $selected = $this->getSelected();

        foreach ($choices as $pk => $name) {
            $data[] =  ['id' => $pk, 'text' => (string)$name];

            if (in_array($pk, $selected)) {
                $s_options[] = "<option value='{$pk}'>{$name}</option>";
            }
        }

        $s_options = implode('',$s_options);

        $out = implode("\n", [
            $label,
            "<select id='{$this->getHtmlId()}' name='{$name}'>{$s_options}</select>",
            $hint,
            $errors,
            '<script type="text/javascript">',
            '$("#' . $this->getHtmlId() . '").select2(' . JavaScript::encode($this->getJSOptions()) . ');',
            empty($data) ? '' : '$("#' . $this->getHtmlId() . '").select2("data", ' . JavaScript::encode($data) . ');',
            '</script>',
        ]);

        return $out;
    }

    public function getJSOptions()
    {

        $form = $this->getForm();

        if ($form instanceof ModelForm) {

            $model = $this->getForm()->getModel();
            $modelField = $model->getField($this->name);
            $multiple = $modelField instanceof ManyToManyField || $modelField instanceof HasManyField || $this->multiple;
        }
        else {
            $multiple = $this->multiple;
        }


        $options = [
            'width' => 'resolve',
            'allowClear' => true,
            'multiple' => $multiple,
            'closeOnSelect' => !$multiple,
            'placeholder' => Translate::getInstance()->t('form', $this->placeholder),
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => $this->ajaxUrl,
                'dataType' => 'json',
                'delay' => 250,
                'processResults' => new JavaScriptExpression('function (data, page) {
                    if (data) {
                        return {
                            results: data.items,
//                            more: (page * 30) < data.total_count
                        };
                    }
                    return { results: { } };
                    
                }'),
            ],
            'escapeMarkup' => new JavaScriptExpression('function (m) {
                return m;
            }'),
        ];

        return array_replace_recursive($options, $this->options);
    }
}
