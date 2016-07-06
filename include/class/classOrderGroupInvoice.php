<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classOrderGroupInvoice extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'invoice_number'];
        $this->sPrimaryTable = 'order_group_invoices';
        parent::__construct($aParams);
    }
}