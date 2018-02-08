<?php
namespace Xcart;

class POPipeline extends Data
{
    const PO_LINK_ON_MODIFY = "PO# %s corresponds to the following order(s): <a href='%s' target='_blank'>%s</a>";
    const PO_NOT_IN_OUR_SYSTEM = "PO# %s is not yet in our system";
    const PO_HAS_ALREADY_BEEN_ADDED = "PO# %s has already been added to Pending entry POs queue";
    const PO_HAS_BEEN_UPLOADED = "PO# %s has been uploaded";
    const PO_HAS_BEEN_SELECTED = "PO# %s has been selected for entry";
    const PO_HAS_BEEN_DROPPED = "PO# %s has been dropped";
    const PO_HAS_BEEN_ENTERED = "PO# %s has been successfully entered";
    const PO_FILE_LINK = "/files/purchase_orders/%s";

    const PO_STATUS_UPLOADED = 'uploaded';
    const PO_STATUS_DROPED = 'dropped';
    const PO_STATUS_ENTERED = 'entered';

    private $oOrder = null;
    public static $aPORecievedBy = ['fax' => 'fax', 'mail_to_us' => 'mail to US address', 'mail_to_ca' => 'mail to Canadian address', 'email' => 'email', 'website' => 'website'];

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

    /**
     * @param $sPONumber
     * @return POPipeline
     */
    public static function getPOByNumber($sPONumber)
    {
        return self::model()->find(SQLBuilder::getInstance()->addCondition("PO_number = '$sPONumber'"));
    }

    public function getOrderInstance()
    {
        if (is_null($this->oOrder)) {
            $iOrderId = $this->getOrderId();
            if (!empty($iOrderId))
                $this->oOrder = Order::model(['orderid' => $iOrderId]);
        }
        return $this->oOrder;
    }

    public static function getPendingPOrders()
    {
        return self::model()->findAll(SQLBuilder::getInstance()->addCondition("status = '" . self::PO_STATUS_UPLOADED . "'"));
    }

    public static function getPOrdersByStatuses($aStatuses)
    {
        return self::model()->findAll(SQLBuilder::getInstance()->addCondition("status IN ('" . implode(',', $aStatuses) . "')"));
    }

    public function getOrderId()
    {
        return $this->getField('order_id');
    }

    public function getOrderNumber()
    {
        return $this->getField('PO_number');
    }

    public function getOrderOriginalFileName()
    {
        return $this->getField('original_po_file');
    }

    public function getOrderFileLink()
    {
        return sprintf(self::PO_FILE_LINK, $this->getField('file_name'));
    }

    public function getStatus()
    {
        return $this->getField('status');
    }

    public function setOrderToPO($iOrderId)
    {
        $this->updateField('order_id', $iOrderId);
        $this->updateOrderStatus('entered');
        Logs::_log('purchase_orders', $this->getPOId(), Logs::LOG_TYPE_CLIENT, sprintf(self::PO_HAS_BEEN_ENTERED, $this->getOrderNumber() . " (" . $this->getOrderOriginalFileName() . ")"));
        return $this;
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
        Logs::_log('purchase_orders', $this->getPOId(), Logs::LOG_TYPE_CLIENT, sprintf(self::PO_HAS_BEEN_SELECTED, $this->getOrderNumber() . " (" . $this->getOrderOriginalFileName() . ")"));
        $aResult['frontend_url'] = 'http://' . StoreFront::model(['storefrontid' => $this->getStoreFrontId()])->getDomain() . "/?purchase_order_selected=" . $this->getPOId();
        return $aResult;
    }

    public function getStoreFrontId()
    {
        return $this->getField('storefront_id');
    }

    public function getUploadDate()
    {
        if ($this->getField('modify_date')) {
            $oDate = new \DateTime();
            $oDate->setTimestamp(strtotime($this->getField('modify_date')));
            return $oDate->format('d-M-Y H:i');
        }
        return '';
    }

    public function uploadPurchaseOrder($purchase_order_number_upload, $purchase_order_storefront_upload, $purchase_order_received_status)
    {
        global $xcart_dir, $login;
        $aPathInfo = (pathinfo($_FILES["purchase_order_file"]['name']));
        $sFileName = $purchase_order_number_upload . '.' . $aPathInfo['extension'];
        $sNewFilePath = $xcart_dir . sprintf(self::PO_FILE_LINK, $sFileName);
        $allow_extensions = ['pdf'];
        if (in_array($aPathInfo['extension'], $allow_extensions)) {
            if (move_uploaded_file($_FILES["purchase_order_file"]['tmp_name'], $sNewFilePath)) {
                $this->setField('PO_number', $purchase_order_number_upload);
                $this->setField('login', $login);
                $this->setField('file_name', $sFileName);
                $this->setField('storefront_id', $purchase_order_storefront_upload);
                $this->setField('original_po_file', $_FILES["purchase_order_file"]['name']);
                $this->setField('received_by', $purchase_order_received_status);
                $this->setOrderStatus('uploaded');
                $iPoID = $this->_insert();
                if ($iPoID) {
                    $this->setField('po_id',$iPoID);
                }
                Logs::_log('purchase_orders', $this->getPOId(), Logs::LOG_TYPE_CLIENT, sprintf(POPipeline::PO_HAS_BEEN_UPLOADED, $this->getOrderNumber() . " (" . $this->getOrderOriginalFileName() . ")"));

            } else {
                throw new \Exception("PO#$purchase_order_number_upload upload failed");
            }
        }
        return $this;
    }

    public static function getPOStatuses()
    {
        return [
            self::PO_STATUS_UPLOADED => 'Uploaded',
            self::PO_STATUS_DROPED => 'Dropped',
            self::PO_STATUS_ENTERED => 'Entered',
        ];
    }

    public static function getRecievedStatuses()
    {
        return array_merge([''=>''],self::$aPORecievedBy);
    }

    public function getReceivedByName()
    {
        return self::$aPORecievedBy[$this->getField('received_by')];
    }

}