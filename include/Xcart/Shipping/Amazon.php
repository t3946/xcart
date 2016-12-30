<?php

namespace Xcart\Shipping;

use Xcart\AmazonMWS;
use Xcart\Logs;
use Xcart\Cart;
use Xcart\Product;
use Xcart\ProductAmazonRates;

class Amazon extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = true;
        return $bResult;
    }

    public function getServerQuotes($aShippingRates)
    {
        $aResponses = null;
        try {
            $aResponses = (new AmazonMWS('FBAOutboundServiceMWS_Client', '/FulfillmentOutboundShipment/2010-10-01/'))->getGetFulfillmentRates($this->getCustomer(), $this->getCart(), $aShippingRates);
        }
        catch (\Exception $e) {
            Logs::_log(Logs::LOG_RESOURCE_SHIPPING_QUOTES, time(), Logs::LOG_TYPE_SYSTEM, __CLASS__.': '. $e->getMessage());
        }
        return $aResponses;
    }

    public function getShippingQuotes()
    {
        if (empty($this->aShippingRates)) {
            /*get rates from Amazon*/
            $aShippingRates = $this->getShippingRatesEntities();
            $oShippingCart = $this->getCart();
            if (!empty($aShippingRates)) {
                $aFetchRates = $this->getServerQuotes($aShippingRates);
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
                        //$this->saveShippingQuotesCached($oProduct);
                    }
                    $this->saveShippingQuotesCached();
                }
            }
        }
        return $this->aShippingRates;
    }

    /*public function saveShippingQuotesCached(Product $oProduct)
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
    }*/

    public function getAdditionalShippingFee($weight)
    {
        $fAdditionalShippingFee = 0;
        return $fAdditionalShippingFee;
    }

    public function getCart()
    {
        $oAmazonCart = new Cart();
        $aProducts = $this->oCart->getProducts();
        if (!empty($aProducts)) {
            foreach ($aProducts as $aProduct) {
                if ($aProduct['entity']->isAmazonFBAEnabled() && ($aProduct['entity']->getAmazonFBAAvailExcludedProcessing() > 0)) {
                    $oAmazonCart->addToCart($aProduct['entity'], $aProduct['qty']);
                }
            }
        }
        return $oAmazonCart;
    }
}