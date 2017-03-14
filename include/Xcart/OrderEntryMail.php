<?php

namespace Xcart;


class OrderEntryMail extends Mail
{
    /**
     * @var OrderGroup
     */
    protected $oOrderGroup = null;

    private $aOrderData = null;

    public function setOrderGroup(OrderGroup $oOrderGroup)
    {
        $this->oOrderGroup = $oOrderGroup;
        return $this;
    }

    public function setOrderData($aData)
    {
        $this->aOrderData = $aData;
        return $this;
    }


    public function sendEmail()
    {
        global $mail_smarty, $statuses, $config;

        if (!empty($this->aOrderData) && !empty($this->oOrderGroup)) {
            $this->prepareMail();

            $mail_smarty->assign('mnf_operator_notify', 'Y');
            $mail_smarty->assign("manufacturerid", $this->oOrderGroup->getManufacturerId());
            $mail_smarty->assign('message_body', $this->getEmailBody());
            $mail_smarty->assign('d_email_subject_14', $this->getSubject());
            $mail_smarty->assign("products", $this->aOrderData['products']);
            $mail_smarty->assign("giftcerts", $this->aOrderData['giftcerts']);
            $mail_smarty->assign("userinfo", $this->aOrderData['userinfo']);

            $mail_smarty->assign('order', $this->aOrderData['order']);

            $mail_smarty->assign('email_is_sent_to_operator', 'Y');

            $oMail = \Xcart\App\Main\Xcart::app()->mail;
            $oMail->to = $this->getTo();
            $oMail->from = $this->getFrom();
            $oMail->reply_to = null;
            $oMail->subject_template = 'mail/order_notification_subj.tpl';
            $oMail->body_template = 'mail/order_notification_mnf.tpl';
            $oMail->addHeader(['X-Xcart-Label' => 'order-logs']);
            $oMail->sendEmail();
            //func_send_mail($this->getTo(), "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $this->getFrom(), false);
        }
    }

}