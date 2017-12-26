<?php

namespace Xcart;

class OrderStatus extends Data
{
    public function __construct($aOrderData = null)
    {
        $this->aPrimaryKeys = ['code'];
        $this->sPrimaryTable = 'order_statuses';

        parent::__construct($aOrderData);
    }

    public function getName()
    {
        return $this->getField('name');
    }
}