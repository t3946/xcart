<?php


namespace Modules\Payment\Gateways;


use Modules\Cart\Helpers\StagesOfOrdering;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Gateways\Omnipay\Stripe\Message\LookupPaymentIntentResponse;
use Xcart\App\Main\Xcart;

class Stripe extends Gateway
{
    public const CONNECTED_ACCOUNT_ID = 'acct_1HIbMdI2P4rQcZLT';

    public static function getProcessorName()
    {
        return 'Stripe';
    }

    public function init()
    {
        parent::init();
        $this->gateway->initialize([
            'apiKey' => $this->model->param02,
        ]);
    }

    public function refund($params)
    {
        $params['payment_intent'] = $params['transactionReference'];
        $this->result = $this->gateway
            ->refund($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function void($params)
    {
        $params['paymentIntentReference'] = $params['transactionReference'];
        $this->result = $this->gateway
            ->cancel($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function capture($params)
    {
        $params['paymentIntentReference'] = $params['transactionReference'];
        $this->result = $this->gateway
            ->capture($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function lookup($params)
    {
        $params['paymentIntentReference'] = $params['transactionReference'];
        $this->result = $this->gateway
            ->fetchPaymentIntent($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function authorize($params)
    {
        $params['paymentMethod'] = $this->gateway->createCard(['card' => $params['card']])->send()->getCardReference();
        $params['confirm'] = true;
        $params['returnUrl'] = Xcart::app()->router->absoluteUrl('payment:success', ['gateway' => 'stripe']);
        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function reauthorize($params)
    {
        $params['paymentIntentReference'] = $params['transactionReference'];
        $order = $params['order'];
        $paymentIntent = $this->gateway
            ->fetchPaymentIntent($params)
            ->send();
        $data = $paymentIntent->getData();
        if ($customer = $data['customer']) {
            $payment_method = $data['payment_method'];

            $intent = $this->gateway->createPaymentIntent(
                array_merge($params, [
                    'metadata' => ['order' => $order->orderid],
                    'captureMethod' => 'manual',
                    'connectedAccount' => self::CONNECTED_ACCOUNT_ID,
                    'customerReference' => $customer,
                    'paymentMethod' => $payment_method,
                    'description' => $order->getTransactionDescription(),
                    'offSession' => 'true',
                    'confirm' => 'true'
                ])
            )->send();
            $this->result = $intent;
            return $intent->isSuccessful();
        }
        return false;
    }

    public function purchase($params)
    {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_PAYMENT);

        /** @var OrderModel $order */
        $order = $params['order'];
        if ($transaction = $order->transactions->filter([
            'transaction_status' => OrderTransactionModel::STATUS_PENDING,
            'transaction_amount' => $params['amount'],
            'transaction_currency' => $params['currency'],
            'paymentid' => $order->payment_method_model->paymentid
        ])->limit(1)->get()) {
            $transaction_id = $transaction->transaction_response['client_secret'] ?? '';
        } else {
            $customer = $this->gateway->createCustomer([
                'email' => $order->email,
                'name' => $order->b_firstname ?: $order->firstname,
                'description' => $order->orderid,
            ])->send();

            $intent = $this->gateway->createPaymentIntent(
                array_merge($params, [
                    'metadata' => ['order' => $order->orderid],
                    'connectedAccount' => self::CONNECTED_ACCOUNT_ID,
                    'setupFutureUsage' => 'off_session',
                    'captureMethod' => 'manual',
                    'customerReference' => $customer->getCustomerReference()
                ])
            )->send();
            $transaction_id = $intent->getData() ? $intent->getData()['client_secret'] : '';
        }

        Xcart::app()->template->display('checkout/stripe_checkout.tpl',
            array_merge($params, [
                'client_secret' => $transaction_id,
                'public_key' => $params['processor_model']['param01'] ?? ''
            ])
        );

        if ($transaction) {
            return false;
        }

        $this->result = $intent;

        OrderHelper::changeOrderStatus($params['order'], OrderStatusModel::ORDER_STATUS_NOT_FINISHED, 'cb', true);

        return $intent->isSuccessful();
    }

    public function complete($params)
    {
        // TODO: Implement complete() method.
    }

    public function getState($mode)
    {
        $state = null;
        if (!$this->result->isSuccessful()) {
            return OrderTransactionModel::STATUS_FAILED;
        }

        if (isset(OrderTransactionStore::$gatewayMethods[$mode]) && $this->result->isSuccessful()) {
            $state = OrderTransactionStore::$gatewayMethods[$mode]['status'];
        }
        $data = $this->result->getData();
        if (!$state) {
            switch ($data['status']) {
                case 'requires_capture':
                    $state = OrderTransactionModel::STATUS_AUTHORIZED;
                    break;
                case 'succeeded':
                    $state = OrderTransactionModel::STATUS_COMPLETED;
                    break;
                case 'canceled':
                    $state = OrderTransactionModel::STATUS_VOIDED;
                    break;
            }
        }
        return $state;
    }

    public function success($params): void
    {
        $payload = @file_get_contents('php://input');
        $pay = json_decode($payload, true);
        $response = new LookupPaymentIntentResponse($this->gateway->createPaymentIntent($params), json_encode($pay['data']['object']));
        $data = $response->getData();
        $txn_id = $data['id'] ?? null;
        //Xcart::app()->logger->debug("Stripe callback action", $data ?? [], 'payment');
        if ($pay['type'] === 'payment_intent.amount_capturable_updated' && $txn_id &&
            $response->isSuccessful() && $this->txn = OrderTransactionModel::objects()->get(['transaction_id' => $txn_id])) {
            $this->txn->setAttributes([
                'transaction_response' => $data,
                'transaction_id' => $txn_id,
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
        parent::success($params);

    }
}