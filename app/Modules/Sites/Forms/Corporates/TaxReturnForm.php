<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Models\TaxReturnModel;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\ModelForm;

class TaxReturnForm extends ModelForm
{
    public function getFieldsets()
    {
        return [[
            'tax_type',
            'from_date',
            'to_date',
            'status',
        ]];
    }

    public function getFields()
    {
        return [
            'from_date' => DateField::class,
            'to_date' => DateField::class,
        ];
    }

    public function getModel()
    {
        return new TaxReturnModel;
    }

    public function getName()
    {
        return 'Tax return period';
    }
}