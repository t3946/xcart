<?php

namespace Xcart\Shipping;


use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Shipping\Models\ShippingCacheLocationModel;
use Modules\Shipping\Models\ShippingCacheModel;
use Modules\Shipping\Models\ShippingCacheProductModel;
use Modules\Shipping\Models\ShippingCacheQuoteModel;
use Modules\User\Models\UserModel;
use Xcart\CartElement;
use Xcart\Manufacturer;
use Xcart\Shipping;
use Xcart\ShippingCarrier;
use Xcart\ShippingZone;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;
use Xcart\Cart;

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

    protected $useCache = true;

    protected $useMapPrice = true;

    protected $useApproximation = true;

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
                if (!empty($this->aShippingRates) && $this->useApproximation) {
                    foreach ($this->aShippingRates as $oShippingRate) {
                        $oShippingRate->setCart($this->getCart());
                        $oShippingRate->setUseMapPRice($this->useMapPrice);
                    }
                    usort($this->aShippingRates, function ($a, $b) {
                        return $a->getShippingCharge() > $b->getShippingCharge();
                    });
                    $this->removeFromCart();
                }
            }
        }
        return $this->aShippingRates;
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
        if (!$this->useCache) {
            return;
        }

        if (!empty($this->aShippingRates)) {
            $oCustomer = $this->getCustomer();
            $oManufacturer = $this->getManufacturer();
            $oCart = $this->getCart();

            /** @var ShippingCacheLocationModel $model */
            [$model] = ShippingCacheLocationModel::objects()->getOrCreate(
                [
                    'zip_to' => $oCustomer->s_zipcode ?: '',
                    'zip_from' => $oManufacturer->m_zipcode ?: '',
                    'state_to' => $oCustomer->s_state ?: '',
                    'state_from' => $oManufacturer->m_state ?: '',
                    'country_to' => $oCustomer->s_country ?: '',
                    'country_from' => $oManufacturer->m_country ?: '',
                ]
            );

            $location = new ShippingCacheModel([
                'shipping_location_id' => $model->shipping_location_id,
                'shipping_carrier' => (new \ReflectionClass($this))->getShortName(),
            ]);
            $location->save();

            foreach ($this->aShippingRates as $oShippingRate) {

                /** @var ShippingCacheQuoteModel $q */
                [$q] = ShippingCacheQuoteModel::objects()->getOrNew([
                    'shipping_cache_id' => $location->shipping_cache_id,
                    'rate_id' => $oShippingRate->rateid,
                ]);

                $q->setAttributes([
                    'shipping_quote' => $oShippingRate->getShippingQuote(),
                    'shipping_charge' => $oShippingRate->getShippingCharge(),
                    'shipping_charge_before_map' => $oShippingRate->getShippingChargeBeforeMap()
                ]);
                $q->save();
            }

            if (!empty($oCart->getElements())) {
                foreach ($oCart->getElements() as $oCartElement) {
                    ShippingCacheProductModel::objects()->getOrCreate([
                        'shipping_cache_id' => $location->shipping_cache_id,
                        'product_id' => $oCartElement->getProduct()->getProductId(),
                        'product_quantity' => $oCartElement->getQuantity(),
                    ]);
                }
            }
        }
    }

    protected function getShippingQuotesCached()
    {
        if (!$this->useCache) {
            return;
        }

        $aProductFilter = [];
        $oCustomer = $this->getCustomer();
        $oManufacturer = $this->getManufacturer();
        $oCart = $this->getCart();
        $sCarrierName = (new \ReflectionClass($this))->getShortName();

        $qsp = ShippingCacheProductModel::objects()->getQuerySet();

        $qs = ShippingCacheModel::objects()->getQuerySet();

        $qs->select(['*',
            'cnt_found' => (new Expression("COUNT(DISTINCT {$qsp->getTableAlias()}.product_id)"))->toSQL(),
            'cnt_total' => (new Expression('COUNT(DISTINCT xs2.product_id)'))->toSQL()
        ]);
        $qs->filter([
            'shipping_location__zip_to' => $oCustomer->s_zipcode ?: '',
            'shipping_location__zip_from' => $oManufacturer->m_zipcode ?: '',
            'shipping_location__state_to' => $oCustomer->s_state ?: '',
            'shipping_location__state_from' => $oManufacturer->m_state ?: '',
            'shipping_location__country_to' => $oCustomer->s_country ?: '',
            'shipping_location__country_from' => $oManufacturer->m_country ?: '',
            'shipping_carrier' => $sCarrierName,
        ]);

        foreach ($oCart->getElements() as $oCartElement) {
            $aProductFilter[] =
                new QAnd ([
                    'products__product_id' => $oCartElement->getProduct()->productid,
                    'products__product_quantity' => $oCartElement->getQuantity()
                ]);
        }
        $qs->filter([new QOr($aProductFilter)]);

        $qs->join('inner join', 'xcart_shipping_cache_products', ['shipping_cache_id' => "xs2.shipping_cache_id"], 'xs2');
        $qs->group(['shipping_cache_id']);

        $qs->having(new Expression('cnt_found = cnt_total AND cnt_total = ' . count($oCart->getElements())));

        /** @var ShippingCacheModel $cache_model */
        if ($cache_model = $qs->limit(1)->get()) {
            if ($quotes = $cache_model->quotes->all()) {
                foreach ($quotes as $quote) {
                    $rate = $quote->shipping_rate;
                    $rate->setShippingChargeQuote($quote->shipping_quote);
                    $rate->setCart($this->getCart());
                    $this->aShippingRates[] = $rate;
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
                            $aR1[] = $oShippingRate->rateid;
                        }
                        foreach ($aShippingRates as $oShippingRate) {
                            $aR2[] = $oShippingRate->rateid;
                        }
                        if (array_diff($aR1, $aR2)) {
                            $this->aShippingRates = null;
                        }
                    }
                    if (is_null($this->aShippingRates)) {
                        $cache_model->delete();
                    }
                }
            }
        }
        return;
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

    public function setUseCache($value)
    {
        $this->useCache = $value;
    }

    public function setUseMapPrice($value)
    {
        $this->useMapPrice = $value;
    }

    public function setUseApproximation($value)
    {
        $this->useApproximation = $value;
    }
}
