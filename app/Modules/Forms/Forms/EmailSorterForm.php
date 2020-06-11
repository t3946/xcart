<?php


namespace Modules\Forms\Forms;


use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Models\EmailSorterModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class EmailSorterForm extends ModelForm
{
    private static $entinty_models = [
        'order' => OrderModel::class,
        'dx' => DistributorModel::class
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
            'condition' => [
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
                'hidden' => $model->condition === 'related',
            ],
            'related_value' => [
                'class' => DropDownField::class,
                'choices' => static function () use ($form) {
                    $class = static::$entinty_models[$form->getField('entity')->getValue()];
                    $model = new $class;
                    foreach ($model->getFieldsInit() as $f) {
                        if ($f->getVerboseName()) {
                            $res[$f->getName()] = $f->getVerboseName();
                        }
                    }
                    sort($res);
                    return $res;
                },
                'hidden' => $model->condition !== 'related',
            ]
        ];
    }
}