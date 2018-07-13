<?php

namespace Modules\Shipping\Helpers;


use Modules\Core\Models\StateModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\ProductModel;
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
     * @param bool $use_map_price
     * @return ShippingRate[]
     */
    public static function getShippingRates(UserModel $user, DistributorModel $distributor, $products, $weight_ratio = null, $use_cache = true, $use_map_price = true, $use_approximation = true)
    {
        /** @var ShippingRate[] $shipping_rates */
        $shipping_rates = [];

        if ($products) {
            $oCart = new Cart();
            foreach ($products as $product) {
                $element = new \Xcart\CartElement($product['model'], $product['qty']);
                $element->setWeightRation($weight_ratio);
                $oCart->addObjectToCart($element);
            }
            try {
                if ($aShippingZones = (new ShippingModel())->getShippingRates($user, $distributor, $oCart, false, $use_cache, $use_map_price, $use_approximation)) {
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
        $ip = Xcart::app()->request->getUserIP();
        if (($geo_ip = GeoipHelper::getGeoipLocation($ip))
            && ($state_model = $geo_ip->state_model)
            && ($product->distributor->calculate_shipping === 'Y'
                || (
                    ($product->amazon_fba === 'Y' && $product->getAmazonFBAAvailExcludedProcessing() >= $qty)
                    || count($product->getProductsAvailOnAmazonParentWithChild($qty))
                )
            )
        ) {
            return static::isUSAContiguous($state_model);
        }

        return false;
    }

    /**
     * @param ProductModel $product_model
     * @param integer $qty
     * @param StateModel $stateModel
     * @param float $weight_ratio
     * @param bool $use_cache
     * @return ShippingRate|null
     * @throws \Exception
     */
    public static function getStateMinShipping(ProductModel $product_model, $qty, $stateModel, $zip = null, $weight_ratio = null, $use_cache = true)
    {
        $result = null;

        if ($product_model) {

            $userModel = new UserModel([
                's_country' => $stateModel->country_code,
                's_state' => $stateModel->code,
                's_zipcode' => $zip ?? $stateModel->base_state_zipcode,
                's_city' => 'New City'
            ]);

            $result = ShippingHelper::getMinShippingRate($userModel, $product_model->distributor, [['model' => $product_model, 'qty' => (int) $qty]], $weight_ratio, $use_cache);
        }

        return $result;
    }


    /**
     * @param integer $product_id
     * @param integer $qty
     * @param StateModel $stateModel
     * @param null $weight_ratio
     * @param bool $use_cache
     * @param bool $use_map_price
     * @return ShippingRate[]
     */
    public static function getStateShipping($product_id, $qty, $stateModel, $weight_ratio = null, $use_cache = true, $use_map_price = true, $use_approximation = true)
    {
        $result = [];

        if ($product_model = ProductModel::objects()->get(['productid' => $product_id])) {

            $userModel = new UserModel([
                's_country' => $stateModel->country_code,
                's_state' => $stateModel->code,
                's_zipcode' => $stateModel->base_state_zipcode,
                's_city' => 'New City'
            ]);

            $result = ShippingHelper::getShippingRates($userModel, $product_model->distributor, [['model' => $product_model, 'qty' => intval($qty)]], $weight_ratio, $use_cache, $use_map_price, $use_approximation);
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

    public static function getTmpStateMinShipping($product_model, $qty, $state_mass, $weight_ratio = null, $use_cache = true)
    {
        $result = null;


        $userModel = new UserModel([
                                       's_country' => $state_mass['s_country'],
                                       's_state' => $state_mass['s_state'],
                                       's_zipcode' => $state_mass['s_zipcode'],
                                       's_city' => 'New City'
                                   ]);

        $result = ShippingHelper::getTmpMinShippingRate($userModel, $product_model->distributor, [['model' => $product_model, 'qty' => intval($qty)]], $weight_ratio, $use_cache);

        return $result;
    }

    public static function getTmpMinShippingRate(UserModel $user, DistributorModel $distributor, $products, $weight_ratio = null, $use_cache = true)
    {
        /** @var ShippingRate $shipping_rate */
        $shipping_rate = null;
        if ($shipping_rates = static::getShippingRates($user, $distributor, $products, $weight_ratio, $use_cache, false, false)) {
            $shipping_rate = $shipping_rates;
        }
        return $shipping_rate;
    }

    public static function getQtyForFreeShipping(ProductModel $model, $state_model, $zip): int
    {
        $qty = $amazon_avail = 0;
        $ups_qty = 0;

        if ($model && !$model->shipping_calc_disabled && ($distributor = $model->distributor) && $distributor->reduce_extra_margin && $distributor->max_extra_margin > (float) 0) {

                if($model->amazon_fba === 'Y') {
                    if ($amazon_avail = max($model->getAmazonFBAAvailExcludedProcessing(), count($model->getProductsAvailOnAmazonParentWithChild($qty)))) {
                        if ($rate = self::getStateMinShipping($model, ++$ups_qty, $state_model, $zip)) {
                            if (($ship_model = $rate->shipping) && $ship_model->is_free_shipping) {
                                return $ups_qty;
                            }
                        }
                    }
                }

                if ($amazon_avail) {
                    $ups_qty = ceil($amazon_avail / 2) ;
                }

                $i = 0;
                do {
                    if ($rate = self::getStateMinShipping($model, ++$ups_qty, $state_model, $zip)) {
                        if (($ship_model = $rate->shipping) && $ship_model->is_free_shipping) {
                            return $ups_qty;
                        }
                        $ups_qty = ceil($rate->getShippingChargeBeforeMap() / ($model->getPrice($ups_qty) - ($model->cost_to_us * $distributor->max_extra_margin)));
                        if ($ups_qty > $model->r_avail || $ups_qty > 200) {
                            break;
                        }
                    }
                } while($i++ < 10);
        }

        return $qty;
    }
}