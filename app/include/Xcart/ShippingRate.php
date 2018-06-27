<?php

namespace Xcart;


class ShippingRate extends Data
{
    private $fShippingQuote;
    private $oShipping;
    private $fShippingCharge;
    private $fShippingChargeBeforeMAP;
    private $oCart;
    private $fCartShippingWeight;
    private $fAdditionalShippingCharge = 0;
    private $useMapPrice = true;
    /**
     * @var ShippingRate[] $aAddedShippingRates
     */
    private $aAddedShippingRates;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['rateid'];
        $this->sPrimaryTable = 'shipping_rates';
        parent::__construct($aParams);
    }

    public function setShippingChargeQuote($fCharge)
    {
        $this->fShippingQuote = (float)$fCharge;
        return $this;
    }

    public function getShippingQuote()
    {
        return $this->fShippingQuote;
    }

    public function getShippingId(): int
    {
        return (int)$this->getField('shippingid');
    }

    /**
     * @return Shipping
     */
    public function getShippingEntity(): Shipping
    {
        if ($this->oShipping === null) {
            $this->oShipping = Shipping::model(['shippingid' => $this->getField('shippingid')]);
        }
        return $this->oShipping;
    }

    /**
     * @param Shipping $oShipping
     * @return ShippingRate
     */
    public function setShippingEntity(Shipping $oShipping): ShippingRate
    {
        $this->oShipping = $oShipping;
        return $this;
    }

    public function getCostMarcup(): float
    {
        return (float)$this->getField('cost_marcup');
    }

    public function getRate(): float
    {
        return (float)$this->getField('rate');
    }

    public function getWeightRate(): float
    {
        return (float)$this->getField('weight_rate');
    }

    public function getItemRate(): float
    {
        return (float)$this->getField('item_rate');
    }

    public function getRateP(): float
    {
        return (float)$this->getField('rate_p');
    }

    public function getShippingCharge() :? float
    {
        if ($this->fShippingCharge === null && $this->fShippingQuote !== null) {
            $this->fShippingCharge = $this->fShippingQuote;
            if ($this->getCostMarcup() > 0) {
                $this->fShippingCharge *= $this->getCostMarcup();
            }
            $oCart = $this->getCart();

            $this->fShippingCharge += $this->getRate();
            $this->fShippingCharge += $oCart->getProductCount() * $this->getItemRate();
            $this->fShippingCharge += $oCart->getCost() * $this->getRateP()/100;
            $this->fShippingCharge += $this->getCartShippingWeight() * $this->getWeightRate();

            //$this->fShippingCharge += $this->fAdditionalShippingCharge;
            $this->fShippingCharge += $this->getCartShippingFreight();
            if ($this->useMapPrice && ($extra_margin = $oCart->getExtraMarginValue()) > 0) {
                $this->fShippingChargeBeforeMAP = $this->fShippingCharge;
                $this->fShippingCharge -= $extra_margin;
                $this->fShippingCharge = max($this->fShippingCharge, 0);
            }

            $this->fShippingCharge = min(max($this->fShippingCharge, $this->min_shipping_charge),  $this->max_shipping_charge);

            $this->fShippingCharge = round($this->fShippingCharge, 2);
        }
        return $this->fShippingCharge;
    }

    public function setShippingCharge($fValue): void
    {
        $this->fShippingCharge = (float)$fValue;
    }

    public function getShippingChargeBeforeMap(): float
    {
        return (float) $this->fShippingChargeBeforeMAP;
    }

    public function setShippingChargeBeforeMap($fValue): void
    {
        $this->fShippingChargeBeforeMAP = (float)$fValue;
    }

    public function addShippingCharge($oShippingRate): void
    {
        $this->getShippingCharge();
        $this->fShippingCharge += $oShippingRate->getShippingCharge();
        $this->aAddedShippingRates[] = $oShippingRate;
    }

    public function setCart(Cart $oCart)
    {
        $this->oCart = $oCart;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->oCart;
    }

    public function getCartShippingWeight(): float
    {
        if ($this->fCartShippingWeight === null) {
            $this->fCartShippingWeight = 0;
            $aCartObjects = $this->getCart()->getElements();
            if (!empty($aCartObjects)) {
                /** @var CartElement $oCartElement */
                foreach ($aCartObjects as $oCartElement) {

                    $this->fCartShippingWeight +=
                        $this->getShippingEntity()
                            ->getShippingWeightN(
                                $oCartElement->getProduct()->getShippingWeight($oCartElement->getQuantity()),
                                $oCartElement->getProduct()->getShippingVolume($oCartElement->getQuantity())
                            ) * $oCartElement->getShippingWeightRatio($this->rateid);
                }
            }
        }
        return round($this->fCartShippingWeight, 2);
    }

    public function getCartShippingFreight(): float
    {
        $shippingFreight = 0;
        $aCartObjects = $this->getCart()->getElements();
        if (!empty($aCartObjects)) {
            /** @var CartElement $oCartElement */
            foreach ($aCartObjects as $oCartElement) {
                $shippingFreight += $oCartElement->getProduct()->getShippingFreight() * $oCartElement->getQuantity();
            }
        }
        return (float) $shippingFreight;
    }

    public function checkShippingRateByFilterValues(): bool
    {
        $weight = $this->getCartShippingWeight();
        $total = $this->getCart()->getCost();
        $bResult = ($this->getField('minweight') <= $weight && $this->getField('maxweight') >= $weight) &&
            ($this->getField('mintotal') <= $total && $this->getField('maxtotal') >= $total) &&
            ($this->getField('maxamount') <= $this->getCart()->getProductCount());
        return $bResult;
    }

    public function setAdditionalShippingCharge($fShippingCharge): void
    {
        $this->fAdditionalShippingCharge = $fShippingCharge;
    }

    public function getSimilarShippingRateByDeliveryTime($aMinPriorityShippingRates)
    {
        return $this->getTimeDeliveryDiff($aMinPriorityShippingRates);
    }

    /**
     * @param ShippingRate[] $aMinPriorityShippingRates
     * @return int|null
     */
    public function getTimeDeliveryDiff($aMinPriorityShippingRates)
    {
        $i = null;
        if (!empty($aMinPriorityShippingRates)) {
            foreach ($aMinPriorityShippingRates as $key => $oMinPriorityShippingRate) {
                $oShipping = $oMinPriorityShippingRate->getShippingEntity();
                $oShippingThis = $this->getShippingEntity();
                $aResults [
                    abs((float)$oShipping->days_min - (float)$oShippingThis->days_min)
                    + abs((float)$oShipping->days_max - (float)$oShippingThis->days_max)
                ] = $key;
            }
            ksort($aResults);
            $i = array_shift($aResults);
        }
        return $i;
    }

    public function getAddedShippingRates()
    {
        return $this->aAddedShippingRates;
    }
    
    public function setUseMapPRice($value)
    {
        $this->useMapPrice = $value;
    }
}