<?php

namespace Xcart;


class ProductsAmazonFields extends Data
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'products_amz_fields';
        $this->aPrimaryKeys = ['productid'];

        parent::__construct($iId);
    }

    public function getPreventSellingOnAmazon()
    {
        return $this->getField('prevent_selling_on_amazon');
    }
}