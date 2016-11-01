<?php
namespace Xcart\Payments;
use Xcart\PaymentMethod;

class PaymentMethodpayment_cc extends PaymentMethod
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);
    }
}