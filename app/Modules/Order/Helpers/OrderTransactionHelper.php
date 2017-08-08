<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\OrderModule;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Xcart\App\Main\Xcart;

class OrderTransactionHelper
{


    /**
     * @param Gateway $gw
     * @param array $params
     * @return array
     */
    public static function prepareOrderTransaction($gw, $params = [])
    {

        $response = [];

        $result = $gw->result->getData();

        if (!$result['amount']) {
            $result['amount'] =
                [
                    'total' => $params['amount'],
                    'currency' => $params['currency']
                ];
        }

        if ($params['mode'] == 'refund_transaction') {
            $result['amount']['total'] = -abs($result['amount']['total']);
        }

        if ($params['mode'] != 'lookup') {
            $response['login'] = Xcart::app()->user->login;
        }

        if (isset($result['capture_id'])) {
            if ($parent = OrderTransactionModel::objects()->get(['transaction_id' => $result['capture_id']])) {
                $response['parent_id'] = $parent->id;
            }
        }

        $response = array_merge($response,
            [
                'transaction_id' => $gw->result->getTransactionReference(),
                'transaction_status' => $gw->getState($params['mode']),
                'transaction_currency' => $result['amount']["currency"],
                'transaction_amount' => $result['amount']['total'],
                'transaction_response' => $result,
                'paymentid' => $params['payment_method_model']->paymentid,
                'transaction_fee' => isset($result['transaction_fee']) ? $result['transaction_fee']['value'] : null,
            ]
        );

        return $response;
    }


    /**
     * @param string $method
     * @param array $params
     * @return array|null
     */
    public static function action($method, $params)
    {
        $model = null;

        $params['mode'] = $method;

        if ($gw = Gateway::getGateway($params['processor'])) {
            if ($gw->$method($params)) {
                if ($result = OrderTransactionHelper::prepareOrderTransaction($gw, $params)) {
                    /** @var OrderTransactionModel $model */
                    list($model) = OrderTransactionModel::objects()->getOrNew(
                        [
                            'transaction_id' => $result['transaction_id'],
                            'orderid' => $params['order']->orderid
                        ]
                    );
                    $model->setAttributes($result);
                }
            }
        }

        return [$model, $gw];
    }

    public static function getOrderTransactionsGroupsValues(OrderModel $order)
    {
        $trs = [];
        if ($order) {
            foreach ($order->transactions as $transaction) {
                $trs[strtolower($transaction->transaction_status)] += $transaction->transaction_amount;
            }
        }

        return [
            'authorized_PLUS_captured_totals' => floatval(
                $trs[OrderTransactionModel::STATUS_COMPLETED]
                + $trs[OrderTransactionModel::STATUS_AUTHORIZED]
                + $trs[OrderTransactionModel::STATUS_CAPTURED]
                + $trs[OrderTransactionModel::STATUS_PENDING]
                + $trs[OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED]
            ),
            'void_total' => floatval($trs[OrderTransactionModel::STATUS_VOIDED]),
            'authorized_total' => floatval($trs[OrderTransactionModel::STATUS_AUTHORIZED] + $trs[OrderTransactionModel::STATUS_PENDING]),
            'captured_total' => floatval($trs[OrderTransactionModel::STATUS_COMPLETED] + $trs[OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED])
        ];
    }

    /**
     * @param OrderModel $order
     * @return float
     */
    public static function getCaptureAmountAvail(OrderModel $order)
    {
        $result = 0;
        if ($order) {
            foreach ($order->transactions as $transaction) {
                if ($transaction->type == OrderTransactionModel::TYPE_AUTHORIZATION
                    && in_array($transaction->transaction_status,
                        [
                            OrderTransactionModel::STATUS_AUTHORIZED,
                            OrderTransactionModel::STATUS_PENDING,
                            OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,

                        ])) {
                    $result += $transaction->transaction_amount;
                }
            }
            /**TODO +15% Paypal capture amount */
        }
        return $result;
    }
}