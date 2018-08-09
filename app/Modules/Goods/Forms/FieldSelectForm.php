<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 14:19
 */

namespace Modules\Goods\Forms;


use Modules\Core\Fields\FrontendVariationSelect;
use Xcart\App\Form\Fields\DropDownField;

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
//                'class' => FrontendVariationSelect::class,
                'class' => DropDownField::class,
                'label' => $this->createTitle(),
                'choices' => $this->addFirstBlankOption(),
                'required' => true,
                'requiredMessage' => $this->createRequiredMessage(),
                'disabled' => [''],
                'selected' => ['']
            ]
        ];
    }

    /**
     * @return string
     */
    protected function createPlaceholder(): string
    {
        return 'Choose a ' . lcfirst($this->title);
    }

    /**
     * @return string
     */
    protected function addFirstBlankOption(): array
    {
        return array_merge(['' => $this->createPlaceholder()], $this->variants);
    }
}