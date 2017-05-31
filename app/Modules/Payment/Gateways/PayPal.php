<?php
namespace Modules\Payment\Gateways;

use Omnipay\Omnipay;

class PayPal extends Gateway
{
    public static function getProcessorName()
    {
        return 'PayPal REST';
    }

    public function init()
    {
        parent::init();
    }
}