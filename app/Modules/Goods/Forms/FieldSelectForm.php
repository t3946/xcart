<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 14:19
 */

namespace Modules\Goods\Forms;


use Modules\Core\Fields\FrontendVariationSelect;

class FieldSelectForm extends DecoratedProductForm
{

    public $type = 'select';
    /**
     * @return array
     */
    protected function fields(): array
    {
        return [
            'sizes' => [
                'class' => FrontendVariationSelect::class,
                'label' => $this->title,
                'choices' => $this->variants,
                'required' => true,
            ]
        ];
    }
}