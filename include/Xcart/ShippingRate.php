<?php

namespace Xcart;

class ShippingRate extends Data
{
    private $fShippingQuote = null;
    private $oShipping;
    private $fShippingCharge = null;
    private $oCart = null;
    private $fCartShippingWeight = null;
    private $fAdditionalShippingCharge = 0;

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
            $this->fShippingCharge += $oCart->getCost() * $this->getRateP();
            $this->fShippingCharge += $this->getCartShippingWeight() * $this->getWeightRate();
            $this->fShippingCharge += $this->fAdditionalShippingCharge;
            $this->fShippingCharge += $this->getCartShippingFreight();
            $this->fShippingCharge = round($this->fShippingCharge, 2);

            if ($oCart->getExtraMarginValue() > 0) {
                $this->fShippingCharge -= $oCart->getExtraMarginValue();
                $this->fShippingCharge = max($this->fShippingCharge, 0);
            }
        }
        return $this->fShippingCharge;
    }

    public function addShippingCharge($fCharge)
    {
        $this->getShippingCharge();
        $this->fShippingCharge += $fCharge;
    }

    public function setCart(\Xcart\Cart $oCart)
    {
        $this->oCart = $oCart;
    }

    /**
     * @return \Xcart\Cart
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
            $oCart = $this->getCart()->getProducts();
            if (!empty($oCart)) {
                foreach ($oCart as $aProducts) {
                    $this->fCartShippingWeight += $this->getShippingEntity()->getShippingWeightN(
                        $aProducts['entity']->getShippingWeight($aProducts['qty']),
                        $aProducts['entity']->getShippingVolume($aProducts['qty']));
                }
            }
        }
        return $this->fCartShippingWeight;
    }

    public function getCartShippingFreight()
    {
        $shippingFreight = 0;
        $oCart = $this->getCart()->getProducts();
        if (!empty($oCart)) {
            foreach ($oCart as $aProducts) {
                $shippingFreight += $aProducts['entity']->getShippingFreight() * $aProducts['qty'];
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
     * @param ShippingRate[] $aMinPriorityShippingRates
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
}