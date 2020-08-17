<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Models\ShareHolderModel;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\PercentField;
use Xcart\App\Form\ModelForm;

class ShareholderForm extends ModelForm
{
    public function getFieldsets()
    {
        return [[
            'name',
            'shares',
            'percent',
        ]];
    }

    public function getFields()
    {
        return [
            'shares' => [
                'class' => NumberField::class,
                'html' => ['style' => 'width: 100px;'],
            ],
            'percent' => [
                'class' => PercentField::class,
                'html' => ['style' => 'width: 70px;'],
            ]
        ];
    }

    public function getModel()
    {
        return new ShareHolderModel;
    }


    public function getName()
    {
        return 'Shareholder';
    }
}