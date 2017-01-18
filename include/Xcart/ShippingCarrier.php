<?php

namespace Xcart;


class ShippingCarrier extends Data
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'shipping_carrier';
        $this->aPrimaryKeys = ['carrier_code'];
        parent::__construct($iId);
    }

    public function getPriority()
    {
        return $this->getField('priority');
    }

    public function getName()
    {
        return $this->getField('carrier_code');
    }
}