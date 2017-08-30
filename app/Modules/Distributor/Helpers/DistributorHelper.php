<?php

namespace Modules\Distributor\Helpers;


use Modules\Core\Models\CountryModel;

class DistributorHelper
{
    /**
     * @param integer $manufacturerid
     * @return CountryModel[]
     */
    public static function getShippingCountries($manufacturerid)
    {
        return CountryModel::objects()
            ->filter(['zone_element__shipping_rates__manufacturerid' => $manufacturerid])
            ->group(['code'])
            ->all();
    }
}