<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\ShareholderForm;
use Modules\Sites\Models\ShareHolderModel;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\PercentField;

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
}