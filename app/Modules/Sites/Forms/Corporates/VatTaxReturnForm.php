<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Models\TaxReturnModel;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class VatTaxReturnForm extends TaxReturnForm
{
    public function getFields()
    {
        return array_replace(parent::getFields(), [
            'tax_type' => [
                'class' => DropDownField::class,
                'selected' => ['VAT'],
            ]
        ]);
    }
}