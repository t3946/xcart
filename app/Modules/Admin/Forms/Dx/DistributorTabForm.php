<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorTabModel;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;

class DistributorTabForm extends ModelForm
{

    public function getModel()
    {
        return new DistributorTabModel();
    }

    public function getFields()
    {
        return [
            'content' => [
                'class' => TextAreaField::class,
                'html' => ['style' => 'width: 400px;']
            ],
            'distributor' => [
                'class' => HiddenField::class
            ]
        ];
    }
}