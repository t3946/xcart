<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Sites\Forms\TaxesForm;
use Modules\Sites\Models\TaxModel;
use Xcart\App\Orm\Model;

class TaxesAdmin extends Admin
{
    public ?string $sort = 'position';

    public function getListColumns(): array
    {
        return ['tax_name', 'apply_to', 'address_type', 'is_vat'];
    }

    public function getAvailableListColumns()
    {
        return [
            'tax_name' => [
                'title' => 'Tax',
                'template' => $this->columnDefaultTemplate,
                'order' => 'tax_name'
            ],
            'apply_to' => [
                'title' => 'Apply To',
                'template' => $this->columnDefaultTemplate,
            ],
            'address_type' => [
                'title' => 'Rates depend on',
                'template' => $this->columnDefaultTemplate,
            ],
            'is_vat' => [
                'title' => 'VAT',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        if (in_array($property, ['apply_to', 'address_type'], true)) {
            return $item->getField($property)->toText();
        }
        return parent::getItemProperty($item, $property);
    }

    public function getForm(): TaxesForm
    {
        return new TaxesForm();
    }

    public function getModel(): TaxModel
    {
        return new TaxModel;
    }

    public static function getName()
    {
        return 'Taxes';
    }

    public function getListGroupActions()
    {
        return ['add'];
    }


    public function isAjaxCreate(): bool
    {
        return true;
    }
}