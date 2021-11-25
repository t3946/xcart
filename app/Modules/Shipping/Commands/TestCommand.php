<?php

namespace Modules\Shipping\Commands;

use Modules\Shipping\Helpers\ShippingRatesHelper;
use Xcart\App\Commands\Command;

class TestCommand extends Command
{

    public function handle($arguments = [])
    {
        print_r(ShippingRatesHelper::getDistributorShippingRates(
            12,
            'US',
            'NY',
            1,
            100
        ));
    }
}