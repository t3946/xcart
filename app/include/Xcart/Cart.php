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
        if ($oObject->getProduct() && $oObject->getQuantity()) {
            $iterator = $this->aArrayOfObjects->getIterator();
            if (!empty($iterator)) {
                foreach ($iterator as $k => $v) {
                    if ($v->getProduct()->productid == $oObject->getProduct()->productid) {
                        $v->setQuantity($v->getQuantity() + $oObject->getQuantity());
                        $this->iProductCount = null;
                        return $this;
                    }
                }
            }
            $this->aArrayOfObjects->append($oObject);
            $this->iProductCount = null;
        }
        return $this;
    }

    public function removeProductFromCart($oProduct)
    {
        $iterator = $this->aArrayOfObjects->getIterator();
        if (!empty($iterator)) {
            foreach ($iterator as $k => $v) {
                if ($v->getProduct()->getProductId() == $oProduct->getProductId()) {
                    $iterator->offsetUnset($k);
                    $this->iProductCount = null;
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
        if ($this->iProductCount === null) {
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
        if ($this->fCost === null) {
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
        $fExtraMarginValue = null;
        $iterator = $this->aArrayOfObjects->getIterator();
            if (!empty($iterator)) {
                foreach ($iterator as $k => $v) {
                    $fExtraMarginValue += $v->getProduct()->getExtraMarginValue($v->getQuantity());
                }
            }
        return $fExtraMarginValue;
    }
}