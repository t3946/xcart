<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\ShareholderForm;
use Modules\Sites\Models\ShareHolderModel;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\PercentField;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class ShareHolderAdmin extends ListViewAdmin
{
    public $ownerField = 'corporate';

    public function getListColumns()
    {
        return ['name', 'shares', 'percent'];
    }

    public function getAvailableListColumns()
    {
        return [
            'name' => [
                'title' => 'Company/Person name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'name'
            ],
            'shares' => [
                'class' => NumberField::class,
                'title' => 'Shares',
                'template' => $this->columnDefaultTemplate,
            ],
            'percent' => [
                'class' => PercentField::class,
                'title' => 'Percentage',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getForm()
    {
        return new ShareholderForm;
    }

    public function getModel()
    {
        return new ShareHolderModel;
    }

    public static function getName()
    {
        return 'Shareholders';
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'percent') {
            return parent::getItemProperty($item, $property) . "%";
        }
        return parent::getItemProperty($item, $property);
    }

    public function getAllUrl()
    {
        $admin = new CorporatesAdmin;
        $admin->section = 'shareholders';
        if ($this->ownerPk->id) {
            return $admin->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return $admin->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();
    }


}