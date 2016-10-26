<?php

class classPaymentMethodpayment_cc extends classPaymentMethod
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);

    }
}