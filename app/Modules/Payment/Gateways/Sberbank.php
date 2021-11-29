<?php

namespace Modules\Payment\Gateways;

use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Xcart\App\Main\Xcart;

class Sberbank extends Gateway
{
    public static function getProcessorName()
    {
        return 'Sberbank';
    }

    public function success($params)
    {
        $transaction_id = $params['mdOrder'];
        $order_info = $params['orderNumber'];
        [$order_id, $time_payment] = explode('_', $order_info);
        /** @var OrderTransactionModel txn */
        if ($this->txn = OrderTransactionModel::objects()->get(['transaction_id' => $transaction_id, 'orderid' => $order_id])) {
            $data = "amount;{$this->txn->transaction_amount};mdOrder;{$this->txn->transaction_id};operation;{$params['operation']};orderNumber;{$order_info};status;{$params['status']};";
            $key = "np0tlf460u8o0nsfisfd0tasti";
            $hmac = hash_hmac ( 'sha256' , $data , $key);
            Xcart::app()->logger->debug('Symmetrical cryptography', ["[$hmac]\n", $hmac], 'sberbank_response');
            $this->txn->setAttributes([
                'transaction_response' => $params,
                'transaction_id' => $transaction_id,
            ]);
            $this->txn->transaction_status = OrderTransactionModel::STATUS_AUTHORIZED;
            $this->txn->save();

            $transactionLog = new TransactionLogModel(
                [
                    'orderid' => $this->txn->orderid,
                    'paymentid' => $this->txn->paymentid,
                    'order_transaction_id' => $this->txn->id,
                    'transaction_id' => $this->txn->transaction_id,
                    'transaction_status' => $this->txn->transaction_status,
                    'transaction_total' => $this->txn->transaction_amount,
                    'transaction_currency' => $this->txn->transaction_currency,
                    'login' => $this->txn->login,
                    'transaction_log' => $this->txn->transaction_response
                ]
            );

            if ($transactionLog->isValid()) {
                $transactionLog->save();
            }
        }
        Xcart::app()->logger->debug('Response from sberbank', [json_encode($params, true)], 'sberbank_response');
        parent::success($params);
    }

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function refund($params)
    {
        // TODO: Implement refund() method.
    }

    public function void($params)
    {
        // TODO: Implement void() method.
    }

    public function capture($params)
    {
        // TODO: Implement capture() method.
    }

    public function lookup($params)
    {
        // TODO: Implement lookup() method.
    }

    public function authorize($params)
    {
        $this->gateway->setTestMode(true);
        $timestamp = time();
        $this->result = $this->gateway->authorize(
            [
                'orderNumber' => "{$params['order']->pk}_$timestamp",
                'amount' => $params['amount'],
                'returnUrl' => $params['returnUrl'],
                'description' => $params['description'],
                'dynamicCallbackUrl' => $params['notifyUrl']
            ]
        )->setTwoStage(true)->setUserName($params['processor_model']->param01)->setPassword($params['processor_model']->param02)->send();
    }

    public function reauthorize($params)
    {
        // TODO: Implement reauthorize() method.
    }

    public function purchase($params)
    {
        $this->authorize($params);
        return $this->result->isSuccessful();
        // TODO: Implement purchase() method.
    }

    public function complete($params)
    {
        // TODO: Implement complete() method.
    }

    public function getState($mode)
    {
        // TODO: Implement getState() method.
    }
}