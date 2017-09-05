<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Gateways\Gateway;
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

    /**
     * @param OrderTransactionModel[] $models
     * @return float
     */
    public static function getToCapture($models)
    {
        return static::getAuthorized($models) + static::getAuthorized($models, true);
    }

    /**
     * @param OrderTransactionModel[] $models
     * @return float
     */
    public static function getCaptured($models)
    {
        return round(array_sum(array_map(function ($model) {
            /** @var OrderTransactionModel $model */
            $value = 0;
            if ($model->type == OrderTransactionModel::TYPE_CAPTURE && in_array($model->transaction_status,
                    [
                        OrderTransactionModel::STATUS_COMPLETED,
                        OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED,
                    ]
                )) {
                $value = $model->getAvailAmount();
            }
            return $value;
        }, $models)), 2);

    }

    /**
     * @param OrderTransactionModel[] $models
     * @param bool $isAdditional
     * @return float
     */
    public static function getAuthorized($models, $isAdditional = false)
    {
        return round(array_sum(array_map(function ($model) use ($isAdditional) {
            /** @var OrderTransactionModel $model */
            $value = 0;
            if ($model->type == OrderTransactionModel::TYPE_AUTHORIZATION && !in_array($model->transaction_status,
                    [
                        OrderTransactionModel::STATUS_FAILED,
                        OrderTransactionModel::STATUS_VOIDED,
                    ]
                ))
            {
                $value = $model->getAvailAmount();

                if (!Gateway::getGateway($model->payment_method_model->processor)->isPartiallyCaptureEnabled()) {
                    if ($value < $model->transaction_amount) {
                        $value = 0;
                    }
                }

                if ($isAdditional) {
                    if (($payment = $model->payment_method_model) && $payment->maximum_re_authorization_multiplier > 0) {
                        $value = min(
                            $payment->maximum_re_authorization_increase, $value * $payment->maximum_re_authorization_multiplier - $value
                        );
                    } else {
                        $value = 0;
                    }
                }
            }
            return $value;
        }, $models)), 2);

    }

    /**
     * @param OrderTransactionModel[] $models
     * @return bool
     */
    public static function isPartiallyCaptureEnabled($models)
    {
        /** @var bool $result */
        $result = true;

        foreach($models as $model) {
            $result = $result & Gateway::getGateway($model->payment_method_model->processor)->isPartiallyCaptureEnabled();
        }
        return $result ;
    }
}