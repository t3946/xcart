<?php

namespace Xcart\Shipping;

use Xcart\AmazonMWS;
use Xcart\CartElement;
use Xcart\Logs;
use Xcart\Cart;
use Xcart\Product;
use Xcart\ProductAmazonRates;

class Amazon extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        return true;
    }

    public function getServerQuotes($aShippingRates)
    {
        $aResponses = null;
        try {
            $aResponses = (new AmazonMWS('FBAOutboundServiceMWS_Client', '/FulfillmentOutboundShipment/2010-10-01/'))->getGetFulfillmentRates($this->getCustomer(), $this->getCart(), $aShippingRates);
        } catch (\Exception $e) {
            Logs::_log(Logs::LOG_RESOURCE_SHIPPING_QUOTES, time(), Logs::LOG_TYPE_SYSTEM, __CLASS__ . ': ' . $e->getMessage());
        }
        return $aResponses;
    }

    public function getShippingQuotes()
    {
        if (empty($this->aShippingRates)) {
            /*get rates from Amazon*/
            $aShippingRates = $this->getShippingRatesEntities();
            if (!empty($aShippingRates)) {
                $aFetchRates = $this->getServerQuotes($aShippingRates);
                if (!empty($aFetchRates)) {
                    $aAmazonMethods = array_keys($aFetchRates);
                    foreach ($aShippingRates as $oShippingRate) {
                        if (in_array($oShippingRate->getShippingEntity()->getName(), $aAmazonMethods) && null !== $aFetchRates[$oShippingRate->getShippingEntity()->getName()]) {
                            $oShippingRate->setShippingChargeQuote($aFetchRates[$oShippingRate->getShippingEntity()->getName()]);
                            $this->aShippingRates[] = $oShippingRate;
                        }
                    }
                    $this->saveShippingQuotesCached();
                }
            }
        }
        return $this->aShippingRates;
    }

    public function getAdditionalShippingFee($weight)
    {
        return 0;
    }

    public function getCart(): Cart
    {
        if ($this->oCarierCart === null) {
            $this->oCarierCart = new Cart();
            $aProducts = $this->oCart->getElements();
            if (!empty($aProducts)) {
                /** @var CartElement $oCartElement */
                foreach ($aProducts as $oCartElement) {
                    if (($oCartElement->getProduct()->isAmazonFBAEnabled() &&
                       ($oCartElement->getProduct()->getAmazonFBAAvailExcludedProcessing() >= $oCartElement->getQuantity())) ||
                       \count($oCartElement->getProduct()->getProductsAvailOnAmazonParentWithChild($oCartElement->getQuantity())) > 0) {
                       $this->oCarierCart->addObjectToCart($oCartElement);
                    }
                }
            }
        }
        return $this->oCarierCart;
    }
}