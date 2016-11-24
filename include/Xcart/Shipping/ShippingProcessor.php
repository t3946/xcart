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
     * @var Product[]
     */
    private $aProducts = null;

    /**
     * @var ShippingRate[]
     */
    private $aShippingRates = null;

    /**
     * @return boolean
     */
    abstract public function isProcessorApplicable();
    abstract public function getShippingRates();

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
        if (is_null($this->aShippingRates)) {
            $this->aShippingRates = ShippingRate::model()->findAll(
                SQLBuilder::getInstance()->
                addInnerJoin('shipping', 's', 'main.shippingid = s.shippingid')->
                addCondition('zoneid = ' . $this->getShippingZone()->getField('zoneid'))->
                addCondition('manufacturerid = ' . $this->getManufacturer()->getManufacturerId())->
                addCondition("type = '" . $this->getShippingType() . "'")->
                addCondition("s.active = 'Y'")
            );
        }
        return $this->aShippingRates;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->aProducts;
    }

    /**
     * @param Product[] $aProducts
     */
    public function setProducts($aProducts)
    {
        $this->aProducts = $aProducts;
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