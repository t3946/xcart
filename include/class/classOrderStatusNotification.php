<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classMail.php";

class classOrderStatusNotification extends classMail
{
    private $sSubject = null;
    private $sBody = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['code'];
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
        $aStatuses = $oSQL->addSelect('*')->addFromTable('order_status_notifications')->addCondition("code='$sStatus'")->Execute()->getQueryResult();
        if (!empty($aStatuses)) {
            foreach ($aStatuses as $aStatus) {
                $oStatus = new classOrderStatusNotification();
                $oStatus->fillPrimaryTableValues($aStatus);
                $aOrderNotifications[] = $oStatus;
            }
        }
        return $aOrderNotifications;
    }

    public function isEnabled()
    {
        return ($this->getField('enabled')=='Y');
    }
}