<?php
namespace Xcart;

use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Models\ShippingModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\User\Models\UserModel;
use Xcart\Shipping\ShippingProcessor;

class Shipping extends Data
{
    /**
     * @var ShippingCarrier
     */
    private $oShippingCarrier = null;

    private $aShippingZones = null;
    /**
     * @var Shipping[]
     */
    private $aShippingMethods = [];

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'shipping';
        $this->aPrimaryKeys = ['shippingid'];
        parent::__construct($iId);
    }

    public function getShippingId()
    {
        return $this->getField('shippingid');
    }


    public static function getShippingWeight($iProductId, $iShippingId, $iAmount = 1, $aProduct = array(), $aShipping = array(), $bUseShippingParametrs = true)
    {
        /** @var ProductModel $product */
        $product = $aProduct ? new ProductModel($aProduct) : ProductModel::objects()->get(['productid' => $iProductId]);

        if (!$aShipping) {
            $aShipping = self::model(['shippingid' => $iShippingId])->getFields();
        }

        $shipping_weight = (float) $product->shipping_weight * $iAmount;
        $volume = (float) $product->shipping_dim_x * (float) $product->shipping_dim_y  * (float) $product->shipping_dim_z * $iAmount;
        $real_weight = (float) $product->weight * $iAmount;

        $product_weight = ($shipping_weight > 0) ? $shipping_weight : $real_weight;

        $weight = $volume > $aShipping["vol_threshold"] && $aShipping["dim_factor"] ? max($product_weight, ($volume / $aShipping["dim_factor"])) : $product_weight;

        return $weight;
    }

    public function getProductsShippingWeight($iShippingId, $aProducts = array(), $aShipping = array())
    {
        $weight = 0;
        if (!empty($aProducts)) {
            foreach ($aProducts as $aProduct) {
                $weight += self::getShippingWeight($aProduct['productid'], $iShippingId, $aProduct['amount'], $aProduct, $aShipping);
            }
        }
        return $weight;
    }

    public function getShippingWeightN($fWeight, $fVolume)
    {
        $dimFactor = floatval($this->getField('dim_factor'));
        if ($fVolume > $this->getField('vol_threshold') && $dimFactor != 0) {
            $weight = max($fWeight, ($fVolume / $dimFactor));
        } else {
            $weight = $fWeight;
        }

        return $weight;
    }

    public function getName()
    {
        return func_insert_trademark($this->getField('shipping'));
    }

    /**
     * @return string
     */
    public function getFrontendName()
    {
        return (!$this->getField('frontend_name')) ? $this->getName() : $this->getField('frontend_name');
    }

    public function isAmazonShipping()
    {
        $bResult = false;
        if ($this->getField('code') == 'Amazon')
            $bResult = true;
        return $bResult;
    }

    public function getShippingMethodsByCode($sCode)
    {
        return Shipping::model()->findAll(SQLBuilder::getInstance()->addCondition("code = '$sCode'"));
    }

    /**
     * @param UserModel $oCustomer
     * @param Manufacturer|DistributorModel $oManufacturer
     * @return array|null
     */
    public function getShippingZones($oCustomer, $oManufacturer)
    {
        if (is_null($this->aShippingZones)) {
            if ($oCustomer->s_country) {
                $cs_state = $oCustomer->s_state;
                $cs_country = $oCustomer->s_country;
                $sCA_ST = $cs_country . "_" . $cs_state;

                $sSQL = <<<SQL
SELECT ZE.zoneid, COUNT(DISTINCT ZES.field) cnt
FROM xcart_zone_element AS ZE
INNER JOIN xcart_zone_element AS ZES USING (zoneid, field_type)
INNER JOIN xcart_shipping_rates SR ON SR.manufacturerid = {$oManufacturer->manufacturerid} AND ZE.zoneid = SR.zoneid
WHERE ZE.field_type = 'S' AND ZE.field ='{$sCA_ST}'
GROUP BY ZE.zoneid 
UNION
SELECT zoneid, 999999999
FROM xcart_shipping_rates
WHERE manufacturerid = {$oManufacturer->manufacturerid} AND zoneid = 0 
GROUP BY zoneid
ORDER BY cnt
SQL;

                $this->aShippingZones = SQLBuilder::getInstance()->setQuery($sSQL)->query()->getQueryResult();
            }
        }
        return $this->aShippingZones;
    }

    public function setShippingZones($aShippingZone)
    {
        $this->aShippingZones = $aShippingZone;
    }

    public function getZoneShippingMethodsByZone($oManufacturer, $iShippingZone)
    {
        if (empty($this->aShippingMethods[$iShippingZone])) {
            $this->aShippingMethods[$iShippingZone] = Shipping::model()->findAll(
                SQLBuilder::getInstance()->
                addInnerJoin('shipping_carrier', 'sc', 'main.code = sc.carrier_code OR (main.code = "" AND sc.carrier_code = "Flat") ')->
                addInnerJoin('shipping_rates', 'sr', 'main.shippingid = sr.shippingid')->
                addCondition("active = 'Y'")->
                addCondition('manufacturerid = ' . $oManufacturer->manufacturerid)->
                addCondition('zoneid = ' . $iShippingZone)->
                addGroupBy('shippingid')->
                addOrderBy('sc.priority DESC')
            );
        }
        return $this->aShippingMethods[$iShippingZone];
    }

    public function getZoneShippingMethods()
    {
        return $this->aShippingMethods;
    }

    public function setZoneShippingMethods($aShippingMethods)
    {
        $this->aShippingMethods = $aShippingMethods;
    }

    /**
     * @param UserModel $oCustomer
     * @param Manufacturer $oManufacturer
     * @param Cart $oCart
     * @return ShippingProcessor[]
     */
    public function getShippingZonesProcessors($oCustomer, $oManufacturer, $oCart)
    {
        $aShippingMethods = null;
        $aShippingZones = $this->getShippingZones($oCustomer, $oManufacturer);
        if (!empty($aShippingZones)) {
            foreach ($aShippingZones as $aShippingZone) {
                $aShippingProcessor = null;
                if ($aShippingsMethods = $this->getZoneShippingMethodsByZone($oManufacturer, $aShippingZone['zoneid'])) {
                    foreach ($aShippingsMethods as $oShippingMethod) {
                        $sShippingCode = $oShippingMethod->code;
                        if (empty($sShippingCode)) {
                            $sShippingCode = 'Flat';
                        }
                        if (empty($aShippingProcessor) || !array_key_exists($sShippingCode, $aShippingProcessor)) {
                            $sProcessor = __NAMESPACE__ . '\\Shipping\\' . $sShippingCode;
                            if (class_exists($sProcessor)) {
                                /** @var ShippingProcessor $oProcessor */
                                $oProcessor = new $sProcessor($oCart);
                                $oProcessor->setManufacturer($oManufacturer);
                                $oProcessor->setCustomer($oCustomer);
                                $oProcessor->setShippingZone(ShippingZone::model()->setField('zoneid', $aShippingZone['zoneid']));
                                $aShippingProcessor[$sShippingCode] = $oProcessor;

                            }
                        }
                        $aShippingProcessor[$sShippingCode]->addShippingMethod($oShippingMethod);
                    }
                }

                $aShippingMethods[$aShippingZone['zoneid']] = $aShippingProcessor;
            }
        }
        return $aShippingMethods;
    }


    /**
     * @param UserModel $oCustomer
     * @param Manufacturer|DistributorModel $oManufacturer
     * @param Cart $oCart
     * @param bool $bGetOnlyApproximationRates
     * @param bool $use_cache
     * @param bool $use_map_price
     * @return null|ShippingRateModel[]
     * @throws \Exception
     */
    public function getShippingRates($oCustomer, $oManufacturer, Cart $oCart, $bGetOnlyApproximationRates = false, $use_cache = true, $use_map_price = true, $use_approximation = true) :? array
    {
        $min_rates = null;
        $aShippingZoneRatesPriority = [];
        $iMinProcessorPriority = 0;

        if (!$oCustomer->s_zipcode && !$oCustomer->s_country) {
            throw new \Exception('Shipping rates error: Customers country or state not set');
        }
        if (!$oManufacturer->manufacturerid) {
            throw new \Exception('Shipping rates error: Manufacturer not set');
        }

        if ($oCart->getProductCount()) {
            $aShippingZones = $this->getShippingZonesProcessors($oCustomer, $oManufacturer, $oCart);
            if (!empty($aShippingZones)) {
                foreach ($aShippingZones as $aShippingZonesArr) {
                    if ($aShippingZonesArr) {
                        /** @var ShippingProcessor $oShippingProcessor */
                        foreach ($aShippingZonesArr as $oShippingProcessor) {
                            $oShippingProcessor->setGetOnlyApproximationRates($bGetOnlyApproximationRates);
                            $oShippingProcessor->setUseApproximation($use_approximation);
                            $oShippingProcessor->setUseCache($use_cache);
                            $oShippingProcessor->setUseMapPrice($use_map_price);
                            if ($aRates = $oShippingProcessor->getShippingRates()) {
                                $aShippingZoneRatesPriority[$oShippingProcessor->getPriority()][] = $aRates;
                            }
                        }
                    }
                    if (!($oCart->getProductCount())) {
                        break;
                    }
                }
                if (!empty($aShippingZoneRatesPriority)) {
                    $iMinProcessorPriority = min(array_keys($aShippingZoneRatesPriority));
                    krsort($aShippingZoneRatesPriority);
                    foreach ($aShippingZoneRatesPriority as $priority => $aShippingZoneRates) {
                        foreach ($aShippingZoneRates as $aShippingRates) {
                            if (!empty($aShippingRates)) {
                                $aMinPriority = $aShippingZoneRatesPriority[$iMinProcessorPriority];
                                /** @var \Xcart\ShippingRate $oShippingRate */
                                foreach ($aShippingRates as $oShippingRate) {
                                    if ($priority != $iMinProcessorPriority) {
                                        /** @var ShippingRate[] $aShippingZoneRate */
                                        foreach ($aMinPriority as $keyPriority => $aShippingZoneRate) {
                                            $iSimilarRateKey = $oShippingRate->getSimilarShippingRateByDeliveryTime($aShippingZoneRate);
                                            if ($iSimilarRateKey !== null) {
                                                $aShippingZoneRate[$iSimilarRateKey]->addShippingCharge($oShippingRate);
                                                unset ($aMinPriority[$keyPriority][$iSimilarRateKey]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($aShippingZoneRatesPriority[$iMinProcessorPriority])) {
            $min_rates = $aShippingZoneRatesPriority[$iMinProcessorPriority];
        }

        /* Free Shipping Calculation */
        if ($min_rates) {
            foreach ($min_rates as $m_rate => $rates) {
                foreach ($rates as $key_r => $rate) {
                    if ($rate->getShippingCharge() === (float) 0) {
                        $min_rates = [];
                        $rate->shippingid = ShippingModel::objects()->get(['is_free_shipping' => 1])->shippingid;
                        $min_rates[] = [$rate];
                    }
                }
            }
        }
        return $min_rates;
    }

    /**
     * @return ShippingCarrier
     */
    public function getShippingCarrier()
    {
        if ($this->oShippingCarrier === null) {
            $sCode = $this->getField('code');
            if (empty($sCode)) {
                $sCode = 'Flat';
            }
            $this->oShippingCarrier = ShippingCarrier::model(['carrier_code' => $sCode]);
        }
        return $this->oShippingCarrier;
    }
}