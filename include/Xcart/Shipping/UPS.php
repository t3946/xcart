<?php

namespace Xcart\Shipping;

use Xcart\Product;
use Xcart\ApproximationShippingRates;
use Xcart\SQLBuilder;

class UPS extends ShippingProcessor
{
    const APPROXIMATION_SHIPPING_METHOD = 1;
    const APPROXIMATION_MAX_VALID_TIME = 1;//5184000; //2 months

    public function isProcessorApplicable()
    {
        $bResult = true;
        return $bResult;
    }

    public function getShippingQuotes()
    {
        if (empty($this->aShippingRates)) {

            $aShippingRates = $this->getShippingRatesEntities();
            if (!empty($aShippingRates)) {
                foreach ($aShippingRates as $oShippingRate) {
                    if (intval($oShippingRate->getField('shippingid')) == self::APPROXIMATION_SHIPPING_METHOD) {
                        /*get aproximation rates for UPS Ground*/
                        $oApproximationRates = ApproximationShippingRates::model()->find(
                            SQLBuilder::getInstance()->
                            addCondition('manufacturerid = ' . $this->getManufacturer()->getManufacturerId())->
                            addCondition('last_updated_date >= ' . (time() - self::APPROXIMATION_MAX_VALID_TIME))->
                            addCondition("state = '{$this->getCustomer()->getShippingStateEntity()->getCode()}'")
                        );
                        if ($oApproximationRates->getField('id')) {
                            $weight = $oShippingRate->getCartShippingWeight();
                            $shippingCharge = 0;
                            switch ($weight) {
                                case ($weight > 0 && $weight <= 1):
                                    $shippingCharge = $oApproximationRates->getField('bw_1');
                                    break;
                                case ($weight > 1 && $weight <= 75):
                                    $shippingCharge = $oApproximationRates->getField('bw_1') + ($oApproximationRates->getField('bw_75') - $oApproximationRates->getField('bw_1')) / (75 - 1) * ($weight - 1);
                                    break;
                                case ($weight > 75):
                                    $shippingCharge = $oApproximationRates->getField('bw_75') + ($oApproximationRates->getField('bw_150') - $oApproximationRates->getField('bw_75')) / (150 - 75) * ($weight - 75);
                                    break;
                            }
                            $this->aShippingRates[] = $oShippingRate->setShippingChargeQuote($shippingCharge);
                        }
                    } else {
                        /* get UPS Rates from server */
                        
                    }
                }
            }
        }
        return $this->aShippingRates;
    }

    public function getShippingQuotesCached()
    {

    }

    public function saveShippingQuotesCached(Product $oProduct)
    {

    }
}