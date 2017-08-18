<?php

namespace Xcart;


use Modules\Shipping\Models\ShippingProductModel;

class CartElement
{
    /**
     * @var Product
     */
    private $oProduct = null;
    private $iQty = 0;

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
        $weightRatio = 1;

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