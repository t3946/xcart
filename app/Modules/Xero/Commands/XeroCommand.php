<?php

namespace Modules\Xero\Commands;

use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\BankTransaction;
use XeroPHP\Models\Accounting\Report\BankStatement;
use XeroPHP\Remote\Collection;

class XeroCommand extends Command
{

    public function handle($arguments = [])
    {
        $xero = new PrivateApplication(Xcart::app()->getModule('Xero')->getConfig());

        /** @var Account[] $accounts */
        $accounts = $xero->load(Account::class)->execute();

        $accounts = array_filter($accounts->getArrayCopy(), static function(Account $a){
            return $a->getType() === Account::ACCOUNT_TYPE_BANK;
        });


        foreach ($accounts as $account) {
            echo $account->getCode()."\t".$account->getName()."\n";
        }

        /** @var BankTransaction[] $transactions */
        $transactions = $xero->load(BankTransaction::class)
            ->modifiedAfter(new \DateTime('-30 days'))
            ->execute();

        foreach ($transactions as $transaction) {
            echo "{$transaction->getType()}\t{$transaction->getDate()->format('d-m-Y')}\t{$transaction->getBankAccount()->getCode()}\t{$transaction->getBankTransactionID()}\t{$transaction->getTotal()}\t{$transaction->getStatus()}\t{$transaction->getReference()}\n";
        }
    }
}