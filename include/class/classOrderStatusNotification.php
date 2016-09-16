<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classOrderStatusNotification extends classData
{
    private $sSubject = null;
    private $sBody = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['code', 'number'];
        $this->sPrimaryTable = 'order_status_notifications';
        parent::__construct($aParams);

    }

    /**
     * @param $sStatus
     * @return classOrderStatusNotification[]
     */
    public static function getOrderStatusNotificationsByCode($sStatus)
    {
        $aOrderNotifications = null;
        $oSQL = new classSQLBuilder();
        $aStatuses = $oSQL->addSelect('*')->addFromTable('order_status_notifications')->addCondition("code='$sStatus'")->addOrderBy('number')->Execute()->getQueryResult();
        if (!empty($aStatuses)) {
            foreach ($aStatuses as $aStatus) {
                $oStatus = new classOrderStatusNotification();
                $oStatus->fillPrimaryTableValues($aStatus);
                $aOrderNotifications[] = $oStatus;
            }
        }
        return $aOrderNotifications;
    }

    public function getEmailBody()
    {
        return $this->getField('email_body');
    }

    public function setBody(classOrder $oOrder)
    {
        if (!empty($oOrder)) {
            $this->setField('email_body', str_replace("{{c-fullname}}", $oOrder->getFirstName(), $this->getEmailBody()));
        }
        $this->setField('email_body',func_eol2br($this->getEmailBody()));
    }

    public function isEnabled()
    {
        return ($this->getField('enabled')=='Y');
    }
}