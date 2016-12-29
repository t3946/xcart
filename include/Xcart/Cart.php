<?php

namespace Xcart;


class Cart
{
    private $aCart;
    private $fCost = null;
    private $iCount = null;
    private $fExtraMarginValue = null;

    public function addToCart(Product $oProduct, $qty)
    {
        if ($oProduct->getProductId()) {
            $this->aCart[$oProduct->getProductId()]['qty'] += $qty;
            $this->aCart[$oProduct->getProductId()]['entity'] = $oProduct;
        }
    }

    public function removeFromCart(Product $oProduct)
    {
        $aProducts = $this->getProducts();
        if (!empty($aProducts) && isset($this->aCart[$oProduct->getProductId()])) {
            unset($this->aCart[$oProduct->getProductId()]);
            $this->iCount = null;
        }
    }

    public function getProductCount()
    {
        if (is_null($this->iCount)) {
            $this->iCount = 0;
            if (!empty($this->aCart)) {
                foreach ($this->aCart as $aProduct) {
                    $this->iCount += $aProduct['qty'];
                }
            }
        }
        return $this->iCount;
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