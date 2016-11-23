<?php

namespace Xcart\Shipping;

class Amazon extends ShippingProcessor
{
    public function isProcessorApplicable()
    {
        $bResult = false;
        return $bResult;
    }
}