<?php

namespace Modules\Order\Stores;


use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Store\BaseStore;

class OrderStore extends BaseStore
{
    /** @var OrderModel $model */
    public $model = null;
    public $transactions = [];

    private $actualShippingCostNet;
    private $actualShippingCostGross;
    private $amazonCompetitorsMinPrice;
    private $amazonCompetitorsMinShipping;
    private $amazonCompetitorsMinTotal;

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
        return max($this->getAuthorizeAvail(), 0);
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

    public function getActualShippingCostNet()
    {
        if ($this->actualShippingCostNet === null) {
            foreach ($this->model->groups as $group) {
                $this->actualShippingCostNet += $group->actual_shipping_net;
            }
        }
        return $this->actualShippingCostNet;
    }

    public function getActualShippingCostGross()
    {
        if ($this->actualShippingCostGross === null) {
            foreach ($this->model->groups as $group) {
                $this->actualShippingCostGross += $group->actual_shipping_gross;
            }
        }
        return $this->actualShippingCostGross;
    }

    public function getAmazonCompetitorsMinPrice(): ?float
    {
        $res = null;

        if ($this->amazonCompetitorsMinPrice === null) {
            if ($details = $this->model->detail_models) {
                foreach ($details as $detail) {
                    if (!\in_array($detail->order_group->dc_status,
                        [
                            OrderStatusModel::ORDER_DA_STATUS_RECEIVED_BY_AMAZON,
                            OrderStatusModel::ORDER_DC_STATUS_SHIPPED,
                        ], true)) {
                        [$product] = $detail->getAmazonCompetitorMinPrice();
                        $this->amazonCompetitorsMinPrice += $product * $detail->amount;
                    } else {
                        $this->amazonCompetitorsMinPrice += $detail->amazon_price;
                    }
                }
            }
        }
        return $this->amazonCompetitorsMinPrice;
    }

    public function getAmazonCompetitorsMinShipping(): ?float
    {
        if ($this->amazonCompetitorsMinShipping === null) {
            if ($details = $this->model->detail_models) {
                foreach ($details as $detail) {
                    if (!\in_array($detail->order_group->dc_status,
                        [
                            OrderStatusModel::ORDER_DA_STATUS_RECEIVED_BY_AMAZON,
                            OrderStatusModel::ORDER_DC_STATUS_SHIPPED,
                        ], true)) {
                        [, $shipping] = $detail->getAmazonCompetitorMinPrice();
                        $this->amazonCompetitorsMinShipping += $shipping;
                    } else {
                        $this->amazonCompetitorsMinShipping += $detail->amazon_shipping;
                    }
                }
            }
        }
        return $this->amazonCompetitorsMinShipping;
    }

    public function getAmazonCompetitorsMinTotal(): ?float
    {
        if ($this->amazonCompetitorsMinTotal === null) {
            $this->amazonCompetitorsMinTotal = $this->getAmazonCompetitorsMinPrice() + $this->getAmazonCompetitorsMinShipping();
        }
        return $this->amazonCompetitorsMinTotal;
    }

    public function getS3ToDxTotalNet()
    {
        return $this->model->getOrderCostToUs() + $this->getActualShippingCostNet();
    }

    public function getS3ToDxTotalGross()
    {
        return $this->model->getOrderCostToUs() + $this->getActualShippingCostGross();
    }

}