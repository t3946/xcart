<?php
/**
 * Created by PhpStorm.
 * User: User01
 * Date: 30.12.2016
 * Time: 11:52
 */

namespace Xcart;


class ShippingCache extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['shipping_cache_id'];
        $this->sPrimaryTable = 'shipping_cache_simple';
        parent::__construct($aParams);
    }
}