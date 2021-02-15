<?php


namespace Modules\Sites\Helpers;


use Modules\Order\Models\OrderGroupModel;
use Modules\Sites\Models\SiteModel;

class TaxHelper
{
    public static function getTaxValue(OrderGroupModel $model, SiteModel $site):? float
    {
        $site->taxes->filter(['rates__'])

        foreach ($site->taxes as $tax) {
            $tax->rates->
        }
        return null;
    }

}