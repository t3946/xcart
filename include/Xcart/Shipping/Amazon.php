<?php

namespace Xcart\Shipping;

use Xcart\AmazonMWS;
use Xcart\Product;
use Xcart\ProductAmazonRates;

class Amazon extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = false;
        $oShippingCart = $this->getCart()->getProducts();
        if (!empty($oShippingCart)) {
            foreach ($oShippingCart as $aProduct) {
                $bResult &= ($aProduct['entity']->isAmazonFBAEnabled() && $aProduct['entity']->getAmazonFBAAvailExcludedProcessing() > 0);
            }
        }
        return $bResult;
    }

    public function getShippingQuotesCached()
    {
        global $config;
        $aShippingRates = $this->getShippingRatesEntities();
        $oShippingCart = $this->getCart();
        /*get proxy amazon rates for 1 product*/
        if ($oShippingCart->getProductCount() == 1) {
            foreach ($aShippingRates as $oShippingRate) {
                $aProd = $oShippingCart->getProducts();
                /** @var Product $oProduct */
                $oProduct = reset($aProd)['entity'];
                $oProductAmazonRates = ProductAmazonRates::model([
                    'product_id' => $oProduct->getProductId(),
                    'shipping_id' => $oShippingRate->getField('shippingid'),
                    'state_id' => $this->getCustomer()->getShippingStateEntity()->getStateId()]);
                if ($oProductAmazonRates->getField('product_id')) {
                    $oDate = new \DateTime();
                    $oDate->setTimestamp(strtotime($oProductAmazonRates->getField('last_update')));
                    $iDaysInterval = $oDate->diff(new \DateTime('now'))->days;
                    if ($iDaysInterval <= $config["Froogle"]["froogle_days_cache_rates"]) {
                        $oShippingRate->setShippingChargeQuote($oProductAmazonRates->getField('rate'));
                        $this->aShippingRates[] = $oShippingRate;
                    }
                }
            }
        }
        return $this->aShippingRates;
    }

    public function getShippingQuotes()
    {
        if (empty($this->aShippingRates)) {
            /*get rates from Amazon*/
            $aShippingRates = $this->getShippingRatesEntities();
            $oShippingCart = $this->getCart();
            $aFetchRates = (new AmazonMWS('FBAOutboundServiceMWS_Client', '/FulfillmentOutboundShipment/2010-10-01/'))->getGetFulfillmentRates($this->getCustomer(), $this->getCart(), $aShippingRates);
            if (!empty($aFetchRates)) {
                $aAmazonMethods = array_keys($aFetchRates);

                foreach ($aShippingRates as $oShippingRate) {
                    if (in_array($oShippingRate->getShippingEntity()->getName(), $aAmazonMethods)) {
                        $this->aShippingRates[] = $oShippingRate->setShippingChargeQuote($aFetchRates[$oShippingRate->getShippingEntity()->getName()]);
                    }
                }
                if ($oShippingCart->getProductCount() == 1 && !empty($this->aShippingRates)) {
                    /*save rates into proxy*/
                    $aProd = $oShippingCart->getProducts();
                    $oProduct = reset($aProd)['entity'];
                    $this->saveShippingQuotesCached($oProduct);
                }
            }
        }
        return $this->aShippingRates;
    }

    public function saveShippingQuotesCached(Product $oProduct)
    {
        if (!empty($this->aShippingRates)) {
            foreach ($this->aShippingRates as $oShippingRate) {
                ProductAmazonRates::model()->fill([
                    'product_id' => $oProduct->getProductId(),
                    'shipping_id' => $oShippingRate->getField('shippingid'),
                    'state_id' => $this->getCustomer()->getShippingStateEntity()->getStateId(),
                    'rate' => $oShippingRate->getShippingQuote()])->_insert(true);
            }
        }
    }
}