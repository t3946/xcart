<?php

namespace Modules\Shipping\Helpers;

use Modules\Shipping\Models\ShippingRateModel;
use Xcart\App\QueryBuilder\Expression;

class ShippingRatesHelper
{
    /** get Shipping rates for distributor
     *
     * @param int $distributor_id
     * @param string $country_to
     * @param string $state_to
     * @param float $weight
     * @param float $grand_total
     * @return ShippingRateModel[]
     */
    public static function getDistributorShippingRates(
        int $distributor_id,
        string $country_to,
        string $state_to,
        float $weight,
        float $grand_total
    ): array {
        return ShippingRateModel::objects()
            ->filter([
                'manufacturerid' => $distributor_id,
                'minweight__lte' => $weight,
                'maxweight__gte' => $weight,
                'mintotal__lte' => $grand_total,
                'maxtotal__gte' => $grand_total,
                'zoneid' => new Expression(
                    "f_shipping_getShippingZone($distributor_id, '{$country_to}', '{$state_to}')"
                )
            ])->all();
    }
}