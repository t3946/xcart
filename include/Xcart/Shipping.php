<?php
namespace Xcart;

use Xcart\Shipping\ShippingProcessor;

class Shipping extends Data
{
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

    public function getName()
    {

        return $this->getField('shipping');
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

    public function getShippingProcessor(Customer $oCustomer, Manufacturer $oManufacturer, $type = 'R')
    {
        $aShippingMethods = null;
        $cs_state = $oCustomer->getField("s_state");
        $cs_country = $oCustomer->getField("s_country");
        $sCA_ST = $cs_country . "_" . $cs_state;

        $sSQL = <<<SQL
SELECT ZE.zoneid, COUNT(DISTINCT ZES.field) cnt
FROM xcart_zone_element AS ZE
INNER JOIN xcart_zone_element AS ZES USING (zoneid, field_type)
INNER JOIN xcart_shipping_rates SR ON SR.manufacturerid = {$oManufacturer->getManufacturerId()} AND ZE.zoneid = SR.zoneid AND SR.type='{$type}'
WHERE ZE.field_type = 'S' AND ZE.field ='{$sCA_ST}'
GROUP BY ZE.zoneid 
UNION
SELECT zoneid, 999999999
FROM xcart_shipping_rates
WHERE manufacturerid = {$oManufacturer->getManufacturerId()} AND zoneid = 0 AND type='{$type}'
GROUP BY zoneid
ORDER BY cnt
SQL;
        $aShippingZones = SQLBuilder::getInstance()->setQuery($sSQL)->query()->getQueryResult();
        if (!empty($aShippingZones)) {
            foreach ($aShippingZones as $aShippingZone) {
                $aShippingProcessor = null;
                $aShippingsMethods = Shipping::model()->findAll(
                    SQLBuilder::getInstance()->
                    addInnerJoin('shipping_rates', 'sr', 'main.shippingid = sr.shippingid')->
                    addCondition("active = 'Y'")->
                    addCondition('manufacturerid = ' . $oManufacturer->getManufacturerId())->
                    addCondition('zoneid = ' . $aShippingZone['zoneid'])->
                    addCondition("type = '$type'")->
                    addOrderBy('orderby')
                );
                if (!empty($aShippingsMethods)) {
                    foreach ($aShippingsMethods as $oShippingMethod) {
                        $sShippingCode = $oShippingMethod->getField('code');
                        if (!empty($sShippingCode)) {
                            if (empty($aShippingProcessor) || !in_array($sShippingCode, array_keys($aShippingProcessor))){
                                $sProcessor =  __NAMESPACE__. '\\Shipping\\' . $sShippingCode;
                                if (class_exists($sProcessor)) {
                                    $oProcessor = new $sProcessor();
                                    $oProcessor->setManufacturer($oManufacturer);
                                    $oProcessor->setShippingZone(ShippingZone::model(['zoneid' => $aShippingZone['zoneid']]));
                                    $oProcessor->setShippingType($type);
                                    $aShippingProcessor[$sShippingCode] = $oProcessor;
                                }
                            }
                        }
                    }
                }

                $aShippingMethods[$aShippingZone['zoneid']] = $aShippingProcessor;
            }
        }

        return $aShippingMethods;
    }

}