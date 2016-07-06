<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classData.php";
require_once $xcart_dir."/include/class/classPaymentMethod.php";

class classPaymentMethodpayment_offline extends classPaymentMethod
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);
    }
}