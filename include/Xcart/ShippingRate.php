<?php

namespace Xcart;

class ShippingRate extends Data
{
    private $fShippingQuote = null;
    private $oShipping;
    private $fShippingCharge = null;
    private $fShippingChargeBeforeMAP = null;
    private $oCart = null;
    private $fCartShippingWeight = null;
    private $fAdditionalShippingCharge = 0;
    /**
     * @var ShippingRate[]
     */
    private $aAddedShippingRates = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['rateid'];
        $this->sPrimaryTable = 'shipping_rates';
        parent::__construct($aParams);
    }

    public function setShippingChargeQuote($fCharge)
    {
        $this->fShippingQuote = floatval($fCharge);
        return $this;
    }

    public function getShippingQuote()
    {
        return $this->fShippingQuote;
    }

    public function getShippingId()
    {
        return intval($this->getField('shippingid'));
    }

    /**
     * @return Shipping
     */
    public function getShippingEntity()
    {
        if (is_null($this->oShipping)) {
            $this->oShipping = Shipping::model(['shippingid' => $this->getField('shippingid')]);
        }
        return $this->oShipping;
    }

    /**
     * @param Shipping $oShipping
     * @return ShippingRate
     */
    public function setShippingEntity(Shipping $oShipping)
    {
        $this->oShipping = $oShipping;
        return $this;
    }

    public function getCostMarcup()
    {
        return floatval($this->getField('cost_marcup'));
    }

    public function getRate()
    {
        return floatval($this->getField('rate'));
    }

    public function getWeightRate()
    {
        return floatval($this->getField('weight_rate'));
    }

    public function getItemRate()
    {
        return floatval($this->getField('item_rate'));
    }

    public function getRateP()
    {
        return floatval($this->getField('rate_p'));
    }

    public function getShippingCharge()
    {
        if (is_null($this->fShippingCharge) && !is_null($this->fShippingQuote)) {
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
            if ($oCart->getExtraMarginValue() > 0) {
                $this->fShippingChargeBeforeMAP = $this->fShippingCharge;
                $this->fShippingCharge -= $oCart->getExtraMarginValue();
                $this->fShippingCharge = max($this->fShippingCharge, 0);
            }
            $this->fShippingCharge = round($this->fShippingCharge, 2);
        }
        return $this->fShippingCharge;
    }

    public function setShippingCharge($fValue)
    {
        $this->fShippingCharge = floatval($fValue);
    }

    public function getShippingChargeBeforeMap()
    {
        return $this->fShippingChargeBeforeMAP;
    }

    public function setShippingChargeBeforeMap($fValue)
    {
        return $this->fShippingChargeBeforeMAP = floatval($fValue);
    }


    public function addShippingCharge(ShippingRate $oShippingRate)
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

    /**
     * @return float
     */
    public function getCartShippingWeight()
    {
        if (is_null($this->fCartShippingWeight)) {
            $this->fCartShippingWeight = 0;
            $aCartObjects = $this->getCart()->getElements();
            if (!empty($aCartObjects)) {
                /** @var CartElement $oCartElement */
                foreach ($aCartObjects as $oCartElement) {
                    $this->fCartShippingWeight += $this->getShippingEntity()->getShippingWeightN(
                        $oCartElement->getProduct()->getShippingWeight($oCartElement->getQuantity()),
                        $oCartElement->getProduct()->getShippingVolume($oCartElement->getQuantity()));
                }
            }
        }
        return $this->fCartShippingWeight;
    }

    public function getCartShippingFreight()
    {
        $shippingFreight = 0;
        $aCartObjects = $this->getCart()->getElements();
        if (!empty($aCartObjects)) {
            /** @var CartElement $oCartElement */
            foreach ($aCartObjects as $oCartElement) {
                $shippingFreight += $oCartElement->getProduct()->getShippingFreight() * $oCartElement->getQuantity();
            }
        }
        return $shippingFreight;
    }

    public function checkShippingRateByFilterValues()
    {
        $weight = $this->getCartShippingWeight();
        $total = $this->getCart()->getCost();
        $bResult = ($this->getField('minweight') <= $weight && $this->getField('maxweight') >= $weight) &&
            ($this->getField('mintotal') <= $total && $this->getField('maxtotal') >= $total) &&
            ($this->getField('maxamount') <= $this->getCart()->getProductCount());
        return $bResult;
    }

    public function setAdditionalShippingCharge($fShippingCharge)
    {
        $this->fAdditionalShippingCharge = $fShippingCharge;
    }

    public function getSimilarShippingRateByDeliveryTime($aMinPriorityShippingRates)
    {
        return $this->getTimeDeliveryDiff($aMinPriorityShippingRates);
    }

    /**
     * @param ShippingRate[] $aMinPriorityShippingRates\
     * @return int|null
     */
    public function getTimeDeliveryDiff($aMinPriorityShippingRates)
    {
        $i = null;
        if (!empty($aMinPriorityShippingRates)) {
            foreach ($aMinPriorityShippingRates as $key => $oMinPriorityShippingRate) {
                $oShipping = $oMinPriorityShippingRate->getShippingEntity();
                $oShippingThis = $this->getShippingEntity();
                $aResults [(abs(floatval($oShipping->getField('days_min')) - floatval($oShippingThis->getField('days_min'))) +
                    abs(floatval($oShipping->getField('days_max')) - floatval($oShippingThis->getField('days_max'))))] = $key;
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

    public function getDataToSave(){
        return ['rate_id' => $this->getField('rateid'),
            'shipping_quote' => $this->getShippingQuote(),
            'shipping_charge' => $this->getShippingCharge(),
            'shipping_charge_before_map' => $this->getShippingChargeBeforeMap()];
    }
}