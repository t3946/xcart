<?php

namespace Xcart\Shipping;

use Xcart\Product;

class Flat extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        return true;
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

    public function getServerQuotes($aShippingRates)
    {

    }

    public function getAdditionalShippingFee($weight)
    {
        return 0;
    }
}