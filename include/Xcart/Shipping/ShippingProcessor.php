<?php

namespace Xcart\Shipping;


use Xcart\Manufacturer;
use Xcart\ShippingZone;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;

abstract class ShippingProcessor
{
    private $oManufacturer = null;
    private $oShippingZone = null;
    private $sShippingType = null;
    private $aShippingRates = null;

    /**
     * @return boolean
     */
    abstract public function isProcessorApplicable();

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

    public function getShippingRates()
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


}