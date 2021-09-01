<?php

namespace Xcart;


use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Models\ShippingProductModel;

class CartElement
{
    /**
     * @var ProductModel Product
     */
    private $oProduct = null;
    private $iQty = 0;
    private $weightRatio = null;

    public function __construct($oProduct, $iQty = 1)
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

    public function setProduct($oProduct)
    {
        $this->oProduct = $oProduct;
    }

    public function setWeightRation($value)
    {
        $this->weightRatio = $value;
    }

    public function getShippingWeightRatio($rate_id)
    {
        $this->weightRatio = 1;
        return $this->weightRatio;

        if ($this->weightRatio !== null) {
            return $this->weightRatio;
        }

        if ($productShipping = ShippingProductModel::objects()->get(
            [
                'product_id' => $this->getProduct()->productid,
                'shipping_rate_id' => $rate_id
            ]
        )) {
            $weightRatio = $productShipping->weight_ratio;
        }

        return $weightRatio;
    }
}