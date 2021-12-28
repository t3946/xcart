<?php

namespace Xcart\Shipping;

/**
 * @deprecated deprecated class
 */
class UPSFlat extends UPS
{
    public function isProcessorApplicable()
    {
        return false;
    }
}