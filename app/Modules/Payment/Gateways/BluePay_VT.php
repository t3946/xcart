<?php

namespace Modules\Payment\Gateways;

class BluePay_VT extends BluePay
{
    public static function getProcessorName()
    {
        return 'BluePay_VT';
    }
}