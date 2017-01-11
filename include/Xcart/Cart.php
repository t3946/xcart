<?php

namespace Xcart;


class Cart
{
    private $aCart;
    private $fCost = null;
    private $iProductCount = null;
    private $fExtraMarginValue = null;
    private $aArrayOfObjects = null;

    public function __construct()
    {
        $this->aArrayOfObjects = new \ArrayObject();
    }
    public function addToCart(Product $oProduct, $qty)
    {
        if ($oProduct->getProductId()) {
            $this->aCart[$oProduct->getProductId()]['qty'] += $qty;
            $this->aCart[$oProduct->getProductId()]['entity'] = $oProduct;
        }
    }

    public function addObjectToCart(CartElement $oObject)
    {
        foreach ($this->aArrayOfObjects->getIterator() as $k => $v) {
            if ($v->getProduct()->getProductId() == $oObject->getProduct()->getProductId()) {
                $v->setQuantity($v->getQuantity() + $oObject->getQuantity());
                return $this;
            }
        }
        $this->aArrayOfObjects->append($oObject);
        return $this;
    }

    public function removeProductFromCart(Product $oProduct)
    {
        $iterator = $this->aArrayOfObjects->getIterator();
        foreach ($iterator as $k => $v) {
            if ($v->getProduct()->getProductId() == $oProduct->getProductId()) {
                $iterator->offsetUnset($k);
            }
        }
    }

    public function removeFromCart(Product $oProduct)
    {
        $aProducts = $this->getProducts();
        if (!empty($aProducts) && isset($this->aCart[$oProduct->getProductId()])) {
            unset($this->aCart[$oProduct->getProductId()]);
            $this->iProductCount = null;
        }
    }

    public function getProductCount()
    {
        if (is_null($this->iProductCount)) {
            $this->iProductCount = 0;
            if (!empty($this->aCart)) {
                foreach ($this->aCart as $aProduct) {
                    $this->iProductCount += $aProduct['qty'];
                }
            }
        }
        return $this->iProductCount;
    }

    public function getProducts()
    {
        return $this->aCart;
    }

    public function getCost()
    {
        if (is_null($this->fCost)) {
            $this->fCost = 0;
            if (!empty($this->aCart)) {
                foreach ($this->aCart as $aProduct) {
                    $this->fCost += $aProduct['entity']->getPrice() * $aProduct['qty'];
                }
            }
        }
        return $this->fCost;
    }

    public function getExtraMarginValue()
    {
        if (is_null($this->fExtraMarginValue) && !empty($this->aCart)) {
            foreach ($this->aCart as $aProduct) {
                $this->fExtraMarginValue += $aProduct['entity']->getExtraMarginValue($aProduct['qty']);
            }
        }
        return $this->fExtraMarginValue;
    }
}