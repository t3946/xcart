<?php

namespace Modules\Order\Stores;


use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\OrderModule;
use Modules\Payment\Helpers\PaymentHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Store\BaseStore;

class OrderTransactionStore extends BaseStore
{
    public $params = [];
    public $model = null;
    public $gateway = null;
    public $order = null;
    public $log = null;
    public $failed = false;

    public static $gatewayMethods = [
        'authorize' => [
            'order_log' => "'Authorize' at 'Authorization'",
            'status' => OrderTransactionModel::STATUS_AUTHORIZED,
            'type' => OrderTransactionModel::TYPE_AUTHORIZATION
        ],
        'void' => [
            'order_log' => "'Void authorized transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_VOIDED,
            'type' => OrderTransactionModel::TYPE_AUTHORIZATION
        ],
        'capture' => [
            'order_log' => "'Capture authorized transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_COMPLETED,
            'type' => OrderTransactionModel::TYPE_CAPTURE
        ],
        'reauthorize' => [
            'order_log' => "'RE-authorize transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_AUTHORIZED,
            'type' => OrderTransactionModel::TYPE_AUTHORIZATION
        ],
        'refund' => [
            'order_log' => "'Refund transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_REFUNDED,
            'type' => OrderTransactionModel::TYPE_REFUND
        ],
        'lookup' => [
            'order_log' => "'Look up payment (Get links)' at 'Virtual Terminal'",
            'status' => null
        ],
        'add_manual_transaction' => [
            'order_log' => "'Add transaction' at 'Add manual transaction' section",
            'status' => null
        ],
    ];

    public function __construct($data, OrderTransactionModel $model = null)
    {
        $this->params = $data;

        if ($model) {
            $this->model = $model;
        }

        $this->populate($data);
    }

    public function __call($name, $params)
    {
        if (array_key_exists($name, self::$gatewayMethods)) {
            return $this->execute($name);
        } else {
            return $this->__smartCall($name, $params);
        }
    }

    public function populate(array $data)
    {
        $this->order = $data['order'];
    }

    /**
     * @param string $method
     * @return OrderTransactionModel
     */
    private function execute($method)
    {
        $type = null;
        $result = [];

        extract(self::$gatewayMethods[$method]);
        
        /** @var OrderTransactionModel $model */
        list($model, $this->gateway) = OrderTransactionHelper::action($method, $this->params);

        try {
            if ($model) {

                if ($model->getIsNewRecord()) {
                    $model->setAttributes(
                        [
                            'orderid' => $this->params['order']->orderid,
                            'parent_id' => $this->params['orderTransaction']->id,
                            'type' => $type
                        ]
                    );
                    $this->log .= OrderModule::t('Transaction:') . " {$this->params['orderTransaction']->transaction_id} --> {$model->transaction_id} \n";
                }

                $model->save();

                $result = $model->transaction_response;


                list ($o_log) = OrderHelper::changeOrderCBStatus($this->params['order'], OrderStatusModel::ORDER_STATUS_AUTHORIZED);
                $this->log .= OrderModule::t('Transaction:') . " {$model->transaction_id} {$o_log}\n";

                $logStatus = $model->transaction_status;

                self::lookupParentTransactions($model);

            } else {

                $state = $this->gateway->getState($this->params['mode']);
                $result = $this->gateway->result->getData();

                if ($this->gateway->result->isSuccessful()) {

                    if ($this->params['orderTransaction'] && $state) {
                        $this->params['orderTransaction']->transaction_status = $state;
                        $this->params['orderTransaction']->transaction_response = $result;
                        $this->params['orderTransaction']->save();
                    }

                    $logStatus = $this->params['orderTransaction']->transaction_status;

                } else {
                    $this->failed = true;
                    $logStatus = $state;
                    self::lookupParentTransactions($this->params['orderTransaction']);
                }
                $this->log .= "<br/>{$result['name']}<br/>{$result['message']}";
            }
        } catch (\Exception $e) {
            $this->log .= $e->getMessage()."\n";
            $this->failed =true;
            $logStatus = OrderTransactionModel::STATUS_FAILED;
        }

        $transactionLog = new TransactionLogModel(
            [
                'orderid' => $this->params['order']->orderid,
                'paymentid' => $this->params['new_method_model']->paymentid,
                'order_transaction_id' => isset($model) ? $model->id : (isset($this->params['orderTransaction']) ? $this->params['orderTransaction']->id : null),
                'transaction_id' => isset($model) ? $model->transaction_id : (isset($params['orderTransaction']) ? $this->params['orderTransaction']->transaction_id : ''),
                'transaction_status' => $logStatus,
                'transaction_currency' => isset($result['amount']) ? $result['amount']['currency'] : $this->params['currency'],
                'transaction_total' => isset($result['amount']) ? $result['amount']['total'] : $this->params['amount'],
                'login' => Xcart::app()->user->login,
                'transaction_log' => array_merge($result, ['xcart_log' => $this->log])
            ]
        );

        if ($transactionLog->isValid()) {
            $transactionLog->save();
        }

        if (isset($result['reason_code']) && $result['reason_code'] == 'PAYMENT_REVIEW') {
            $this->failed = true;
        }

        if ($this->failed) {
            switch($method) {
                case 'capture' :
                    OrderTagEventHelper::orderTagEvent(37, $this->order->orderid);
                    break;
            }
        }

        return $model;

    }

    public static function lookupSelf($model) {
        list($model_o) = OrderTransactionHelper::action('lookup', PaymentHelper::getPaymentParams($model));
        if ($model_o) {
            $model_o->save();
        }

    }

    public static function lookupParentTransactions($model)
    {
        while ($model->parent_id && $model = $model->parent) {
            self::lookupSelf($model);
        }
    }

}