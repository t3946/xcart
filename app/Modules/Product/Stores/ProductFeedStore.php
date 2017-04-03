<?php
namespace Modules\Product\Stores;

use Modules\Product\Models\ProductModel;

class ProductFeedStore extends ProductModel
{
    public function setSku($value)
    {
        $this->productcode = $value;
    }

    public function setQuantity($value)
    {
        $this->r_avail = $value;
    }

    public function setEtaDate($value)
    {
        $this->eta_date_mm_dd_yyyy = $value;
    }

    public function setTitle($value)
    {
        $this->product = $value;
    }

    public function setListprice($value)
    {
        $this->list_price = $value;
    }
}