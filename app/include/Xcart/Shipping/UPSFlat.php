<?php

namespace Xcart\Shipping;

use Xcart\App\Main\Xcart;

class UPSFlat extends UPS
{
    public function isProcessorApplicable()
    {
        $config = $this->getManufacturer()->site->getConfig();
        return $config['flat_shipping_enabled'] !== 'N';
    }
}