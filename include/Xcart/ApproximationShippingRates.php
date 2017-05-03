<?php

namespace Xcart;


class ApproximationShippingRates extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['manufacturerid', 'state', 'last_updated_date'];
        $this->sPrimaryTable = 'approximation_shipping_rates';
        parent::__construct($aParams);

    }

    public function getId()
    {
        return $this->getField('id');
    }
}