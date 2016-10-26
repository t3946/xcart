<?php
namespace Xcart;

class OrderRefundGroups extends Data
{
    /**
     * @var classOrderRefundGroup[]
     */
    private $aRefundGroups = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'refund_groups';
        parent::__construct($aParams);
    }

    public function countOrderRefundGroups() {
        $count = 0;
        if (!empty($this->aRefundGroups))
            $count = count($this->aRefundGroups);
        return $count;
    }

    public function getOrderRefundGroups($aParams = [])
    {
        $aRes = func_query("SELECT * FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE orderid = " . $aParams['orderid'] . " AND manufacturerid = " . $aParams['manufacturerid']);
        if (!empty($aRes)) {
            foreach ($aRes as $aRefundGroup) {
                $oRefundGroup = new classOrderRefundGroup();
                $oRefundGroup->fill($aRefundGroup);
                $this->aRefundGroups[] = $oRefundGroup;
            }
        }
        return $this;
    }

    public function getOrderRefundTotal()
    {
        $fRes = 0;
        if (!empty($this->aRefundGroups)) {
            foreach ($this->aRefundGroups as $oRefundInvoice) {
                $fRes+=floatval($oRefundInvoice->getField('total_gross'));
            }
        }
        return $fRes;
    }

    public function getOrderRefundPST()
    {
        $fRes = 0;
        if (!empty($this->aRefundGroups)) {
            foreach ($this->aRefundGroups as $oRefundInvoice) {
                $fRes+=floatval($oRefundInvoice->getField('total_pst'));
            }
        }
        return $fRes;
    }

    public function getOrderRefundHST()
    {
        $fRes = 0;
        if (!empty($this->aRefundGroups)) {
            foreach ($this->aRefundGroups as $oRefundInvoice) {
                $fRes+=floatval($oRefundInvoice->getField('total_gst'));
            }
        }
        return $fRes;
    }
}