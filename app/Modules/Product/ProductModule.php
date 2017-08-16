<?php
namespace Modules\Product;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class ProductModule extends Module
{
    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addAccessorSmart('get_warehouse', self::className()."::getWarehouse", $template::ACCESSOR_CALL);
    }

    public static function getWarehouse($id)
    {
        return DistributorModel::objects()->get(['pk' => $id]);
    }
}