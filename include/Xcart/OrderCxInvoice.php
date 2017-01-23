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

    public function getInvoiceDate()
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $this->getField('invoice_date'));
    }
}