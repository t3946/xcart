<?php

namespace Xcart;


class CartElement
{
    /**
     * @var Product
     */
    private $oProduct = null;
    private $iQty = 0;

    public function __construct($oProduct, $iQty)
    {
        $this->oProduct = $oProduct;
        $this->iQty = $iQty;
    }

    public function getProduct()
    {
        return $this->oProduct;
    }

    public function getQuantity()
    {
        return $this->iQty;
    }

    public function setQuantity($iQty)
    {
        $this->iQty = $iQty;
    }
}