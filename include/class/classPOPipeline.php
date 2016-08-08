<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrders.php";

class classPOPipeLine extends classData
{
    private $oOrder = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['po_id'];
        $this->sPrimaryTable = 'po_pipeline';
        parent::__construct($aParams);

    }

    public function getPOId()
    {
        return $this->getField('po_id');
    }

    public static function getPOByNumber($sPONumber)
    {
        global $sql_tbl;
        $oPo = null;
        if (!empty($sPONumber)) {
            $aOrder = func_query_first("SELECT * FROM " . $sql_tbl['po_pipeline'] . " WHERE PO_number = '$sPONumber'");
            if (!(empty($aOrder))) {
                $oPo = new classPOPipeLine();
                $oPo->fillPrimaryTableValues($aOrder);
            }
        }
        return $oPo;

    }

    public function getOrderInstance()
    {
        if (is_null($this->oOrder)) {
            $iOrderId = $this->getField('order_id');
            if (!empty($iOrderId))
                $this->oOrder = new classOrder($iOrderId);
        }
        return $this->oOrder;
    }

    public static function getPendingPOrders()
    {
        global $sql_tbl;
        $aPOs = [];
        $aOrders = func_query("SELECT * FROM " . $sql_tbl['po_pipeline'] . " WHERE status = 'uploaded'");
        if (!empty($aOrders)) {
            foreach ($aOrders as $aOrder) {
                $oPo = new classPOPipeLine();
                $oPo->fillPrimaryTableValues($aOrder);
                $aPOs[] = $oPo;
            }
        }
        return $aPOs;
    }

    public function getOrderNumber()
    {
        return $this->getField('PO_number');
    }

    public function getOrderOriginalFileName()
    {
        return $this->getField('original_po_file');
    }
}