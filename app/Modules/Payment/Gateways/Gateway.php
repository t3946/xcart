<?php

namespace Modules\Payment\Gateways;


use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Interfaces\GatewayInterface;
use Modules\Payment\Models\ProcessorModel;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Omnipay\PayPal\RestGateway;
use Omnipay\Stripe\PaymentIntentsGateway;
use Xcart\App\Main\Xcart;

abstract class Gateway implements GatewayInterface
{

    /** @var AbstractGateway|RestGateway|\Omnipay\BluePay\Gateway|\Omnipay\Xpay\Gateway|PaymentIntentsGateway|\Omnipay\Sberbank\Gateway $gateway */
    public $gateway;

    /** @var ProcessorModel $model */
    public $model;

    /** @var OrderTransactionModel $model */
    public $txn;

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
    public static function getGateway($model): ?Gateway
    {
        if ($model && class_exists($class = "Modules\\Payment\\Gateways\\" . $model->processor_name)) {
            $gateway = new $class($model);
        }
        return $gateway ?? null;
    }

    public function init()
    {
        $this->test_mode = $this->model->test_mode === 'Y';
    }

    public static function getProcessorName()
    {
        return null;
    }

    public static function isPartiallyCaptureEnabled(): bool
    {
        return false;
    }

    public function success($params)
    {
        if ($this->txn) {
            $this->txn->save();

            switch ($this->txn->transaction_status) {
                case OrderTransactionModel::STATUS_AUTHORIZED:
                    Xcart::app()->event->trigger('order:paid', ['model' => $this->txn->order]);
                    break;
                case OrderTransactionModel::STATUS_DECLINED:
                    break;
            }
        }
    }
}