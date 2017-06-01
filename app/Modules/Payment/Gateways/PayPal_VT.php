<?php
namespace Modules\Payment\Gateways;

use Omnipay\Omnipay;

class PayPal_VT extends PayPal
{
    public static function getProcessorName()
    {
        return 'PayPal_VT';
    }

}