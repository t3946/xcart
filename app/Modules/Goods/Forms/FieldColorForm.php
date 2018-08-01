<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 14:18
 */

namespace Modules\Goods\Forms;


use Modules\Core\Fields\FrontendColorField;

class FieldColorForm extends DecoratedProductForm
{

    public $type = 'color';
    /**
     * @return array
     */
    protected function fields(): array
    {
        return [
            'color' => [
                'class' => FrontendColorField::class,
                'label' => $this->title,
                'choices' => $this->variants,
                'required' => true,
            ]
        ];
    }
}