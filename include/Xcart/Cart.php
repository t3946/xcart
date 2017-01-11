<?php

namespace Xcart;


class Cart
{
    private $fCost = null;
    private $iProductCount = null;
    private $fExtraMarginValue = null;
    private $aArrayOfObjects = null;

    public function __construct()
    {
        $this->aArrayOfObjects = new \ArrayObject();
    }

    public function addObjectToCart(CartElement $oObject)
    {
        if ($oObject->getProduct()->getProductId() && $oObject->getQuantity()) {
            $iterator = $this->aArrayOfObjects->getIterator();
            if (!empty($iterator)) {
                foreach ($iterator as $k => $v) {
                    if ($v->getProduct()->getProductId() == $oObject->getProduct()->getProductId()) {
                        $v->setQuantity($v->getQuantity() + $oObject->getQuantity());
                        return $this;
                    }
                }
            }
            $this->aArrayOfObjects->append($oObject);
        }
        return $this;
    }

    public function removeProductFromCart(Product $oProduct)
    {
        $iterator = $this->aArrayOfObjects->getIterator();
        if (!empty($iterator)) {
            foreach ($iterator as $k => $v) {
                if ($v->getProduct()->getProductId() == $oProduct->getProductId()) {
                    $iterator->offsetUnset($k);
                    $this->iCount = null;
                }
            }
        }
    }

    public function getElements()
    {
        return $this->aArrayOfObjects->getIterator();
    }

    public function getProductCount()
    {
        if (is_null($this->iProductCount)) {
            $this->iProductCount = 0;
            $iterator = $this->aArrayOfObjects->getIterator();
            if (!empty($iterator)) {
                foreach ($iterator as $k => $v) {
                    $this->iProductCount += $v->getQuantity();
                }
            }
        }
        return $this->iProductCount;
    }

    public function getCost()
    {
        if (is_null($this->fCost)) {
            $this->fCost = 0;
            $iterator = $this->aArrayOfObjects->getIterator();
            if (!empty($iterator)) {
                foreach ($iterator as $k => $v) {
                    $this->fCost += $v->getProduct()->getPrice() * $v->getQuantity();
                }
            }
        }
        return $this->fCost;
    }

    public function getExtraMarginValue()
    {
        if (is_null($this->fExtraMarginValue)) {
            $iterator = $this->aArrayOfObjects->getIterator();
            if (!empty($iterator)) {
                foreach ($iterator as $k => $v) {
                    $this->fExtraMarginValue += $v->getProduct()->getExtraMarginValue($v->getQuantity());
                }
            }
        }
        return $this->fExtraMarginValue;
    }
}