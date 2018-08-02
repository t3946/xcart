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
    /**
     * @return array
     */
    protected function fields(): array
    {
        return [
            'color' => [
                'class' => DropDownField::class,
//                'class' => FrontendColorField::class,
                'label' => $this->createTitle(),
                'choices' => $this->addFirstBlankOption(),
                'required' => true,
                'disabled' => [''],
                'selected' => ['']
            ]
        ];
    }
}