<?php

namespace Xcart\Shipping;


use Xcart\Manufacturer;
use Xcart\ShippingZone;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;
use Xcart\Product;
use Xcart\Customer;

abstract class ShippingProcessor
{
    private $oManufacturer = null;
    private $oShippingZone = null;
    private $sShippingType = null;
    /**
     * @var Customer
     */
    private $oCustomer = null;

    /**
     * @var ShippingCart
     */
    private $oCart = null;

    /**
     * @var ShippingRate[]
     */
    private $aShippingRatesEntities = null;
    /**
     * @var ShippingRate[]
     */
    protected $aShippingRates = null;

    /**
     * @return boolean
     */
    abstract public function isProcessorApplicable();

    abstract public function getShippingQuotes();

    abstract public function getShippingQuotesCached();

    abstract public function saveShippingQuotesCached(Product $oProduct);

    abstract public function getServerQuotes($aShippingRates);

    public function getShippingRates()
    {
        if ($this->getCart() && $this->getCart()->getProductCount() > 0) {
            if ($this->isProcessorApplicable()) {
                $this->getShippingQuotesCached();
                $this->getShippingQuotes();
                if (!empty($this->aShippingRates)) {
                    usort($this->aShippingRates, ['\Xcart\Shipping\ShippingProcessor', 'sortShippingRatesByCostAsc']);
                    foreach ($this->aShippingRates as $oShippingRate) {
                        $oShippingRate->setCart($this->getCart());
                    }
                }
            }
        }
        return $this->aShippingRates;
    }

    public function sortShippingRatesByCostAsc(ShippingRate $a, ShippingRate $b)
    {
        return $a->getShippingCharge() > $b->getShippingCharge();
    }

    /**
     * @return Manufacturer
     */
    public function getManufacturer()
    {
        return $this->oManufacturer;
    }

    /**
     * @param Manufacturer
     */
    public function setManufacturer($oManufacturer)
    {
        $this->oManufacturer = $oManufacturer;
    }

    /**
     * @return ShippingZone
     */
    public function getShippingZone()
    {
        return $this->oShippingZone;
    }

    /**
     * @param ShippingZone
     */
    public function setShippingZone($oShippingZone)
    {
        $this->oShippingZone = $oShippingZone;
    }

    /**
     * @return string
     */
    public function getShippingType()
    {
        return $this->sShippingType;
    }

    /**
     * @param string $sShippingType
     */
    public function setShippingType($sShippingType)
    {
        $this->sShippingType = $sShippingType;
    }

    /**
     * @return ShippingRate[]
     */
    public function getShippingRatesEntities()
    {
        if (is_null($this->aShippingRatesEntities)) {
            $this->aShippingRatesEntities = ShippingRate::model()->findAll(
                SQLBuilder::getInstance()->
                addInnerJoin('shipping', 's', 'main.shippingid = s.shippingid')->
                addCondition('zoneid = ' . $this->getShippingZone()->getField('zoneid'))->
                addCondition('manufacturerid = ' . $this->getManufacturer()->getManufacturerId())->
                addCondition("s.active = 'Y'")
            );
            if (!empty($this->aShippingRatesEntities)) {
                foreach ($this->aShippingRatesEntities as $key => $oShippingRate) {
                    $oShippingRate->setCart($this->getCart());
                    if (!$oShippingRate->checkShippingRateByFilterValues()) {
                        unset($this->aShippingRatesEntities[$key]);
                    }
                }
            }
        }
        return $this->aShippingRatesEntities;
    }

    /**
     * @return ShippingCart
     */
    public function getCart()
    {
        return $this->oCart;
    }

    /**
     * @param Product $oProduct
     */
    public function addProduct($oProduct, $qty)
    {
        if (is_null($this->oCart)) {
            $this->oCart = new ShippingCart();
        }
        $this->oCart->addToCart($oProduct, $qty);
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->oCustomer;
    }

    /**
     * @param Customer $oCustomer
     */
    public function setCustomer($oCustomer)
    {
        $this->oCustomer = $oCustomer;
    }


}

class ShippingCart
{
    private $aCart;

    public function addToCart(Product $oProduct, $qty)
    {
        if ($oProduct->getProductId()) {
            $this->aCart[$oProduct->getProductId()]['qty'] += $qty;
            $this->aCart[$oProduct->getProductId()]['entity'] = $oProduct;
        }
    }

    public function getProductCount()
    {
        $qty = 0;
        if (!empty($this->aCart)) {
            foreach ($this->aCart as $aProduct) {
                $qty += $aProduct['qty'];
            }
        }
        return $qty;
    }

    public function getProducts()
    {
        return $this->aCart;
    }


    public function getCost()
    {
        $fCost = 0;
        if (!empty($this->aCart)) {
            foreach ($this->aCart as $aCartRow) {
                $fCost += $aCartRow['entity']->getPrice() * $aCartRow['qty'];
            }
        }
        return $fCost;
    }
}