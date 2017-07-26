<?php

namespace Modules\Payment\Gateways;


use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Interfaces\GatewayInterface;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Payment\Models\PaymentProcessorModel;
use Modules\Payment\Models\ProcessorModel;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Omnipay\PayPal\RestGateway;

abstract class Gateway implements GatewayInterface
{
    public static $gatewayMethods = [
        'authorize' => [
            'method' => 'authorize',
            'order_log' => "'Authorize' at 'Authorization'",
            'status' => OrderTransactionModel::STATUS_AUTHORIZED
        ],
        'void' => [
            'method' => 'void',
            'order_log' => "'Void authorized transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_VOIDED
        ],
        'capture' => [
            'method' => 'capture',
            'order_log' => "'Capture authorized transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_COMPLETED
        ],
        'reauthorize' => [
            'method' => 'reauthorize',
            'order_log' => "'RE-authorize transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_AUTHORIZED
        ],
        'refund' => [
            'method' => 'refund',
            'order_log' => "'Refund transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_REFUNDED
        ],
        'lookup' => [
            'method' => 'lookup',
            'order_log' => "'Look up payment (Get links)' at 'Virtual Terminal'",
            'status' => null
        ],
        'add_manual_transaction' => [
            'method' => 'lookup',
            'order_log' => "'Add transaction' at 'Add manual transaction' section",
            'status' => null
        ],
    ];

    /** @var \Omnipay\Common\AbstractGateway|RestGateway|\Omnipay\BluePay\Gateway $gateway */
    public $gateway;

    /** @var PaymentProcessorModel $model */
    public $model;

    public $test_mode = false;
    /** @var  ResponseInterface $result */
    public $result;

    /**
     * Gateway constructor.
     * @param PaymentProcessorModel $model
     */
    public function __construct($model)
    {
        $this->gateway = Omnipay::create(static::getProcessorName());
        if ($this->gateway) {
            $this->model = $model;
            $this->init();
        }
    }

    /**
     * @param ProcessorModel $model
     * @return null|Gateway
     */
    public static function getGateway($model)
    {
        $gateway = null;
        if ($model) {
            if (class_exists($class = "Modules\\Payment\\Gateways\\" . $model->processor_name)) {
                $gateway = new $class($model->cc_processor);
            }
        }
        return $gateway;
    }

    public function init()
    {
        $this->test_mode = ($this->model->testmode == 'Y');
    }

    public static function getProcessorName()
    {
        return null;
    }
}