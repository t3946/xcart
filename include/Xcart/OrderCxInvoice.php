<?php

namespace Xcart;


class OrderCxInvoice extends Data
{
    public function __construct($aParam = null)
    {
        $this->aPrimaryKeys = ['orderid', 'invoice_order_number'];
        $this->sPrimaryTable = 'order_cx_invoices';

        parent::__construct($aParam);
    }
}