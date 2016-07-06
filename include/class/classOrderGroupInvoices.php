<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrderGroupInvoice.php";

class classOrderGroupInvoices extends classData
{
    private $aGroupInvoices = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'invoice_number'];
        $this->sPrimaryTable = 'order_group_invoices';
        parent::__construct($aParams);
    }

    public function countOrderGroupInvoices() {
        $count = 0;
        if (!empty($this->aGroupInvoices))
            $count = count($this->aGroupInvoices);
        return $count;
    }

    public function getOrderGroupInvoices($aParams = [])
    {
        $aRes = func_query("SELECT * FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE orderid = " . $aParams['orderid'] . " AND manufacturerid = " . $aParams['manufacturerid']);
        if (!empty($aRes)) {
            foreach ($aRes as $aGroupInvoice) {
                $oGroupInvoice = new classOrderGroupInvoice();
                $oGroupInvoice->fillPrimaryTableValues($aGroupInvoice);
                $this->aGroupInvoices[] = $oGroupInvoice;
            }
        }
        return $this;
    }

    public function getOrderGroupInvoicesProductTotal()
    {
        $fRes = 0;
        if (!empty($this->aGroupInvoices)) {
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                $fRes+=floatval($oGroupInvoice->getField('products_total'));
            }
        }
        return $fRes;
    }

    public function getOrderGroupInvoicesShippingTotal()
    {
        $fRes = 0;
        if (!empty($this->aGroupInvoices)) {
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                $fRes+=floatval($oGroupInvoice->getField('shipping_total'));
            }
        }
        return $fRes;
    }

    public function getOrderGroupInvoicesHST()
    {
        $fRes = 0;
        if (!empty($this->aGroupInvoices)) {
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                $fRes+=floatval($oGroupInvoice->getField('HST_charged'));
            }
        }
        return $fRes;
    }
}