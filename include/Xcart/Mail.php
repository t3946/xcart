<?php

namespace Xcart;

class Mail extends Data
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

    public function replaceSubject(Order $oOrder)
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

    public function replaceBody(Order $oOrder)
    {
        if (!empty($oOrder)) {
            $this->setBody(str_replace("{{c-fullname}}", $oOrder->getFirstName(), $this->getEmailBody()))->
            setBody(str_replace("{{orderid}}", $oOrder->getDisplayOrderNumber(), $this->getEmailBody()))->
            setBody(str_replace("{{site_url}}", $oOrder->getOrderStoreFront()->getStoreFrontURL(), $this->getEmailBody()));
        }
        $this->setBody(func_eol2br($this->getEmailBody()));
    }
}