<?php

namespace Xcart;


class ShippingRate extends Data
{
    private $fShippingCharge = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['rateid'];
        $this->sPrimaryTable = 'shipping_rates';
        parent::__construct($aParams);
    }

    public function setShippingCharge($fCharge)
    {
        $this->fShippingCharge = $fCharge;
    }

    public function getShippingCharge()
    {
        return $this->fShippingCharge;
    }
}