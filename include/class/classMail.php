<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classMail extends classData
{
    public function __construct($aParams = [])
    {
        parent::__construct($aParams);
    }

    public function getEmailBody()
    {
        return $this->getField('email_body');
    }

    public function getSubject()
    {
        return $this->getField('customer_subject');
    }

    public function replaceSubject(classOrder $oOrder)
    {
        if (!empty($oOrder)) {
            $this->setField('customer_subject', str_replace("{{orderid}}", $oOrder->getDisplayOrderNumber(), $this->getSubject()));
        }
    }

    public function setBody($str)
    {
        $this->setField('email_body', $str);
        return $this;
    }

    public function setSubject($str)
    {
        $this->setField('customer_subject', $str);
        return $this;
    }

    public function replaceBody(classOrder $oOrder)
    {
        if (!empty($oOrder)) {
            $this->setField('email_body', str_replace("{{c-fullname}}", $oOrder->getFirstName(), $this->getEmailBody()))->
            setField('email_body', str_replace("{{orderid}}", $oOrder->getDisplayOrderNumber(), $this->getEmailBody()))->
            setField('email_body', str_replace("{{site_url}}", $oOrder->getOrderStoreFront()->getStoreFrontURL(), $this->getEmailBody()));
        }
        $this->setField('email_body',func_eol2br($this->getEmailBody()));
    }
}