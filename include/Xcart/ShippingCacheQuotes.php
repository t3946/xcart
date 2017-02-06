<?php

namespace Xcart;


class ShippingCacheQuotes extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['shipping_cache_id', 'rate_id'];
        $this->sPrimaryTable = 'shipping_cache_quotes';
        parent::__construct($aParams);
    }
}