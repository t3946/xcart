<?php

namespace Xcart\Shipping;


class UPSFlat extends UPS
{
    public function isProcessorApplicable()
    {
        return false;
    }
}