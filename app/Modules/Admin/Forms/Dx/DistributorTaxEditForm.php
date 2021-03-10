<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorTaxModel;
use Modules\Sites\Models\TaxModel;
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
        /** @var DistributorTaxModel $dx_taxes */
        $dx_taxes = $this->getInstance()->getObjects();
        return [
            'tax' => [
                'class' => Select2Field::class,
                'html' => ['style' => 'width: 400px;'],
                'choices' => function () use ($dx_taxes) {
                    foreach (TaxModel::objects()->exclude(['pk__in' => $dx_taxes->valuesList(['tax_id'], true)]) as $tax) {
                        $result[$tax->taxid] = (string)$tax;
                    }
                    return $result ?? [];
                }
            ],
            'distributor' => [
                'class' => HiddenField::class
            ]
        ];
    }

}