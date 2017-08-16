<?php

namespace Modules\Shipping\Helpers;


use Modules\Core\Helpers\GeoipHelper;
use Modules\Core\Models\StateModel;
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
     * @param null|float $weight_ratio
     * @param bool $use_cache
     * @return ShippingRate[]
     */
    public static function getShippingRates(UserModel $user, DistributorModel $distributor, $products, $weight_ratio = null, $use_cache = true)
    {
        /** @var ShippingRate[] $shipping_rates */
        $shipping_rates = [];

        if ($products) {
            $oCart = new Cart();
            foreach ($products as $product) {
                $oCart->addObjectToCart(new \Xcart\CartElement($product['model'], $product['qty']));
            }
            try {
                if ($aShippingZones = (new ShippingModel())->getShippingRates($user, $distributor, $oCart, false, $weight_ratio, $use_cache)) {
                    $shipping_rates = reset($aShippingZones);
                }
            } catch (\Exception $e) {
                $shipping_rates = [];
            }
        }


        return $shipping_rates;
    }

    /**
     * @param UserModel $user
     * @param DistributorModel $distributor
     * @param array $products
     * @param null|float $weight_ratio
     * @param bool $use_cache
     * @return null|ShippingRate
     */
    public static function getMinShippingRate(UserModel $user, DistributorModel $distributor, $products, $weight_ratio = null, $use_cache = true)
    {
        /** @var ShippingRate $shipping_rate */
        $shipping_rate = null;
        if ($shipping_rates = static::getShippingRates($user, $distributor, $products, $weight_ratio, $use_cache)) {
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
            return static::isUSAContiguous($state_model);
        }

        return false;
    }

    /**
     * @param integer $product_id
     * @param integer $qty
     * @param StateModel $stateModel
     * @param float $weight_ratio
     * @return ShippingRate|null
     */
    public static function getStateMinShipping($product_id, $qty, $stateModel, $weight_ratio = null, $use_cache = true)
    {
        $result = null;

        if ($product_model = ProductModel::objects()->get(['productid' => $product_id])) {

            $userModel = new UserModel([
                's_country' => $stateModel->country_code,
                's_state' => $stateModel->code,
                's_zipcode' => $stateModel->base_state_zipcode,
                's_city' => 'New City'
            ]);

            $result = ShippingHelper::getMinShippingRate($userModel, $product_model->distributor, [['model' => $product_model, 'qty' => intval($qty)]], $weight_ratio, $use_cache);
        }

        return $result;
    }

    /**
     * @param integer $product_id
     * @param integer $qty
     * @param StateModel $stateModel
     * @return ShippingRate[]
     */
    public static function getStateShipping($product_id, $qty, $stateModel, $weight_ratio = null, $use_cache = true)
    {
        $result = [];

        if ($product_model = ProductModel::objects()->get(['productid' => $product_id])) {

            $userModel = new UserModel([
                's_country' => $stateModel->country_code,
                's_state' => $stateModel->code,
                's_zipcode' => $stateModel->base_state_zipcode,
                's_city' => 'New City'
            ]);

            $result = ShippingHelper::getShippingRates($userModel, $product_model->distributor, [['model' => $product_model, 'qty' => intval($qty)]], $weight_ratio, $use_cache);
        }

        return $result;
    }

    public static function isUSAContiguous(StateModel $model)
    {
        return (ZoneElementModel::objects()
                ->filter(
                    [
                        'field' => $model->country_code . '_' . $model->code,
                        'zone__zone_name' => 'USA: Contiguous'
                    ])
                ->count() > 0);
    }
}