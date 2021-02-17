<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\TaxRatesForm;
use Modules\Sites\Models\TaxRatesModel;
use Xcart\App\Orm\Model;

class TaxRatesAdmin extends ListViewAdmin
{
    public static $public = false;
    public $ownerField = 'tax';

    public function getListColumns()
    {
        return ['zone', 'rate_value'];
    }

    public function getAvailableListColumns()
    {
        return [
            'zone' => [
                'title' => 'Zone',
                'template' => $this->columnDefaultTemplate,
            ],
            'rate_value' => [
                'title' => 'Rate value',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm()
    {
        return new TaxRatesForm();
    }

    public function getModel()
    {
        return new TaxRatesModel;
    }

    public static function getName()
    {
        return 'Tax rates';
    }

    public function getAllUrl()
    {
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return (new TaxesAdmin)->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();

    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'rate_value') {
            return "{$item->$property}{$item->rate_type}";
        }
        return parent::getItemProperty($item, $property);
    }

    public function getListGroupActions()
    {
        return ['add'];
    }
}