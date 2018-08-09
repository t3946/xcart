<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 14:20
 */

namespace Modules\Goods\Forms;


use Modules\Core\Fields\FrontendVariationRadio;
use Xcart\App\Form\Fields\RadioField;

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
                'class' => RadioField::class,
//                'class' => FrontendVariationRadio::class,
                'label' => $this->createTitle(),
                'choices' => $this->variants,
                'required' => true,
                'requiredMessage' => $this->createRequiredMessage(),
            ]
        ];
    }
}