<?php

namespace Xcart;


class FilterProduct extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['fp_id'];
        $this->sPrimaryTable = 'cidev_filter_products';
        parent::__construct($aParams);
    }
}