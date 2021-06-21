<?php


namespace Modules\Distributor\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\Model;

class DistributorAdmin extends Admin
{
    use AdminTrait;

    public function getListColumns()
    {
        return [
            'manufacturer',
            'code',
            'sites',
            'products',
            'active_products',
            'feed',
            'feed_source',
            'active',
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch($property) {
            case 'products' :
                return 1000;
        }
        return parent::getItemProperty($item, $property);
    }

    public function getForm()
    {

    }

    public function getModel()
    {
        return new DistributorModel();
    }

}