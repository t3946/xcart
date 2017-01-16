<?php
namespace Xcart;

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


    public static function getShippingWeight($iProductId, $iShippingId, $iAmount = 1, $aProduct = array(), $aShipping = array(), $bUseShippingParametrs = true)
    {
        if (empty($aProduct)) {
            $aProduct = Product::model(['productid' => $iProductId])->getFields();
        }
        if (empty($aShipping)) {
            $aShipping = self::model(['shippingid' => $iShippingId])->getFields();
        }

        if (empty($aProduct["weight"]) || floatval($aProduct["weight"]) == 0) {
            $aProduct["weight"] = "0.1";
        }

        $real_weight = $aProduct["weight"] * $iAmount;

        if (!empty($aProduct["shipping_weight"]) && floatval($aProduct["shipping_weight"]) > 0 && $bUseShippingParametrs)
            $real_weight = $aProduct["shipping_weight"] * $iAmount;

        if (($aProduct["shipping_dim_x"] || $aProduct["shipping_dim_y"] || $aProduct["shipping_dim_z"]) && $bUseShippingParametrs)
            $Volume = $aProduct["shipping_dim_x"] * $aProduct["shipping_dim_y"] * $aProduct["shipping_dim_z"] * $iAmount; else
            $Volume = $aProduct["dim_x"] * $aProduct["dim_y"] * $aProduct["dim_z"] * $iAmount;

        if ($Volume > $aShipping["vol_threshold"] && !empty($aShipping["dim_factor"])) {
            $weight = max($real_weight, ($Volume / $aShipping["dim_factor"]));
        } else {
            $weight = $real_weight;
        }

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

    public function getShippingZones(Customer $oCustomer, Manufacturer $oManufacturer)
    {
        if (is_null($this->aShippingZones)) {
            if ($oCustomer->getField("s_country")) {
                $cs_state = $oCustomer->getField("s_state");
                $cs_country = $oCustomer->getField("s_country");
                $sCA_ST = $cs_country . "_" . $cs_state;

                $sSQL = <<<SQL
SELECT ZE.zoneid, COUNT(DISTINCT ZES.field) cnt
FROM xcart_zone_element AS ZE
INNER JOIN xcart_zone_element AS ZES USING (zoneid, field_type)
INNER JOIN xcart_shipping_rates SR ON SR.manufacturerid = {$oManufacturer->getManufacturerId()} AND ZE.zoneid = SR.zoneid
WHERE ZE.field_type = 'S' AND ZE.field ='{$sCA_ST}'
GROUP BY ZE.zoneid 
UNION
SELECT zoneid, 999999999
FROM xcart_shipping_rates
WHERE manufacturerid = {$oManufacturer->getManufacturerId()} AND zoneid = 0 
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

    public function getZoneShippingMethodsByZone(Manufacturer $oManufacturer, $iShippingZone)
    {
        if (empty($this->aShippingMethods[$iShippingZone])){
            $this->aShippingMethods[$iShippingZone] = Shipping::model()->findAll(
                SQLBuilder::getInstance()->
                addInnerJoin('shipping_carrier', 'sc', 'main.code = sc.carrier_code OR (main.code = "" AND sc.carrier_code = "Flat") ')->
                addInnerJoin('shipping_rates', 'sr', 'main.shippingid = sr.shippingid')->
                addCondition("active = 'Y'")->
                addCondition('manufacturerid = ' . $oManufacturer->getManufacturerId())->
                addCondition('zoneid = ' . $iShippingZone)->
                //addCondition("type = '$type'")->
                addOrderBy('sc.priority DESC')
            );
        }
        return $this->aShippingMethods[$iShippingZone];
    }

    public function getZoneShippingMethods(){
        return $this->aShippingMethods;
    }

    public function setZoneShippingMethods($aShippingMethods)
    {
        $this->aShippingMethods = $aShippingMethods;
    }

    /**
     * @param Customer $oCustomer
     * @param Manufacturer $oManufacturer
     * @param Cart $oCart
     * @return ShippingProcessor[]
     */
    public function getShippingZonesProcessors(Customer $oCustomer, Manufacturer $oManufacturer, $oCart)
    {
        $aShippingMethods = null;
        $aShippingZones = $this->getShippingZones($oCustomer, $oManufacturer);
        if (!empty($aShippingZones)) {
            foreach ($aShippingZones as $aShippingZone) {
                $aShippingProcessor = null;
                $aShippingsMethods = $this->getZoneShippingMethodsByZone($oManufacturer, $aShippingZone['zoneid']);
                if (!empty($aShippingsMethods)) {
                    foreach ($aShippingsMethods as $oShippingMethod) {
                        $sShippingCode = $oShippingMethod->getField('code');
                        if (empty($sShippingCode)) {
                            $sShippingCode = 'Flat';
                        }
                        if (empty($aShippingProcessor) || !in_array($sShippingCode, array_keys($aShippingProcessor))) {
                            $sProcessor = __NAMESPACE__ . '\\Shipping\\' . $sShippingCode;
                            if (class_exists($sProcessor)) {
                                /** @var ShippingProcessor $oProcessor */
                                $oProcessor = new $sProcessor($oCart);
                                $oProcessor->setManufacturer($oManufacturer);
                                $oProcessor->setCustomer($oCustomer);
                                $oShippingZone = ShippingZone::model(['zoneid' => $aShippingZone['zoneid']]);
                                $oShippingZone->setField('zoneid', $aShippingZone['zoneid']); // for 0 zoneid
                                $oProcessor->setShippingZone($oShippingZone);
                                $aShippingProcessor[$sShippingCode] = $oProcessor;
                            }
                        }
                    }
                }

                $aShippingMethods[$aShippingZone['zoneid']] = $aShippingProcessor;
            }
        }


        return $aShippingMethods;
    }

    public function getShippingRates(Customer $oCustomer, Manufacturer $oManufacturer, Cart $oCart, $bGetOnlyApproximationRates = false)
    {
        $aResult = null;
        $aShippingZoneRatesPriority = [];
        $iMinProcessorPriority = 0;

        if (!$oCustomer->getField('s_zipcode') && !$oCustomer->getField('s_country')) {
            throw new \Exception('Shipping rates error: Customers country or state not set');
        }
        if (!$oManufacturer->getManufacturerId()) {
            throw new \Exception('Shipping rates error: Manufacturer not set');
        }

        if ($oCart->getProductCount()) {
            $aShippingZones = $this->getShippingZonesProcessors($oCustomer, $oManufacturer, $oCart);
            if (!empty($aShippingZones)) {
                foreach ($aShippingZones as $aShippingZonesArr) {
                    if (!empty($aShippingZonesArr)) {
                        /** @var ShippingProcessor $oShippingProcessor */
                        foreach ($aShippingZonesArr as $oShippingProcessor) {
                            $oShippingProcessor->setGetOnlyApproximationRates($bGetOnlyApproximationRates);
                            $aRates = $oShippingProcessor->getShippingRates();
                                if (!empty($aRates)) {
                                    $aShippingZoneRatesPriority[$oShippingProcessor->getPriority()][] = $aRates;
                                }
                            $aShippingZoneRatesPriority[$oShippingProcessor->getPriority()][] = $aRates;
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
                                            if (!is_null($iSimilarRateKey)) {
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
            $aResult = $aShippingZoneRatesPriority[$iMinProcessorPriority];
        }
        return $aResult;
    }

    /**
     * @return ShippingCarrier
     */
    public function getShippingCarrier()
    {
        if (is_null($this->oShippingCarrier)) {
            $sCode = $this->getField('code');
            if (empty($sCode)) {
                $sCode = 'Flat';
            }
            $this->oShippingCarrier = ShippingCarrier::model(['carrier_code' => $sCode]);
        }
        return $this->oShippingCarrier;
    }
}