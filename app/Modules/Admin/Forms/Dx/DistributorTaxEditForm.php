<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorTaxModel;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class DistributorTaxEditForm extends ModelForm
{
    public function getModel()
    {
        return new DistributorTaxModel();
    }

    public function getFields()
    {
        return [
            'tax' => [
                'class' => Select2Field::class,
                'html' => ['style' => 'width: 400px;']
            ],
            'distributor' => [
                'class' => HiddenField::class
            ]
        ];
    }
}