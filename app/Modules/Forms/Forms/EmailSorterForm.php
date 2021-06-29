<?php


namespace Modules\Forms\Forms;


use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Admin\EmailSorterAdmin;
use Modules\Forms\Models\EmailSorterModel;
use Modules\Goods\Admin\ProductAdmin;
use Modules\Order\Models\OrderModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\RelatedField;
use Xcart\App\Orm\Model;

class EmailSorterForm extends ModelForm
{
    public function getModel()
    {
        return new EmailSorterModel();
    }

    public function getFields()
    {
        $form = $this;
        $model = $this->getInstance();
        $ajax_url = (new EmailSorterAdmin)->getSuggestionUrl($form->getField('entity')->getValue() ?? DistributorModel::class);

        return [
            'filter_field' => [
                'class' => DropDownField::class
            ],
            'cond' => [
                'class' => DropDownField::class,
                'html' => [
                    'onchange' => "(function(e) {
                        if (e.value === 'related') {
                            $('#{$this->getName()}_related_value').closest('div').show(); $('#{$this->getName()}_value').closest('div').hide();
                        } else {
                            $('#{$this->getName()}_value').closest('div').show(); $('#{$this->getName()}_related_value').closest('div').hide();
                        }
                    })(this)"
                ],
            ],
            'value' => [
                'class' => CharField::class,
                'hidden' => $model->cond === 'related',
            ],
            'entity' => [
                'class' => DropDownField::class,
                'html' => [
                    'onchange' => "location=window.location.href.split('?')[0] + '?{$this->getName()}[entity]=' + this.value"
                ],
            ],
            'target' => [
                'class' => Select2Field::class,
                'choices' => static function () use ($form) {
                    $class = $form->getField('entity')->getValue() ?? DistributorModel::class;
                    $target = new $class;
                    return $form->getInstance()->target ?
                        [$form->getInstance()->target => $target::objects()->get([$target::getPrimaryKeyName() => $form->getInstance()->target])] : [];
                },
                'html' => [
                    'data-ajax-url' => $ajax_url,
                    'class' => 'select2-field',
                ]
            ],
            'related_value' => [
                'class' => DropDownField::class,
                'choices' => static function () use ($form) {
                    $class = $form->getField('entity')->getValue() ?? DistributorModel::class;
                    $model = new $class;
                    foreach ($model->getFieldsInit() as $f) {
                        if ($f instanceof RelatedField) {
                            foreach ((new $f->modelClass)->getFieldsInit() as $ff) {
                                if ($ff->getVerboseName()) {
                                    $res["{$f->getName()}__{$ff->getName()}"] = "{$f->getVerboseName()}->{$ff->getVerboseName()}";
                                }
                            }
                        } else {
                            if ($f->getVerboseName()) {
                                $res[$f->getName()] = $f->getVerboseName();
                            }
                        }
                    }
                    asort($res);
                    return $res;
                },
                'hidden' => $model->cond !== 'related',
            ]
        ];
    }

    public function setAttributes(array $data)
    {
        if ($data['cond'] !== 'related') {
            $data['related_value'] = null;
        } else {
            $data['value'] = null;
        }
        return parent::setAttributes($data);
    }
}