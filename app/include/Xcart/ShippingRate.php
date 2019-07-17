<?php

namespace Xcart;


use Modules\Shipping\Helpers\ShippingHelper;

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

    public function getCartShippingDimentions(): array
    {

        $volume = 0;
        if ($aCartObjects = $this->getCart()->getElements()) {
            $widthRange = [];
            $heightRange = [];
            $depthRange = [];
            foreach ($aCartObjects as $oCartElement) {
                $product = $oCartElement->getProduct();
                $volume += $product->getVolume() * $oCartElement->getQuantity();
                for($i = 0; $i < $oCartElement->getQuantity(); $i++) {
                    $widthRange[] = $product->shipping_dim_x;
                    $heightRange[] = $product->shipping_dim_y;
                    $depthRange[] = $product->shipping_dim_z;
                }
            }

            if ((float) $volume === (float) 0) {
                return [];
            }

            sort($widthRange);
            sort($heightRange);
            sort($depthRange);

            $widthCombination = ShippingHelper::combination($widthRange);
            $heightCombination = ShippingHelper::combination($heightRange);
            $depthCombination = ShippingHelper::combination($depthRange);

            $stacks = [];
            foreach($widthCombination as $width) {
                foreach($heightCombination as $height) {
                    foreach($depthCombination as $depth) {
                        $v = round($width*$height*$depth,2);
                        if($v >= $volume) {
                            $stacks[$v][$width+$height+$depth] = [$width, $height, $depth];
                        }
                    }
                }
            }

            ksort($stacks);

            foreach($stacks as $i => $dims) {
                ksort($stacks[$i]);
                foreach($stacks[$i] as $j => $stack) {
                    rsort($stack);
                    break;
                }
                break;
            }

            if ($stacks) {
                if ($r = reset($stacks)) {
                    return reset($r);
                }
            }
        }
        return [];
    }

    public function getCartShippingWeight(): float
    {
        $weight = 0;
        if ($aCartObjects = $this->getCart()->getElements()) {
            /** @var CartElement $oCartElement */
            foreach ($aCartObjects as $oCartElement) {
                $weight += $oCartElement->getProduct()->getShippingWeight() * $oCartElement->getQuantity();
            }
        }
        return $weight;
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