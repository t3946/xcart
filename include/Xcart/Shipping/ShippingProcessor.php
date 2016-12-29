<?php

namespace Xcart\Shipping;


use Xcart\Manufacturer;
use Xcart\ShippingCarrier;
use Xcart\ShippingZone;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;
use Xcart\Product;
use Xcart\Customer;
use Xcart\Cart;

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
     * @var Cart
     */
    protected $oCart = null;

    /**
     * @var ShippingRate[]
     */
    private $aShippingRatesEntities = null;
    /**
     * @var ShippingRate[]
     */
    protected $aShippingRates = null;

    /**
     * @var ShippingCarrier
     */
    private $oShippingCarrier = null;

    /**
     * @return boolean
     */
    abstract public function isProcessorApplicable();

    abstract public function getShippingQuotes();

    abstract public function getShippingQuotesCached();

    abstract public function saveShippingQuotesCached(Product $oProduct);

    abstract public function getServerQuotes($aShippingRates);

    abstract public function getAdditionalShippingFee($weight);

    public function __construct(Cart $oShippingCart)
    {
        $this->oCart = $oShippingCart;
    }

    public function getShippingRates()
    {
        if ($this->getCart() && $this->getCart()->getProductCount() > 0) {
            if ($this->isProcessorApplicable()) {
                $this->getShippingQuotesCached();
                $this->getShippingQuotes();
                if (!empty($this->aShippingRates)) {
                    foreach ($this->aShippingRates as $oShippingRate) {
                        $oShippingRate->setCart($this->getCart());
                    }
                    usort($this->aShippingRates, ['\Xcart\Shipping\ShippingProcessor', 'sortShippingRatesByCostAsc']);
                    $this->removeFromCart();
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
                addCondition("s.active = 'Y'")->
                addOrderBy("s.orderby")
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
     * @return Cart
     */
    protected function getCart()
    {
        return $this->oCart;
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

    public function removeFromCart()
    {
        $aProducts = $this->getCart()->getProducts();
        foreach ($aProducts as $aProduct) {
            $this->oCart->removeFromCart($aProduct['entity']);
        }
    }

    public function getPriority()
    {
        return $this->getShippingCarrier()->getPriority();
    }

    public function getShippingCarrier()
    {
        if (is_null($this->oShippingCarrier)){
            $this->oShippingCarrier = ShippingCarrier::model(['carrier_code' => (new \ReflectionClass($this))->getShortName()]);
        }
        return $this->oShippingCarrier;
    }
}
