<?php

namespace Modules\Order\Stores;


use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTransactionModel;
use Xcart\App\Store\BaseStore;

class OrderStore extends BaseStore
{
    /** @var OrderModel $model */
    public $model = null;
    public $transactions = [];

    public function __construct(OrderModel $model = null)
    {
        if ($model) {
            $this->model = $model;
            $this->populate([]);
        }
    }

    public function populate(array $data)
    {
        $this->transactions = $this->model->transactions->all();
    }

    private $total = null;
    public function getTotal()
    {
        if (is_null($this->total)) {
            $refund = 0;

            /** @var OrderGroupModel $group */
            foreach ($this->model->groups as $group) {
                $refund += $group->getRefunds();
            }

            $this->total = $this->model->total - $refund;
        }
        return $this->total;
    }

    private $capturedAmount = null;
    public function getCapturedAmount()
    {
        if (is_null($this->capturedAmount)) {
            $this->capturedAmount = round(array_sum(array_map(function ($model) {
                /** @var OrderTransactionModel $model */
                $value = 0;
                if ($model->type == OrderTransactionModel::TYPE_CAPTURE && in_array($model->transaction_status,
                        [
                            OrderTransactionModel::STATUS_COMPLETED,
                            OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED,
                        ]
                    )) {
                    $value = $model->transaction_amount;
                }
                return $value;
            }, $this->transactions)),2);
        }

        return $this->capturedAmount;
    }

    private $capturedAvail = null;
    public function getCapturedAvail()
    {
        if (is_null($this->capturedAvail)) {
            $this->capturedAvail = round(array_sum(array_map(function ($model) {
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
            }, $this->transactions)),2);
        }

        return $this->capturedAvail;
    }

    private $authorizedAmount = null;
    public function getAuthorizedAmount()
    {
        if (is_null($this->authorizedAmount)) {
            $this->authorizedAmount = round(array_sum(array_map(function ($model) {
                /** @var OrderTransactionModel $model */
                $value = 0;
                if ($model->type == OrderTransactionModel::TYPE_AUTHORIZATION && in_array($model->transaction_status,
                        [
                            OrderTransactionModel::STATUS_AUTHORIZED,
                            OrderTransactionModel::STATUS_PENDING,
                            OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                            OrderTransactionModel::STATUS_CAPTURED,
                            OrderTransactionModel::STATUS_COMPLETED,
                        ]
                    )) {
                    $value = $model->transaction_amount;
                }
                return $value;
            }, $this->transactions)),2);
        }

        return $this->authorizedAmount;
    }

    private $authAvailAmount = null;
    public function getAuthAvailAmount()
    {
        if (is_null($this->authAvailAmount)) {
            $this->authAvailAmount = round(array_sum(array_map(function ($model) {
                /** @var OrderTransactionModel $model */
                $value = 0;
                if ($model->type == OrderTransactionModel::TYPE_AUTHORIZATION && in_array($model->transaction_status,
                        [
                            OrderTransactionModel::STATUS_AUTHORIZED,
                            OrderTransactionModel::STATUS_PENDING,
                            OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                            OrderTransactionModel::STATUS_CAPTURED,
                            OrderTransactionModel::STATUS_COMPLETED,
                        ]
                    )) {
                    $value = $model->getAvailAmount();
                }
                return $value;
            }, $this->transactions)),2);
        }

        return $this->authAvailAmount;
    }

    public function getAmountDeficit()
    {
        return $this->getTotal() - $this->getCapturedAvail();
    }

    public function getAmountToCapture()
    {
        $amount = $this->getAuthorizedAmount() - $this->getCapturedAmount();
        return ($amount >= 0) ? $amount : 0;
    }

    public function getAskFromCx()
    {
        return $this->getAmountDeficit() - $this->getAmountToCapture();
    }

    private $additionalCaptureAmount = null;
    public function getAdditionalCaptureAmount()
    {
        if (is_null($this->additionalCaptureAmount)) {
            $this->additionalCaptureAmount = round(array_sum(array_map(function ($model) {
                /** @var OrderTransactionModel $model */
                $value = 0;
                if ($model->type == OrderTransactionModel::TYPE_AUTHORIZATION && in_array($model->transaction_status,
                        [
                            OrderTransactionModel::STATUS_AUTHORIZED,
                            OrderTransactionModel::STATUS_PENDING,
                            OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                        ]
                    )) {
                    if (($payment = $model->payment_method_model) && $payment->maximum_re_authorization_multiplier > 0) {
                        $value = min($payment->maximum_re_authorization_increase, $model->getAvailAmount() * $payment->maximum_re_authorization_multiplier - $model->getAvailAmount());
                    }
                }
                return $value;
            }, $this->transactions)),2);
        }

        return $this->additionalCaptureAmount;
    }
}