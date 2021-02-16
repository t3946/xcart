<?php


namespace Modules\Sites\Helpers;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Sites\Models\SiteModel;
use Modules\Sites\Models\TaxRatesModel;

class TaxHelper
{
    public static function getTaxValue(TaxRatesModel $tax_rate, float $product_total, float $shipping_total): float
    {
        $value_net = 0;
        $tax_value = 0;

        $tax = $tax_rate->tax;

        switch ($tax->apply_to) {
            case 'PS' :
                $value_net = $product_total;
                break;
            case 'SH' :
                $value_net = $product_total + $shipping_total;
                break;
        }

        switch ($tax_rate->rate_type) {
            case '%':
                $tax_value = $value_net * ($tax_rate->rate_value / 100);
                break;
            case '$':
                $tax_value = $tax_rate->rate_value;
                break;
        }
        return $tax_value;
    }

    /**
     * @param SiteModel $site
     * @param CountryModel $country
     * @param StateModel|null $state
     * @return TaxRatesModel[]
     */
    public static function getTaxRate(SiteModel $site, string $country, string $state = null): array
    {
        $filter = [
            'tax__sites__storefrontid' => $site->storefrontid,
            'tax__active' => true,
        ];
        if ($state) {
            $filter += [
                'zone__zone_element__field_type' => 'S',
                'zone__zone_element__field' => $country . '_' . $state,
            ];
        } else {
            $filter += [
                'zone__zone_element__field_type' => 'C',
                'zone__zone_element__field' => $country,
            ];
        }
        return TaxRatesModel::objects()->filter($filter)->all();
    }

}