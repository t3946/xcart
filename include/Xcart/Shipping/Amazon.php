<?php

namespace Xcart\Shipping;

use Xcart\ProductAmazonRates;

class Amazon extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = false;
        return $bResult;
    }

    public function getShippingRates()
    {
        global $config;

        $aShippingRates = $this->getShippingRatesEntities();
        if (!empty($aShippingRates)) {
            foreach ($aShippingRates as $oShippingRate) {
                $aProducts = $this->getProducts();
                if (!empty($aProducts)) {
                    if (count($aProducts) == 1) {
                        /*get proxy amazon rates for 1 product*/
                        $oProduct = reset($aProducts);
                        $oProductAmazonRates = ProductAmazonRates::model([
                            'product_id' => $oProduct->getProductId(),
                            'shipping_id' => $oShippingRate->getField('shippingid'),
                            'state_id' => $this->getCustomer()->getShippingStateEntity()->getStateId()]);
                        if ($oProductAmazonRates->getField('product_id')) {
                            $oDate = new \DateTime();
                            $oDate->setTimestamp(strtotime($oProductAmazonRates->getField('last_update')));
                            $iDaysInterval = $oDate->diff(new \DateTime('now'))->days;
                            if ($iDaysInterval <= $config["Froogle"]["froogle_days_cache_rates"]) {
                                $oShippingRate->setShippingCharge($oProductAmazonRates->getField('rate'));
                            }
                        }
                    }
                }
            }
        }
        return $aShippingRates;
    }
}