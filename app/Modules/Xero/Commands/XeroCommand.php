<?php

namespace Modules\Xero\Commands;

use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\BankTransaction;

class XeroCommand extends Command
{

    public function handle($arguments = [])
    {
        $xero = new PrivateApplication(Xcart::app()->getModule('Xero')->getConfig());
        $res = $xero->load(BankTransaction::class)
            ->modifiedAfter(new \DateTime('-30 days'))
            ->execute();
        dd($res);
    }
}