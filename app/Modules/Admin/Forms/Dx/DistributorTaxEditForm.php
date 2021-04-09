<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorTaxModel;
use Modules\Sites\Models\TaxModel;
use Xcart\App\Form\Fields\DropDownField;
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
        $dx_taxes = $this->getInstance();
        $tax_excluded = $this->getModel()->getObjects()->filter(['distributor_id' => $dx_taxes->distributor_id])->valuesList(['tax_id'], true);
        return [
            'tax' => [
                'class' => DropDownField::class,
                'html' => ['style' => 'width: 400px;'],
                'choices' => function () use ($tax_excluded) {
                    $taxes = TaxModel::objects();
                    if ($tax_excluded) {
                        $taxes->exclude(['pk__in' => $tax_excluded]);
                    }
                    foreach ($taxes as $tax) {
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