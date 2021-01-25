<?php


namespace Modules\Goods\Forms;


use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class ProductVerificationForm extends ModelForm
{
    public function getFields()
    {
        return [
            'verification_status' => [
                'class' => DropDownField::class,
                'required' => true,
                'html' => [
                    'autocomplete' => 'off',
                    'title' => $this->getInstance()->getProductVerificationHistoryLastNote()
                ]
            ]
        ];
    }
}