<?php

namespace Xcart\Shipping;

use Xcart\Product;

class UPS extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = false;
        return $bResult;
    }

    public function getShippingRates()
    {
        $aShippingRates = $this->getShippingRatesEntities();
        if (!empty($aShippingRates)) {
            foreach ($aShippingRates as $oShippingRate) {

            }
        }
    }
    public function getShippingQuotes()
    {

    }
    public function getShippingQuotesCached()
    {

    }
    public function saveShippingQuotesCached(Product $oProduct)
    {

    }
}