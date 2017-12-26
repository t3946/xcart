<?php

namespace Xcart;


class FilterProduct extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['productid', 'fv_id'];
        $this->sPrimaryTable = 'cidev_filter_products';
        parent::__construct($aParams);
    }
}