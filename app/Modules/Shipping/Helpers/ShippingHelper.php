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

    public static function isCalcShippingEnabled($product, $qty = 1)
    {
        $oManufacturer = $product->distributor;
        $ip = Xcart::app()->request->getUserIP();
        //$ip = '173.234.204.152';
        if (($geo_ip = GeoipHelper::getGeoipLocation($ip))
            && ($state_model = $geo_ip->state_model)
            && ($oManufacturer->calculate_shipping == 'Y'
                || (
                    ($product->amazon_fba == 'Y' && $product->getAmazonFBAAvailExcludedProcessing() >= $qty)
                    || count($product->getProductsAvailOnAmazonParentWithChild($qty))
                )
            )
        ) {
            if ($z = ZoneElementModel::objects()->filter(
                [
                    'field' => $state_model->country_code . '_' . $state_model->code,
                    'zone__zone_name' => 'USA: Contiguous'
                ])->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param integer $product_id
     * @param integer $qty
     * @return array
     */
    public static function getProductShippingData($product_id, $qty)
    {
        $result = [];

        if ($product_model = ProductModel::objects()->get(['productid' => $product_id])) {

            $state_model = GeoipHelper::getGeoipLocation(Xcart::app()->request->getUserIP())->state_model;
            $userModel = new UserModel();
            $userModel->setAttributes([
                's_country' => $state_model->country_code,
                's_state' => $state_model->code,
                's_zipcode' => $state_model->base_state_zipcode,
                's_city' => 'New City'
            ]);

            $result =
                [
                    ShippingHelper::getMinShippingRate($userModel, $product_model->distributor, [['model' => $product_model, 'qty' => intval($qty)]]),
                    $state_model
                ];
        }
        return $result;
    }
}