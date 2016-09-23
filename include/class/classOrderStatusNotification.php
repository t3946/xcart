<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classMail.php";
require_once $xcart_dir . "/include/class/classOrder.php";

class classOrderStatusNotification extends classMail
{
    /**
     * @var classMail
     */
    private $oMail = null;
    /**
     * @var classOrder
     */
    private $oOrder = null;

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

    public function getBody()
    {
        return $this->getField('email_body');
    }

    public function getSubject()
    {
        return $this->getField('customer_subject');
    }

    public function getSubjectCopy()
    {
        return $this->getField('copy_subject');
    }

    public function prepareMail(classOrder $oOrder)
    {
        $this->oMail = new classMail();
        $this->oMail->setSubject($this->getSubject())->replaceSubject($oOrder);
        $this->oMail->setBody($this->getBody())->replaceBody($oOrder);
        $this->oOrder = $oOrder;
        return $this;
    }

    public function sendEmail()
    {
        global $mail_smarty, $statuses, $config;
        x_load('order');
        $order_data = func_order_data($this->oOrder->getOrderId());
        $mail_smarty->assign("products",$order_data["products"]);
        $mail_smarty->assign("giftcerts",$order_data["giftcerts"]);
        $mail_smarty->assign("order",$order_data["order"]);
        $mail_smarty->assign("userinfo",$order_data["userinfo"]);
        $mail_smarty->assign('statuses', $statuses);

        $this->oOrder->getCustomerEntity()->getLanguage();

        $to_customer = ($this->oOrder->getCustomerEntity()->getLanguage() ? $this->oOrder->getCustomerEntity()->getLanguage() : $config['default_customer_language']);
        $mail_smarty->assign("products", func_translate_products($order_data["products"], $to_customer));
        $mail_smarty->assign('type', 'C');
        $mail_smarty->assign('order_notification', $this->getFields());
        $mail_smarty->assign('oOrder', $this->oOrder);

        func_send_mail($this->oOrder->getEmail(), 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $config['Company']['orders_department'], false);

        $mail_smarty->assign('type', 'A');
        $mail_smarty->assign("show_order_details", "Y");
        $to = $config['Company']['orders_department'];
        $from = $this->oOrder->getFirstName() . "<" . $config['Company']['orders_department'] . ">";
        $reply_to = $this->oOrder->getFirstName() . "<" . $this->oOrder->getEmail() . ">";

        func_send_mail($to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $from, true, true, false, false, $reply_to);
    }
}