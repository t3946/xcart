<?php


namespace Modules\Forms\Forms;


use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Models\EmailSorterModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\RelatedField;

class EmailSorterForm extends ModelForm
{
    private static $entinty_models = [
        OrderModel::class => OrderModel::class,
        DistributorModel::class => DistributorModel::class
    ];

    public function getModel()
    {
        return new EmailSorterModel();
    }

    public function getFields()
    {
        $form = $this;
        $model = $this->getInstance();
        return [
            'filter_field' => [
                'class' => DropDownField::class
            ],
            'entity' => [
                'class' => DropDownField::class,
                'html' => [
                    'onchange' => "location=window.location.href.split('?')[0] + '?{$this->getName()}[entity]=' + this.value"
                ],
            ],
            'cond' => [
                'class' => DropDownField::class,
                'html' => [
                    'onchange' => "(function(e) {
                        console.log(e.value);
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