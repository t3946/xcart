<?php

namespace Xcart;


class ProductAmazonRates extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['product_id', 'shipping_id', 'state_id'];
        $this->sPrimaryTable = 'products_amazon_rates';
        parent::__construct($aParams);

    }
}