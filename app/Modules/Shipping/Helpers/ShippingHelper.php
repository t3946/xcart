<?php

namespace Modules\Shipping\Helpers;


use Modules\Core\Helpers\GeoipHelper;
use Modules\Distributor\Models\DistributorModel;
use Modules\Product\Models\ProductModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Shipping\Models\ZoneElementModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\Cart;
use Xcart\ShippingRate;

class ShippingHelper
{

    /**
     * @param UserModel $user
     * @param DistributorModel $distributor
     * @param array $products
     * @return ShippingRate[]
     */
    public static function getShippingRates(UserModel $user, DistributorModel $distributor, $products)
    {
        /** @var ShippingRate[] $shipping_rates */
        $shipping_rates = [];

        if ($products) {
            $oCart = new Cart();
            foreach ($products as $product) {
                $oCart->addObjectToCart(new \Xcart\CartElement($product['model'], $product['qty']));
            }
            try {
                if ($aShippingZones = (new ShippingModel())->getShippingRates($user, $distributor, $oCart)) {
                    $shipping_rates = reset($aShippingZones);
                }
            } catch (\Exception $e) {
                $shipping_rates = [];
            }
        }


        return $shipping_rates;
    }

    public static function getMinShippingRate(UserModel $user, DistributorModel $distributor, $products)
    {
        /** @var ShippingRate $shipping_rate */
        $shipping_rate = null;
        if ($shipping_rates = static::getShippingRates($user, $distributor, $products)) {
            $shipping_rate = reset($shipping_rates);
        }
        return $shipping_rate;
    }

    /**
     * @param integer  $product_id
     * @param integer $qty
     * @return array
     */
    public static function getProductShippingData($product_id, $qty)
    {
        $result = [];

        if ($product_model = ProductModel::objects()->get(['productid' => $product_id])) {
            /** @var DistributorModel $oManufacturer */
            $oManufacturer = $product_model->distributor;
            $ip = Xcart::app()->request->getUserIP();
            if (($geo_ip = GeoipHelper::getGeoipLocation($ip))
                && ($state_model = $geo_ip->state_model)
                && ($oManufacturer->calculate_shipping == 'Y' || (($product_model->amazon_fba == 'Y') && ($product_model->amazon_fba_avail > 0)))
            ) {
                if ($z = ZoneElementModel::objects()->filter(
                    [
                        'field' => $state_model->country_code . '_' . $state_model->code,
                        'zone__zone_name' => 'USA: Contiguous'
                    ])->count()) {

                    $userModel = new UserModel();
                    $userModel->setAttributes([
                        's_country' => $state_model->country_code,
                        's_state' => $state_model->code,
                        's_zipcode' => $state_model->base_state_zipcode,
                        's_city' => 'New City'
                    ]);

                    $result =
                        [
                            ShippingHelper::getMinShippingRate($userModel, $oManufacturer, [['model' => $product_model, 'qty' => intval($qty)]]),
                            $state_model
                        ];
                }
            }
        }
        return $result;
    }
}