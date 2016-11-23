<?php

namespace Xcart\Shipping;

class UPS extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = false;
        return $bResult;
    }
}