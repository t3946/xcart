<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classData.php";
require_once $xcart_dir."/include/class/payments/classPaymentMethodpayment_offline.php";
require_once $xcart_dir."/include/class/payments/classPaymentMethodpayment_cc.php";


class classPaymentMethod extends classData {
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);
    }

    public function getPaymentMethodInstance($aParams){
        if (intval($this->getField('paymentid')) > 0) {
            $sPaymentClassName = 'classPaymentMethod'.str_replace('.php','',$this->getField('payment_script'));
            return new $sPaymentClassName($aParams);
        } else return $this;
    }
}
