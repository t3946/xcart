<?php

namespace Xcart;

class ShippingCacheProducts extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['shipping_cache_id', 'product_id', 'product_quantity'];
        $this->sPrimaryTable = 'shipping_cache_products';
        parent::__construct($aParams);
    }
}