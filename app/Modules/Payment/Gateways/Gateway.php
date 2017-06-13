<?php

namespace Modules\Payment\Gateways;


use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Payment\Models\PaymentProcessorModel;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Omnipay\PayPal\RestGateway;

abstract class Gateway implements GatewayInterface
{
    public static $gatewayMethods = [
        'authorize' => [
            'method' => 'authorize',
            'log' => "'Authorize' at 'Authorization'",
            'status' => OrderTransactionModel::STATUS_AUTHORIZED
        ],
        'void_transaction' => [
            'method' => 'void',
            'log' => "'Void authorized transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_VOIDED
        ],
        'capture_transaction' => [
            'method' => 'capture',
            'log' => "'Capture authorized transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_COMPLETED
        ],
        're_authorize_transaction' => [
            'method' => 'authorize',
            'log' => "'RE-authorize transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_AUTHORIZED
        ],
        'refund_transaction' => [
            'method' => 'refund',
            'log' => "'Refund transaction' at 'Virtual Terminal'",
            'status' => OrderTransactionModel::STATUS_REFUNDED
        ],
        'look_up_payment' => [
            'method' => 'lookup',
            'log' => "'Look up payment (Get links)' at 'Virtual Terminal'",
            'status' => null
        ],
    ];

    /** @var \Omnipay\Common\AbstractGateway|RestGateway|\Omnipay\BluePay\Gateway $gateway */
    public $gateway;
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
     * @param PaymentMethodModel $model
     * @return null|Gateway
     */
    public static function getGateway($model)
    {
        $gateway = null;
        if ($model) {
            $ccp = $model->processor_models->limit(1)->get();
            if ($ccp) {
                $class = "Modules\\Payment\\Gateways\\" . $ccp->module_name;
                if (class_exists($class)) {
                    $gateway = new $class($ccp);
                }
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