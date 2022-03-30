<?php

namespace Modules\Payment\Gateways;


use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Interfaces\GatewayInterface;
use Modules\Payment\Models\ProcessorModel;
use Omnipay\Common\GatewayInterface as OmnipayGatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Xcart\App\Main\Xcart;

abstract class AbstractGateway implements GatewayInterface
{

    public OmnipayGatewayInterface $gateway;

    public ProcessorModel $model;

    public ?OrderTransactionModel $txn = null;

    public bool $test_mode = false;

    public ResponseInterface $result;

    public function __construct(ProcessorModel $model)
    {
        $this->gateway = Omnipay::create(static::getProcessorName());

        $this->model = $model;

        $this->init();
    }

    public static function getGateway(ProcessorModel $model): ?AbstractGateway
    {
        if (class_exists($class = 'Modules\\Payment\\Gateways\\' . $model->processor_name)) {
            $gateway = new $class($model);
        }
        return $gateway ?? null;
    }

    public function init(): void
    {
        $this->test_mode = $this->model->test_mode === 'Y';
    }

    public static function isPartiallyCaptureEnabled(): bool
    {
        return false;
    }

    public function success($params): bool
    {
        if ($this->txn) {
            $result = $this->txn->save();

            switch ($this->txn->transaction_status) {
                case OrderTransactionModel::STATUS_AUTHORIZED:
                    Xcart::app()->event->trigger('order:paid', ['model' => $this->txn->order]);
                    break;
                case OrderTransactionModel::STATUS_DECLINED:
                    break;
            }
        }

        return $result ?? false;
    }
}