<?php

namespace Modules\Order\Stores;


use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
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
        if ($this->total === null) {
            $refund = 0;

            /** @var OrderGroupModel $group */
            foreach ($this->model->groups as $group) {
                $refund += $group->getRefunds();
            }

            $this->total = $this->model->total - $refund;
        }
        return $this->total;
    }

    private $capturedAvail = null;

    public function getCapturedAvail()
    {
        if ($this->capturedAvail === null) {
            $this->capturedAvail = OrderTransactionHelper::getCaptured($this->transactions);
        }
        return $this->capturedAvail;
    }

    private $authorizeAvail = null;

    public function getAuthorizeAvail()
    {
        if ($this->authorizeAvail === null) {
            $this->authorizeAvail = OrderTransactionHelper::getAuthorized($this->transactions);
        }

        return $this->authorizeAvail;
    }

    private $refunded = null;
    public function getRefunded()
    {
        if ($this->refunded === null) {
            $this->refunded = OrderTransactionHelper::getRefunded($this->transactions);
        }
        return $this->refunded;
    }

    public function getAmountDeficit()
    {
        return $this->getTotal() - $this->getCapturedAvail();
    }

    public function getAmountToCapture()
    {
        return max($this->getAuthorizeAvail() - $this->getRefunded(), 0);
    }

    public function getAskFromCx()
    {
        return $this->getAmountDeficit() - $this->getAmountToCapture();
    }

    private $additionalCaptureAmount = null;

    public function getAdditionalCaptureAmount()
    {
        if ($this->additionalCaptureAmount === null) {
            $this->additionalCaptureAmount = OrderTransactionHelper::getAuthorized($this->transactions, true);
        }

        return $this->additionalCaptureAmount;
    }
}