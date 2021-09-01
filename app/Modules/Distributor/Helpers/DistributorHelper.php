<?php

namespace Modules\Distributor\Helpers;


use Modules\Core\Models\CountryModel;
use Modules\Distributor\Models\DistributorModel;

class DistributorHelper
{
    /**
     * @param int $manufacturerid
     * @return CountryModel[]
     */
    public static function getShippingCountries($manufacturerid)
    {
        return CountryModel::objects()
            ->filter(['zone_element__shipping_rates__manufacturerid' => $manufacturerid])
            ->group(['code'])
            ->all();
    }

    /**
     * get Distributor emails by unity type
     * @param DistributorModel $dx
     * @param int $unity_type
     * @return array
     */
    public static function getDistributorEmails(DistributorModel $dx, int $unity_type): array
    {
        $to = $dx->contacts_model->filter([
            'utility__utility_id' => $unity_type
        ])->valuesList('email', true);

        $to = array_unique(array_map('trim', $to));

        return $to;
    }
}