<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 14:18
 */

namespace Modules\Goods\Forms;


use Modules\Core\Fields\FrontendColorField;
use Xcart\App\Form\Fields\DropDownField;

class FieldColorForm extends FieldSelectForm
{

    public $type = 'color';
    protected $fieldName = 'color';
    /**
     * @return array
     */
    protected function fields(): array
    {
        return [
            $this->fieldName => [
                'class' => DropDownField::class,
//                'class' => FrontendColorField::class,
                'label' => $this->createTitle(),
                'choices' => $this->addFirstBlankOption(),
                'requiredMessage' => $this->createRequiredMessage(),
                'required' => true,
                'disabled' => [''],
                'selected' => [''],
//                'className' => $this->type
                'html' => [
                    'class' => $this->type
                ]
            ]
        ];
    }
}