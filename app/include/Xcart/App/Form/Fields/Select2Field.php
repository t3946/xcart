<?php

namespace Xcart\App\Form\Fields;

use Closure;
use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\JavaScript;
use Xcart\App\Helpers\JavaScriptExpression;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Translate\Translate;

/**
 * Class Select2Field
 * @package Mindy\Form
 */
class Select2Field extends DropDownField
{
    public array $options = [];

    public int $pageSize = 30;

    public string $modelField = 'name';

    public string $placeholder = 'Click to select value';

    public string $ajaxUrl = "";
    public bool $editable = false;

    public function getAjaxUrl()
    {
        if ($this->ajaxUrl instanceof Closure) {
            return $this->ajaxUrl->__invoke();
        }
        return $this->ajaxUrl;
    }

    public function render($fieldExtension = null)
    {
        $extension = implode("\n",
            [
                '<script type="text/javascript">',
                '$("#' . $this->getHtmlId() . '").select2(' . JavaScript::encode($this->getJSOptions()) . ')',
                '.on("select2:unselecting", function(e){if (!e.params.args.originalEvent) {return false;}e.params.args.originalEvent.stopPropagation();})',
                '</script>',
                '<style>
                    .select2-results__option[aria-selected=true] {display: none;}
                    .select2-search--inline {width: 100% !important;}
                    .select2-search--inline input {width: 100% !important;}
                </style>',
            ]
        );
        return parent::render($extension);
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

        if ($this->getChoices()) {
            $options = [
                'allowClear' => true,
                'placeholder' => Translate::getInstance()->t('form', $this->placeholder),
                'multiple' => $multiple,
                'width' => 'resolve',
                'tags' => $this->editable,
                'createTag' => new JavaScriptExpression('function (params) {
                      var term = $.trim(params.term);
                      if (term === \'\') return null;
                      return {
                          id: term,
                          text: term,
                          newTag: true
                      }
                }'),
            ];
        }

        if ($this->getAjaxUrl()) {
            $options = [
                'width' => 'resolve',
                'allowClear' => true,
                'multiple' => $multiple,
                'closeOnSelect' => !$multiple,
                'placeholder' => Translate::getInstance()->t('form', $this->placeholder),
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => $this->getAjaxUrl(),
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
        }
        return array_replace_recursive($options, $this->options);
    }

}
