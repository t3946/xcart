<?php

namespace Xcart;


use Xcart\Shipping\ShippingCart;

class ShippingRate extends Data
{
    private $fShippingQuote = null;
    private $oShipping;
    private $fShippingCharge = null;
    private $oCart = null;
    private $fCartShippingWeight = null;

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
        if (!is_null($this->fShippingQuote)) {
            $this->fShippingCharge = $this->fShippingQuote;
            if ($this->getCostMarcup() > 0) {
                $this->fShippingCharge *= $this->getCostMarcup();
            }
            $oCart = $this->getCart();
            $this->fShippingCharge += $this->getRate();
            $this->fShippingCharge += $oCart->getProductCount() * $this->getItemRate();
            $this->fShippingCharge += $oCart->getCost() * $this->getRateP();
            $this->fShippingCharge += $this->getCartShippingWeight() * $this->getWeightRate();

            $this->fShippingCharge += $this->getCartShippingFreight();

        }
        return round($this->fShippingCharge, 2);
    }

    public function setCart(ShippingCart $oCart)
    {
        $this->oCart = $oCart;
    }

    /**
     * @return ShippingCart
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
        if ($this->getField('minweight') <= $weight && $this->getField('maxweight') >= $weight) {
            $bResult = true;
        } else {
            $bResult = false;
        }
        if ($this->getField('mintotal') <= $total && $this->getField('maxtotal') >= $total && $bResult) {
            $bResult = true;
        } else {
            $bResult = false;
        }
        if ($this->getField('maxamount') <= $this->getCart()->getProductCount() && $bResult) {
            $bResult = true;
        } else {
            $bResult = false;
        }
        return $bResult;
    }
}