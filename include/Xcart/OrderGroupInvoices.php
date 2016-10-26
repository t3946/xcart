<?php
namespace Xcart;

class OrderGroupInvoices extends Data
{
    /**
     * @var OrderGroupInvoice[]
     */
    private $aGroupInvoices = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'invoice_number'];
        $this->sPrimaryTable = 'order_group_invoices';
        parent::__construct($aParams);
    }

    public function countOrderGroupInvoices()
    {
        $count = 0;
        if (!empty($this->aGroupInvoices))
            $count = count($this->aGroupInvoices);
        return $count;
    }

    public function countOrderGroupInvoicesReconciled()
    {
        $count = 0;
        if (!empty($this->aGroupInvoices))
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                if ($oGroupInvoice->getReconcileStatus() == 'R')
                    $count ++;
            }
        return $count;
    }

    public function getOrderGroupInvoices($aParams = [])
    {
        if (empty($this->aGroupInvoices)) {
            $aRes = func_query("SELECT * FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE ".str_replace('&',' AND ',http_build_query($aParams)). " ORDER BY invoice_number");
            if (!empty($aRes)) {
                foreach ($aRes as $aGroupInvoice) {
                    $oGroupInvoice = new OrderGroupInvoice();
                    $oGroupInvoice->fill($aGroupInvoice);
                    $this->aGroupInvoices[] = $oGroupInvoice;
                }
            }
        }
        return $this;
    }

    public function getOrderGroupInvoicesProductTotal()
    {
        $fRes = 0;
        if (!empty($this->aGroupInvoices)) {
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                $fRes += $oGroupInvoice->getOrderGroupInvoiceProductsTotal();
            }
        }
        return $fRes;
    }

    public function getOrderGroupInvoicesShippingTotal()
    {
        $fRes = 0;
        if (!empty($this->aGroupInvoices)) {
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                $fRes += $oGroupInvoice->getOrderGroupInvoicesShippingTotal();
            }
        }
        return $fRes;
    }

    public function getOrderGroupInvoicesHST()
    {
        $fRes = 0;
        if (!empty($this->aGroupInvoices)) {
            foreach ($this->aGroupInvoices as $oGroupInvoice) {
                $fRes += $oGroupInvoice->getOrderGroupInvoicesHST();
            }
        }
        return $fRes;
    }

    public function getLastInvoice()
    {
        $oLastInvoice = null;
        if (!empty($this->aGroupInvoices)) {
            $oLastInvoice = end($this->aGroupInvoices);
        }
        return $oLastInvoice;
    }
    /**
     * @param OrderGroupInvoice $oLastInvoice
     * @return OrderGroupInvoices
     */
    public function createCloneInvoice(OrderGroupInvoice $oInvoice)
    {
        $oCloneInvoice = $oInvoice->_clone();
        $oCloneInvoice->setInvoiceNumber($oInvoice->getInvoiceNumber() + 1);
        $this->aGroupInvoices[] = $oCloneInvoice;
        return $this;
    }
}