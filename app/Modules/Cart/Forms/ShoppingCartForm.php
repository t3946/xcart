<?php


namespace Modules\Cart\Forms;

use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Orm\Fields\IntField;

class ShoppingCartForm extends BaseForm
{
    public function getFields()
    {
        return [
            'id' => [
                'class' => NumberField::className(),
                'label' => 'Cart ID',
                'attributes' => [
                    'pattern' => '\d+',
                    'placeholder' => 'Numbers only'
                ]
            ],
        ];
    }
}