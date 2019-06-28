<?php

namespace Modules\Xero\Commands;

use Modules\Order\Models\ReconciliationAccountModel;
use Modules\Order\Models\ReconciliationModel;
use Modules\Order\Models\ReconciliationUploadInfoModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use Xcart\Reconciliation;
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

        $accounts = array_filter($accounts->getArrayCopy(), static function (Account $a) {
            return $a->getType() === Account::ACCOUNT_TYPE_BANK;
        });


        foreach ($accounts as $account) {
            echo $account->getCode() . "\t" . $account->getName() . "\n";
            if (trim($account->getCode())) {
                ReconciliationAccountModel::objects()->getOrCreate([
                    'code' => trim($account->getCode()),
                    'name' => trim($account->getName()),
                ]);
            }
        }

        /** @var BankTransaction[] $transactions */
        $transactions = $xero->load(BankTransaction::class)
            ->modifiedAfter(new \DateTime('-90 days'))
            ->execute();

        [$file] = ReconciliationUploadInfoModel::objects()->getOrCreate(['orig_file_name' => 'Xero integration']);

        foreach ($transactions as $transaction) {


            switch($transaction->getType()) {
                case 'SPEND':
                case 'SPEND-TRANSFER':
                    $mlt = -1;
                    break;
                default: $mlt = 1;
            }

            $strippedReference = htmlspecialchars_decode(trim(preg_replace('/\s+/', ' ', $transaction->getReference())), ENT_QUOTES);

            $account_model = ReconciliationAccountModel::objects()->get(['code' => $transaction->getBankAccount()->getCode()]);

            $rec = ReconciliationModel::objects()->get(['bank_transaction_id' => $transaction->getBankTransactionID()]);
            if (!$rec) {
                [$rec, $is_new] = ReconciliationModel::objects()->getOrNew([
                    'description_csv' => $strippedReference,
                    'amount_csv' => (float)$transaction->getTotal() * $mlt,
                    'date_csv' => $transaction->getDate()->getTimestamp()
                ]);
                $rec->setAttributes([
                    'bank_transaction_id' => $transaction->getBankTransactionID(),
                    'status' => $transaction->getStatus(),
                    'type' => $transaction->getType(),
                    'account_id' => $account_model ? $account_model->id : null
                ]);
                if ($is_new) {
                    if (\DateTime::createFromFormat('d.m.Y', '01.06.2019') <= $transaction->getDate()) {
                        echo "{$transaction->getType()}\t{$transaction->getDate()->format('d-m-Y')}\t{$transaction->getBankAccount()->getCode()}\t{$transaction->getBankTransactionID()}\t{$transaction->getTotal()}\t{$transaction->getStatus()}\t{$strippedReference}\n";

                        $rec->save();
                    }
                } else {
                    $rec->save();
                }
            }
        }
    }
}