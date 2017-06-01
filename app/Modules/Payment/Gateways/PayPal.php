<?php
namespace Modules\Payment\Gateways;

use Omnipay\Omnipay;

class PayPal extends Gateway
{
    public static function getProcessorName()
    {
        return 'PayPal_REST';
    }

    public function init()
    {
        parent::init();
    }
}