<?php

namespace Xcart\Shipping;


use Modules\User\Models\UserModel;
use Xcart\CartElement;
use Xcart\Manufacturer;
use Xcart\Shipping;
use Xcart\ShippingCache;
use Xcart\ShippingCacheProducts;
use Xcart\ShippingCarrier;
use Xcart\ShippingZone;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;
use Xcart\Customer;
use Xcart\Cart;
use Xcart\ShippingCacheQuotes;

abstract class ShippingProcessor
{
    protected $oManufacturer = null;
    private $oShippingZone = null;
    private $sShippingType = null;
    /**
     * @var UserModel
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
     * @var Shipping[]
     */
    private $aShippingMethods = null;

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

    public function getShippingRateFilterValues(Shipping $oShipping)
    {
        $weight = $this->getCartShippingWeight($oShipping);
        $total = $this->getCart()->getCost();
        $count = $this->getCart()->getProductCount();
        $sResult = " {$weight} BETWEEN minweight AND maxweight AND {$total} BETWEEN mintotal AND maxtotal AND maxamount <= {$count} ";
        return $sResult;
    }

    public function getCartShippingWeight(Shipping $oShipping)
    {
        $fCartShippingWeight = 0;
        $aCartObjects = $this->getCart()->getElements();
        if (!empty($aCartObjects)) {
            /** @var CartElement $oCartElement */
            foreach ($aCartObjects as $oCartElement) {
                $fCartShippingWeight += $oShipping->getShippingWeightN(
                    $oCartElement->getProduct()->getShippingWeight($oCartElement->getQuantity()),
                    $oCartElement->getProduct()->getShippingVolume($oCartElement->getQuantity()));
            }
        }
        return $fCartShippingWeight;
    }

    /**
     * @return ShippingRate[]
     */
    public function getShippingRatesEntities()
    {
        if (is_null($this->aShippingRatesEntities)) {
            if (!empty($this->aShippingMethods)) {
                foreach ($this->aShippingMethods as $oShipping) {
                    $aResults = ShippingRate::model()->findAll(
                        SQLBuilder::getInstance()->addSelect('*')->
                        addFromTable('shipping_rates')->
                        addCondition('zoneid = ' . $this->getShippingZone()->zoneid)->
                        addCondition('shippingid = ' . $oShipping->getShippingId())->
                        addCondition('manufacturerid = ' . $this->getManufacturer()->getManufacturerId())->
                        addCondition($this->getShippingRateFilterValues($oShipping)));
                    if (!empty($aResults)) {
                        foreach ($aResults as $oShippingRate) {
                            $oShippingRate->setShippingEntity($oShipping);
                            $oShippingRate->setCart($this->getCart());
                            $this->aShippingRatesEntities[] = $oShippingRate;
                        }
                    }
                }
            }
        }
        return $this->aShippingRatesEntities;
    }

