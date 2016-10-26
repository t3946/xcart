<?php
namespace Xcart;

class PaymentMethodpayment_offline extends PaymentMethod
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);
    }
}