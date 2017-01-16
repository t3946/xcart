<?php

namespace Xcart\Shipping;


use Xcart\CartElement;
use Xcart\Manufacturer;
use Xcart\ShippingCache;
use Xcart\ShippingCacheProducts;
use Xcart\ShippingCarrier;
use Xcart\ShippingZone;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;
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
     * @var Cart
     */
    protected $oCarierCart = null;

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

    protected $bGetOnlyApproximationRates = false;

    /**
     * @return boolean
     */
    abstract public function isProcessorApplicable();

    abstract public function getShippingQuotes();

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
    public function getCart()
    {
        $oCart = new Cart();
        $aProducts = $this->oCart->getElements();
        if (!empty($aProducts)) {
            foreach ($aProducts as $oCartElement) {
                $oCart->addObjectToCart($oCartElement);
            }
        }
        return $oCart;
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
        $aProducts = $this->getCart()->getElements();
        if (!empty($aProducts)) {
            /** @var CartElement $oCartElement */
            foreach ($aProducts as $oCartElement) {
                $this->oCart->removeProductFromCart($oCartElement->getProduct());
            }
        }
    }

    public function getPriority()
    {
        return $this->getShippingCarrier()->getPriority();
    }

    public function getShippingCarrier()
    {
        if (is_null($this->oShippingCarrier)) {
            $this->oShippingCarrier = ShippingCarrier::model(['carrier_code' => (new \ReflectionClass($this))->getShortName()]);
        }
        return $this->oShippingCarrier;
    }

    protected function saveShippingQuotesCached()
    {
        if (!empty($this->aShippingRates)) {
            $oCustomer = $this->getCustomer();
            $oManufacturer = $this->getManufacturer();
            $oCart = $this->getCart();
            if (empty($aShippingCache)) {
                $iShippingCacheId = ShippingCache::model()->fill(
                    ['shipping_carrier' => (new \ReflectionClass($this))->getShortName(),
                        'zip_to' => $oCustomer->getField('s_zipcode'),
                        'zip_from' => $oManufacturer->getField('m_zipcode'),
                        'state_to' => $oCustomer->getField('s_state'),
                        'state_from' => $oManufacturer->getField('m_state'),
                        'country_to' => $oCustomer->getField('s_country'),
                        'country_from' => $oManufacturer->getField('m_country'),
                        'shipping_rates' => addslashes(serialize($this->aShippingRates))]
                )->_insert();
                if ($iShippingCacheId) {
                    $aProducts = $oCart->getElements();
                    if (!empty($aProducts)) {
                        /** @var CartElement $oCartElement */
                        foreach ($aProducts as $oCartElement) {
                            ShippingCacheProducts::model()->fill(
                                [
                                    'shipping_cache_id' => $iShippingCacheId,
                                    'product_id' => $oCartElement->getProduct()->getProductId(),
                                    'product_quantity' => $oCartElement->getQuantity(),
                                ]
                            )->_insert();
                        }
                    }
                }
            }
        }
    }

    protected function getShippingQuotesCached()
    {
        $aProductFilter = [];
        $oCustomer = $this->getCustomer();
        $oManufacturer = $this->getManufacturer();
        $oCart = $this->getCart();
        $sCarrierName = (new \ReflectionClass($this))->getShortName();
        $aCartElements = $oCart->getElements();
        if (!empty($aCartElements)) {
            /** @var CartElement $oCartElement */
            foreach ($aCartElements as $oCartElement) {
                $aProductFilter[] = " (xs1.product_id = {$oCartElement->getProduct()->getProductId()} AND xs1.product_quantity = {$oCartElement->getQuantity()}) ";
            }
            $sProductFilter = implode(' OR ', $aProductFilter);

            $oSQLBuilder = SQLBuilder::getInstance()->addSelect('xs.shipping_cache_id, count(DISTINCT xs1.product_id) as cnt_found, count(DISTINCT xs2.product_id) as cnt_total')->
            addFromTable('shipping_cache_simple', 'xs')->
            addCondition("zip_to='{$oCustomer->getField('s_zipcode')}'")->
            addCondition("zip_from='{$oManufacturer->getField('m_zipcode')}'")->
            addCondition("state_to='{$oCustomer->getField('s_state')}'")->
            addCondition("state_from='{$oManufacturer->getField('m_state')}'")->
            addCondition("country_to='{$oCustomer->getField('s_country')}'")->
            addCondition("country_from='{$oManufacturer->getField('m_country')}'")->
            addCondition("shipping_carrier='{$sCarrierName}'")->
            addInnerJoin('shipping_cache_products', 'xs1', "xs1.shipping_cache_id = xs.shipping_cache_id AND {$sProductFilter}")->
            addInnerJoin('shipping_cache_products', 'xs2', "xs2.shipping_cache_id = xs.shipping_cache_id ");


            $res = $oSQLBuilder->query()->getQueryResult();
            if (!empty($res)) {
                $oShippingCache = null;
                foreach ($res as $aShippingCacheRes) {
                    if ($aShippingCacheRes['cnt_total'] == count($aCartElements)) {
                        $oShippingCache = ShippingCache::model(['shipping_cache_id' => $aShippingCacheRes['shipping_cache_id']]);
                        break;
                    }
                }
                if ($oShippingCache && $oShippingCache->getField('shipping_cache_id')) {
                    $this->aShippingRates = unserialize($oShippingCache->getField('shipping_rates'));
                }
            }
        }
        return $this->aShippingRates;
    }

    public function setGetOnlyApproximationRates($bValue)
    {
        $this->bGetOnlyApproximationRates = $bValue;
    }
}
