<?php

namespace Xcart;


class CidevAmazonOrderRaw extends Data
{
    public function __construct($aData = null)
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'cidev_amazon_order_raw';

        parent::__construct($aData);
    }
}