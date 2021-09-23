<?php

namespace Xcart;


class ShippingZone extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['zoneid'];
        $this->sPrimaryTable = 'zones';
        parent::__construct($aParams);
    }
}