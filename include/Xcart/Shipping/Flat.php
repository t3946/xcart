<?php

namespace Xcart\Shipping;

use Xcart\Product;

class Flat extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = false;
        return $bResult;
    }

    public function getShippingQuotesCached()
    {
    }

    public function getShippingQuotes()
    {
        $this->aShippingRates = $this->getShippingRatesEntities();
        if (!empty($this->aShippingRates)) {
            foreach ($this->aShippingRates as $oShippingRate) {
                $oShippingRate->setShippingChargeQuote(0);
            }
        }
        return $this->aShippingRates;
    }

    public function saveShippingQuotesCached(Product $oProduct)
    {

    }
}