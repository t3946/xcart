<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrders.php";
require_once $xcart_dir . "/include/class/classLogs.php";

class classPOPipeLine extends classData
{
    const PO_LINK_ON_MODIFY = "PO# %s corresponds to the following order(s): <a href='%s' target='_blank'>%s</a>";
    const PO_NOT_IN_OUR_SYSTEM = "PO# %s is not yet in our system";
    const PO_HAS_ALREADY_BEEN_ADDED = "PO# %s has already been added to Pending entry POs queue";
    const PO_HAS_BEEN_UPLOADED = "PO# %s has been uploaded";
    const PO_HAS_BEEN_SELECTED = "PO# %s has been selected for entry";
    const PO_HAS_BEEN_DROPPED = "PO# %s has been dropped";
    const PO_HAS_BEEN_ENTERED = "PO# %s has been successfully entered";

    const PO_STATUS_UPLOADED = 'uploaded';
    const PO_STATUS_DROPED = 'droped';
    const PO_STATUS_ENTERED = 'entered';

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
        $aOrders = func_query("SELECT * FROM " . $sql_tbl['po_pipeline'] . " WHERE status = '" . self::PO_STATUS_UPLOADED . "'");
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

    public function setOrderToPO($iOrderId)
    {
        $this->updateField('order_id', $iOrderId);
        $this->updateOrderStatus('entered');
        classLogs::_log('purchase_orders', $this->getPOId(), classLogs::LOG_TYPE_CLIENT, sprintf(self::PO_HAS_BEEN_ENTERED, $this->getOrderNumber() . " (" . $this->getOrderOriginalFileName() . ")"));
    }

    public function setOrderStatus($sStatus)
    {
        $this->setField('status', $sStatus);
    }

    public function updateOrderStatus($sStatus)
    {
        $this->updateField('status', $sStatus);
    }

    public function selectOrderForEntry()
    {
        $aResult = [];
        $oStoreFront = new classStoreFront(['storefrontid' => $this->getStoreFrontId()]);
        classLogs::_log('purchase_orders', $this->getPOId(), classLogs::LOG_TYPE_CLIENT, sprintf(self::PO_HAS_BEEN_SELECTED, $this->getOrderNumber() . " (" . $this->getOrderOriginalFileName() . ")"));
        $aResult['frontend_url'] = 'http://' . $oStoreFront->getDomain() . "/?purchase_order_selected=" . $this->getPOId();
        return $aResult;
    }

    public function getStoreFrontId()
    {
        return $this->getField('storefront_id');
    }

    public function uploadPurchaseOrder($purchase_order_number_upload, $purchase_order_storefront_upload)
    {
        global $xcart_dir, $login;
        $aPathInfo = (pathinfo($_FILES["purchase_order_file"]['name']));
        $sFileName = $purchase_order_number_upload . '.' . $aPathInfo['extension'];
        $sNewFilePath = $xcart_dir . '/files/purchase_orders/' . $sFileName;

        if (move_uploaded_file($_FILES["purchase_order_file"]['tmp_name'], $sNewFilePath)) {
            $this->setField('PO_number', $purchase_order_number_upload);
            $this->setField('login', $login);
            $this->setField('file_name', $sFileName);
            $this->setField('storefront_id', $purchase_order_storefront_upload);
            $this->setField('original_po_file', $_FILES["purchase_order_file"]['name']);
            $this->setOrderStatus('uploaded');
            $this->_insert();
            classLogs::_log('purchase_orders', $this->getPOId(), classLogs::LOG_TYPE_CLIENT, sprintf(classPOPipeLine::PO_HAS_BEEN_UPLOADED, $this->getOrderNumber() . " (" . $this->getOrderOriginalFileName() . ")"));

        } else {
            throw new Exception("PO#$purchase_order_number_upload upload failed");
        }
    }

    public static function getPOStatuses()
    {
        return [
            self::PO_STATUS_UPLOADED => 'Uploaded',
            self::PO_STATUS_DROPED => 'Droped',
            self::PO_STATUS_ENTERED => 'Entered',
        ];
    }
}