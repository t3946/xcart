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

    /** @var \Omnipay\Common\AbstractGateway|RestGateway|\Omnipay\BluePay\Gateway $gateway */
    public $gateway;

    /** @var ProcessorModel $model */
    public $model;

    public $test_mode = false;
    /** @var  ResponseInterface $result */
    public $result;

    /**
     * Gateway constructor.
     * @param ProcessorModel $model
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
                $gateway = new $class($model);
            }
        }
        return $gateway;
    }

    public function init()
    {
        $this->test_mode = ($this->model->test_mode == 'Y');
    }

    public static function getProcessorName()
    {
        return null;
    }

    public static function isPartiallyCaptureEnabled()
    {
        return true;
    }
}