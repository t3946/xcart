<?php
namespace Xcart;

class PaymentMethodpayment_cc extends PaymentMethod
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);

    }
}