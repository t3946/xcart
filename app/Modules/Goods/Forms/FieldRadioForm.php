<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 14:20
 */

namespace Modules\Goods\Forms;


use Modules\Core\Fields\FrontendVariationRadio;

class FieldRadioForm extends DecoratedProductForm
{

    public $type = 'radio';
    /**
     * @return array
     */
    protected function fields(): array
    {
        return [
            'name' => [
                'class' => FrontendVariationRadio::class,
                'label' => $this->title,
                'choices' => $this->variants,
                'required' => true,
            ]
        ];
    }
}