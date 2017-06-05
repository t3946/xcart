<?php
namespace Modules\Payment\Gateways;

use Omnipay\Omnipay;

class PayPal extends Gateway
{
    public static function getProcessorName()
    {
        return 'PayPal_Rest';
    }

    public function init()
    {
        parent::init();
    }
}