    public function addShippingRate(ShippingRate $oShippingRate)
    {
        $oShippingRate->setCart($this->getCart());
        if ($oShippingRate->checkShippingRateByFilterValues()) {
            $this->aShippingRatesEntities[] = $oShippingRate;
        }
        return $this;
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
     * @return UserModel
     */
    public function getCustomer()
    {
        return $this->oCustomer;
    }

    /**
     * @param UserModel $oCustomer
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
            $iShippingCacheId = ShippingCache::model()->fill(
                ['shipping_carrier' => (new \ReflectionClass($this))->getShortName(),
                    'zip_to' => $oCustomer->getField('s_zipcode'),
                    'zip_from' => $oManufacturer->getField('m_zipcode'),
                    'state_to' => $oCustomer->getField('s_state'),
                    'state_from' => $oManufacturer->getField('m_state'),
                    'country_to' => $oCustomer->getField('s_country'),
                    'country_from' => $oManufacturer->getField('m_country'),
                    //'shipping_rates' => base64_encode(addslashes(gzcompress(serialize($this->aShippingRates)))),
                    'compressed' => 0]
            )->_insert();
            if ($iShippingCacheId) {
                foreach ($this->aShippingRates as $oShippingRate){
                    $aCacheQutes = $oShippingRate->getDataToSave();
                    ShippingCacheQuotes::model()->fill([
                        'shipping_cache_id' => $iShippingCacheId,
                        'rate_id' => $oShippingRate->getField('rateid'),
                        'shipping_quote' => $aCacheQutes['shipping_quote'],
                        'shipping_charge' => $aCacheQutes['shipping_charge'],
                        'shipping_charge_before_map' => $aCacheQutes['shipping_charge_before_map']
                    ])->_insert();
                }

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

            $oSQLBuilder = SQLBuilder::getInstance()->addSelect('xs.*, count(DISTINCT xs1.product_id) as cnt_found, count(DISTINCT xs2.product_id) as cnt_total')->
            addFromTable('shipping_cache_simple', 'xs')->
            addCondition("zip_to='{$oCustomer->getField('s_zipcode')}'")->
            addCondition("zip_from='{$oManufacturer->getField('m_zipcode')}'")->
            addCondition("state_to='{$oCustomer->getField('s_state')}'")->
            addCondition("state_from='{$oManufacturer->getField('m_state')}'")->
            addCondition("country_to='{$oCustomer->getField('s_country')}'")->
            addCondition("country_from='{$oManufacturer->getField('m_country')}'")->
            addCondition("shipping_carrier='{$sCarrierName}'")->
            addInnerJoin('shipping_cache_products', 'xs1', "xs1.shipping_cache_id = xs.shipping_cache_id AND {$sProductFilter}")->
            addInnerJoin('shipping_cache_products', 'xs2', "xs2.shipping_cache_id = xs.shipping_cache_id ")->
            addGroupBy('xs.shipping_cache_id')->
            addHaving('cnt_found = cnt_total AND cnt_total = ' . count($aCartElements));

            $res = $oSQLBuilder->query_first()->getQueryResult();
            if (!empty($res)) {
                $oShippingCache = ShippingCache::model()->fill([
                    'shipping_cache_id' => $res['shipping_cache_id'],
                    'zip_from' => $res['zip_from'],
                    'zip_to' => $res['zip_to'],
                    'state_from' => $res['state_from'],
                    'state_to' => $res['state_to'],
                    'country_from' => $res['country_from'],
                    'country_to' => $res['country_to'],
                    'shipping_rates' => $res['shipping_rates'],
                    'shipping_carrier' => $res['shipping_carrier'],
                    'cache_date' => $res['cache_date'],
                    'compressed' => $res['compressed']
                ]);

                if ($oShippingCache && $oShippingCache->getField('shipping_cache_id')) {
                    if ($oShippingCache->getField('compressed')) {
                        $this->aShippingRates = unserialize(gzuncompress(stripslashes(base64_decode($oShippingCache->getField('shipping_rates')))));
                    } else {
                        $aShippingCacheQuotes = ShippingCacheQuotes::model()->findAll(SQLBuilder::getInstance()->addCondition('shipping_cache_id = '.$oShippingCache->getField('shipping_cache_id')));
                        if (!empty($aShippingCacheQuotes)) {
                            foreach ($aShippingCacheQuotes as $oShippingCacheQuotes){
                                $oShippingRate = ShippingRate::model(['rateid' => $oShippingCacheQuotes->getField('rate_id')]);
                                if ($oShippingRate->getField('rateid')) {
                                    $oShippingRate->setShippingChargeQuote($oShippingCacheQuotes->getField('shipping_quote'));
                                    //$oShippingRate->setShippingCharge($oShippingCacheQuotes->getField('shipping_charge'));
                                    //$oShippingRate->setShippingChargeBeforeMap($oShippingCacheQuotes->getField('shipping_charge_before_map'));
                                    $oShippingRate->setCart($this->getCart());
                                    $this->aShippingRates[] = $oShippingRate;
                                }
                            }
                        }
                    }
                }

                if (!empty($this->aShippingRates) && !$this->bGetOnlyApproximationRates) {
                    $aShippingRates = $this->getShippingRatesEntities();
                    if (!empty($aShippingRates)) {
                        if (count($this->aShippingRates) != count($aShippingRates)) {
                            $this->aShippingRates = null;
                        } else {
                            $aR1 = $aR2 = [];
                            foreach ($this->aShippingRates as $oShippingRate) {
                                $aR1[] = $oShippingRate->getField('rateid');
                            }
                            foreach ($aShippingRates as $oShippingRate) {
                                $aR2[] = $oShippingRate->getField('rateid');
                            }
                            $aDiff = array_diff($aR1, $aR2);
                            if (!empty($aDiff)){
                                $this->aShippingRates = null;
                            }
                        }
                        if (is_null($this->aShippingRates)) {
                            $oShippingCache->_delete();
                        }
                    }
                }
            }
        }
        return $this->aShippingRates;
    }

    public function setGetOnlyApproximationRates($bValue)
    {
        $this->bGetOnlyApproximationRates = $bValue;
    }

    public function addShippingMethod(Shipping $oShipping)
    {
        if (empty($this->aShippingMethods[$oShipping->getShippingId()])) {
            $this->aShippingMethods[$oShipping->getShippingId()] = $oShipping;
        }
        return $this;
    }
}
