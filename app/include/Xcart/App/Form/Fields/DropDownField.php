<?php

namespace Xcart\App\Form\Fields;

use Closure;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * Class DropDownField
 * @package Mindy\Form
 */
class DropDownField extends Field
{
    /**
     * @var array
     */
    public $choices = [];
    private $_selected = [];
    public array $depends = [];
    /**
     * Span tag needed because: http://stackoverflow.com/questions/23920990/firefox-30-is-not-hiding-select-box-arrows-anymore
     * @var string
     */
    public $inputTemplate = 'forms/field/dropdown/new_input.tpl';
    public $fieldType = 'dropdown';
    /**
     * @var bool
     */
    public $multiple = false;
    /**
     * @var string
     */
    public $empty = '';
    public bool $not_assigned = false;
    /**
     * @var array
     */
    public $disabled = [];
    public $selected = [];

    public function getCommonData()
    {
        return [
            'disabled' => $this->disabled,
        ];
    }

    public function getSelected()
    {
        return $this->_selected;
    }

    public function getAttributesInput()
    {
        $params = [];
        if ($this->multiple) {
            $params['multiple'] = 'multiple';
        }

        return array_replace_recursive(parent::getAttributesInput(), $params);
    }

    public function getHtmlName()
    {
        $name = $this->getPrefix() . '[' . $this->name . ']';

        if ($this->multiple) {
            $name .= '[]';
        }

        return $name;
    }


    public function getChoices()
    {
        $data = [];
        $selected = [];
        $choices = [];

        if ($this->choices) {
            $choices = $this->choices;
        } elseif ($this->getForm() instanceof ModelForm) {
            $choices = $this->getForm()->getInstance()->getField($this->name)->choices;
        }

        if ($choices) {
            if ($choices instanceof Closure) {
                $data = $choices->__invoke();
            } else {
                $data = $choices;
            }

            $value = $this->getValue();
            if ($value !== null) {
                if ($value instanceof Manager) {
                    $selected = $value->valuesList(['pk'], true);
                } elseif ($value instanceof Model) {
                    $selected[] = $value->pk;
                } elseif (is_array($value)) {
                    $selected = $value;
                } else {
                    $selected[] = $value;
                }
            }

            if ($this->form instanceof ModelForm
                && ($model = $this->getForm()->getInstance())
                && $field = $model->getField($this->name)) {
                if ($field->null && !$this->multiple) {
                    $data = ['' => ''] + $data;
                }

                if (is_a($field, ForeignField::class)) {
                    $from = $field->getFrom();
                    $to = $field->getTo();
                    $related = $model->{$from};
                    if ($related) {
                        $selected[] = $related;
                    }
                } elseif (is_a($field, ManyToManyField::class)) {
                    $this->multiple = true;

                    $selectedTmp = $field->getManager()->all();
                    foreach ($selectedTmp as $model) {
                        $selected[] = $model->pk;
                    }
                } elseif ($model->hasAttribute($this->name)) {
                    $selected[] = $model->{$this->name};
                }
            } elseif ($this->getForm() instanceof Form) {
                if ($selected !== null && !is_array($selected)) {
                    $selected = [$selected];
                }
            }
        } elseif ($this->form instanceof ModelForm
            && $this->form->getModel()->hasField($this->name)
            && ($model = $this->form->getModel())
            && $field = $model->getField($this->name)
        ) {
            if (is_a($field, ManyToManyField::className())) {
                $this->multiple = true;

                $modelClass = $field->modelClass;
                $models = $modelClass::objects()->all();

                if ($value = $this->getValue()) {
                    if ($value instanceof Manager) {
                        $selectedTmp = $value->all();
                        foreach ($selectedTmp as $item) {
                            $selected[] = $item->pk;
                        }
                    } else {
                        $selected = is_array($value) ? $value : [$value];
                    }
                }

                $this->_attributes['multiple'] = 'multiple';

                foreach ($models as $item) {
                    $data[$item->pk] = (string)$item;
                }
            } elseif (is_a($field, HasManyField::class)) {
                $this->multiple = true;

                $modelClass = $field->modelClass;
                $models = $modelClass::objects()->all();

                $this->_attributes['multiple'] = 'multiple';

                foreach ($models as $item) {
                    $data[$item->pk] = (string)$item;
                }
            } elseif (is_a($field, ForeignField::class)) {
                //@TODO: CHECK FOR CORRECTLY;
                /** @var ForeignField $from */
                $from = $field->getFrom();
                $to = $field->getTo();

                $modelClass = $field->modelClass;
                $qs = $modelClass::objects();
                if (get_class($model) == $modelClass && $model->getIsNewRecord() === false) {
                    $qs = $qs->exclude([$to => $model->{$to}]);
                }
                /* @var $modelClass Model */
                if (!$this->required) {
                    $data[''] = $this->empty;
                }
                if (($value = $this->getValue()) !== null) {
                    $selected[] = $value instanceof Model ? $value->{$to} : $value;
                }
                foreach ($qs->all() as $item) {
                    $data[$item->{$to}] = (string)$item;
                }
            } else {
                $data = $this->getValue();
            }
        } else {
            $data = $this->getValue();
        }

        if ($this->multiple) {
            if (!$this->empty) {
                $selected = array_filter($selected);
            }
            $this->_attributes['multiple'] = 'multiple';
        }

        $this->_selected = $this->selected ?: $selected;

        return $data;
    }
}
