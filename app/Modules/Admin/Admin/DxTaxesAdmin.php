<?php


namespace Modules\Admin\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Admin\Forms\Dx\DistributorTaxEditForm;
use Modules\Distributor\Models\DistributorTaxModel;
use Modules\Sites\Models\TaxModel;
use Xcart\App\Orm\Model;

class DxTaxesAdmin extends ListViewAdmin
{
    public $ownerField = 'distributor';

    public function getListColumns()
    {
        return [
            'tax_name',
            'is_vat',
            'tax_rate',
            'zone'
        ];
    }

    public function getForm()
    {
        return new DistributorTaxEditForm();
    }

    public function getModel()
    {
        return new DistributorTaxModel();
    }

    public function getItemProperty(Model $item, $property)
    {
        $tax = $item->tax;
        $rates = $tax->rates->all();
        /** @var TaxModel $item */
        switch ($property) {
            case 'tax_name':
                return (string)$tax;
            case 'is_vat':
                return $tax->$property ? 'VAT' : 'Sales';
            case 'tax_rate':
                return implode('<br/>', array_map(static fn($r) => $r->rate_value . $r->rate_type, $rates));
            case 'zone':
                return implode('<br/>', array_map(static fn($r) => $r->zone, $rates));
        }
        return parent::getItemProperty($item, $property);
    }

    public function getAvailableListColumns()
    {
        return [
            'tax_name' => [
                'title' => 'Tax name',
            ],
            'is_vat' => [
                'title' => 'Tax type',
            ],
            'tax_rate' => [
                'title' => 'Tax rate',
            ],
            'zone' => [
                'title' => 'Shipping zones where tax is charged',
            ],
        ];
    }

}