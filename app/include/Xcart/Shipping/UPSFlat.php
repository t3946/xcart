<?php

namespace Xcart\Shipping;

use Xcart\App\Main\Xcart;

class UPSFlat extends UPS
{
    public function isProcessorApplicable()
    {
        $config = Xcart::app()->getModule('Sites')->getSite()->getConfig();
        return $config['flat_shipping_enabled'] !== 'N';
    }
}