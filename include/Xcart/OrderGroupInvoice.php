<?php
namespace Xcart;

class OrderGroupInvoice extends Data
{
    /**
     * @var Reconciliation
     */
    private $oReconciliation = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'invoice_number'];
        $this->sPrimaryTable = 'order_group_invoices';
        parent::__construct($aParams);
    }

    public function setInvoiceNumber($iInvoiceNumber) {
        $this->setField('invoice_number',$iInvoiceNumber);
        return $this;
    }

    public function getInvoiceNumber() {
        return $this->getField('invoice_number');
    }

    public function getOrderGroupInvoiceProductsTotal() {
        return floatval($this->getField('products_total'));
    }

    public function setOrderGroupInvoiceProductsTotal($fSumma) {
        $this->setField('products_total', $fSumma);
        return $this;
    }

    public function getCostToUsForProductsCharged() {
        return floatval($this->getField('cost_to_us_for_products_charged'));
    }

    public function setCostToUsForProductsCharged($fSumma) {
        $this->setField('cost_to_us_for_products_charged',$fSumma);
        return $this;
    }

    public function getTaxChargedExceptHST() {
        return $this->getField('tax_charged_except_HST');
    }

    public function setTaxChargedExceptHST($fSumma) {
        $this->setField('tax_charged_except_HST',$fSumma);
        return $this;
    }

    public function getOrderGroupInvoicesShippingTotal() {
        return floatval($this->getField('shipping_total'));
    }

    public function setOrderGroupInvoicesShippingTotal($fSumma) {
        $this->setField('shipping_total', $fSumma);
        return $this;
    }

    public function getOrderGroupInvoicesHST() {
        return floatval($this->getField('HST_charged'));
    }

    public function setOrderGroupInvoicesHST($fSumma) {
        $this->setField('HST_charged', $fSumma);
        return $this;
    }

    public function getOrderGroupInvoicesDropShipFeeCharged() {
        return floatval($this->getField('drop_ship_fee_charged'));
    }

    public function setOrderGroupInvoicesDropShipFeeCharged($fSumma) {
        $this->setField('drop_ship_fee_charged', $fSumma);
        return $this;
    }

    public function getOrderGroupInvoicesShippingCharged() {
        return floatval($this->getField('shipping_charged'));
    }

    public function setOrderGroupInvoicesShippingCharged($fSumma) {
        $this->setField('shipping_charged', $fSumma);
        return $this;
    }

    public function calculateOrderGroupInvoiceTotal() {
        $this->setField('invoice_total', $this->getOrderGroupInvoicesShippingTotal()+$this->getOrderGroupInvoiceProductsTotal());
        return $this;
    }

    protected function getReconciliationEntity() {
        if (intval($this->getField('reconciliation_id')) > 0 && empty($this->oReconciliation)) {
            $this->oReconciliation = new Reconciliation(['id'=>$this->getField('reconciliation_id')]);
        }
        return $this->oReconciliation;
    }

    public function getReconcileStatus() {
        $sReconcileStatus = null;
        $oReconciliation =  $this->getReconciliationEntity();
        if (!empty($oReconciliation)) {
            $sReconcileStatus = $oReconciliation->getAction();
        }
        return $sReconcileStatus;
    }
}