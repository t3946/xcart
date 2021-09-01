<?php

namespace Xcart\App\Form\Fields;

/**
 * Class RadioField
 * @package Mindy\Form
 */
class RadioField extends CharField
{
    public $template = "<input type='{type}' id='{id}' value='{value}' name='{name}'{html}/>";

    public $templateWithChoices = 'forms/field/default/radio_choices.tpl';
    public $templateWithoutChoices = 'forms/field/default/radio_no_choices.tpl';

    public $type = "radio";

    /*public function render()
    {

        $template = empty($this->choices) ? $this->templateWithoutChoices : $this->templateWithChoices;

        return $this->innerRender($template, [
            'input' => $this->renderInput(),
            'label' => $this->renderLabel(),
            'hint' => $this->hintToTemplate(),
            'errors' => $this->renderErrors(),
            'field' => $this,
            'name' => $this->getHtmlName(),
        ]);

    }*/

    private function hintToTemplate(){
        return $this->hint ? $this->renderHint() : '';
    }

    public function renderInput()
    {
        if (!empty($this->choices)) {
            $inputs = [];
            $i = 0;
            foreach ($this->choices as $value => $labelStr) {
                $label = strtr("<label for='{for}'>{label}</label>", [
                    '{for}' => $this->getHtmlId() . '_' . $i,
                    '{label}' => '<span>' . $labelStr . '</span>'
                ]);

                $checked = false;
                if (is_array($this->value)) {
                    foreach ($this->value as $v) {
                        if ($v == $value) {
                            $checked = true;
                        }
                    }
                } else {
                    if ($this->value == $value) {
                        $checked = true;
                    }
                }

                $input = $this->renderInputInternal($this->getHtmlId() . '_' . $i, $value,  ($checked ? " checked='checked'" : ''));
                $i++;
                $hint = $this->hint ? $this->renderHint() : '';
                $inputs[] = "{$input}{$label}{$hint}";
            }
            if ($this->extend && $inputs) {
                $ex = " {$this->getForm()->getField($this->extend)->renderInput()}";
                $inputs[count($inputs)-1] .= $ex;
            }
            $inputs = array_map(function($a){return "<div class='radio-container'>{$a}</div>";}, $inputs);

            return implode("\n", $inputs);
        } else {
            if ($this->value) {
               $checked = 'checked';
            }
            $input = $this->renderInputInternal($this->getHtmlId(), 1, ($checked ? " checked='checked'" : ''));
            return implode("\n", [
                //"<input type='hidden' value='' name='" . $this->getHtmlName() . "' />",
                $input,
            ]);
        }
    }

    protected function renderInputInternal($id, $value, $html = '')
    {
        return strtr($this->template, [
            '{type}' => $this->type,
            '{id}' => $id,
            '{name}' => $this->getHtmlName(),
            '{value}' => $value,
            '{html}' => $this->buildAttributesInput() . $html
        ]);
    }
}
