<?php

namespace Modules\Payment\Gateways;

use Exception;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Models\ProcessorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class Sberbank extends Gateway
{
    public static function getProcessorName(): string
    {
        return 'Sberbank';
    }

    /**
     * @throws Exception
     */
    public function success($params)
    {
        $transaction_id = $params['mdOrder'];
        /** @var OrderTransactionModel txn */
        if ($this->txn = OrderTransactionModel::objects()->get(['transaction_id' => $transaction_id])) {
            $transaction_response = $this->txn->transaction_response;
            $amount = $this->txn->transaction_amount * 100;
            $data = "amount;$amount;mdOrder;{$this->txn->transaction_id};operation;approved;orderNumber;{$transaction_response['uniqueOrderNumber']};status;1;";
            $key = $this->txn->payment_method_model->frontend_processor->param03;

            $hmac = hash_hmac('sha256', $data, $key);
            if (strtoupper($hmac) === $params['checksum']) {
                Xcart::app()->logger->debug('Symmetrical cryptography', ["[$hmac]\n", $hmac], 'sberbank_response');
                $params['links'] = $this->getLinkByMode('success');
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
        }
        parent::success($params);
    }

    public function refund($params)
    {
        $transaction_id = $params['orderTransaction']->transaction_id;
        /** @var ProcessorModel $processor_model */
        $processor_model = $params['processor'];
        $this->gateway->setTestMode($processor_model->getTestMode());
        $this->result = $this->gateway->refund([
            'orderId' => $transaction_id,
            'amount' => (float)$params['amount']
        ])->setUserName($processor_model->param01)->setPassword($processor_model->param02)->send();
        return $this->result->isSuccessful();
    }

    public function void($params)
    {
        $transaction_id = $params['orderTransaction']->transaction_id;
        /** @var ProcessorModel $processor_model */
        $processor_model = $params['processor'];
        $this->gateway->setTestMode($processor_model->getTestMode());
        $this->result = $this->gateway->void(
            [
                'orderId' => $transaction_id,
                'amount' => (float)$params['amount']
            ]
        )->setUserName($processor_model->param01)->setPassword($processor_model->param02)->send();
        return $this->result->isSuccessful();
    }

    public function capture($params)
    {
        $transaction_id = $params['orderTransaction']->transaction_id;
        /** @var ProcessorModel $processor_model */
        $processor_model = $params['processor'];
        $this->gateway->setTestMode($processor_model->getTestMode());
        $this->result = $this->gateway->capture([
            'orderId' => $transaction_id,
            'amount' => (float)$params['amount'],
        ])->setUserName($processor_model->param01)->setPassword($processor_model->param02)->send();
        return $this->result->isSuccessful();
    }

    public function authorize($params)
    {
        /** @var ProcessorModel $processor_model */
        $processor_model = $params['processor_model'];
        $this->gateway->setTestMode($processor_model->getTestMode());
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $this->result = $this->gateway->authorize(
            [
                'orderNumber' => $params['orderNumber'],
                'amount' => $params['amount'],
                'returnUrl' => $params['returnUrl'],
                'description' => "$site->corporation. Заказ № {$params['order']->getOrderNumber()}",
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
    }

    public function getLinkByMode(string $mode)
    {
        switch ($mode) {
            case 'refund':
            case 'void':
                return [];
            case 'success':
                return [
                    ['rel' => 'capture', 'method' => 'POST'],
                    ['rel' => 'void', 'method' => 'POST'],
                    ['rel' => 'reauthorize', 'method' => 'POST']
                ];
            case 'authorization':
                return null;
            case 'capture':
                return [
                    [
                        'rel' => 'refund',
                        'method' => 'POST'
                    ],
                ];
        }
    }

    public function complete($params)
    {
        // TODO: Implement complete() method.
    }

    public function getState($mode)
    {
        if (!$this->result->isSuccessful()) {
            return OrderTransactionModel::STATUS_FAILED;
        }
        $method = OrderTransactionStore::$gatewayMethods[$mode];
        return $method['status'] ?? OrderTransactionModel::STATUS_FAILED;
    }

    public function lookup($params)
    {
        // TODO: Implement lookup() method.
    }
}