<?php

namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Helpers\OrderEventHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroup;

/**
 * @property float total_gross
 * @property int orderid
 * @property int order_group_id
 * @property float total_net
 * @property DistributorModel manufacturer
 * @property OrderStatusModel|null cb_status_model
 * @property mixed cb_status
 */
class OrderGroupModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass(): string
    {
        return OrderGroup::class;
    }

    public static function tableName()
    {
        return 'xcart_order_groups';
    }

    public static function getFields()
    {
        return [
            'order_group_id' => [
                'class' => AutoField::class,
            ],
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'null' => false,
                'primary' => true,
            ],
            'shippingModel' => [
                'field' => 'shippingid',
                'class' => ForeignField::className(),
                'modelClass' => ShippingModel::className(),
                'null' => false,
            ],
            'cb_status_model' => [
                'class' => ForeignField::class,
                'field' => 'cb_status',
                'sqlType' => Type::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['cb_status' => 'code'],
                'null' => true,
            ],
            'dc_status_model' => [
                'class' => ForeignField::class,
                'field' => 'dc_status',
                'sqlType' => Type::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['dc_status' => 'code'],
                'null' => true,
            ],
            'bd_status_model' => [
                'class' => ForeignField::class,
                'field' => 'bd_status',
                'sqlType' => Type::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['bd_status' => 'code'],
                'null' => true,
            ],
            'payment_method' => [
                'field' => 'acc_paymentid',
                'class' => ForeignField::class,
                'modelClass' => PaymentMethodModel::class,
                'link' => ['acc_paymentid' => 'paymentid'],
                'null' => false,
            ],
            'detail_models' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderDetailModel::className(),
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'product_model__manufacturerid'],
            ],
            'invoices' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupInvoiceModel::className(),
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ],
            'memos' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupMemoModel::className(),
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ],
            'refunds' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupRefundModel::className(),
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ],
            'tracking' => [
                'class' => SerializeField::className(),
                'null' => false,
                'default' => '',
            ],
            'accounting' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'manufacturer_data' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'OLD_accounting' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'amz_customer_notes' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],

        ];
    }

    private static $shippingModels = [];

    public function getShippingModel()
    {
        if (isset(self::$shippingModels[$this->shippingid])) {
            $this->shippingModel = self::$shippingModels[$this->shippingid];
            return self::$shippingModels[$this->shippingid];
        }

        self::$shippingModels[$this->shippingid] = $this->shippingModel;
        return self::$shippingModels[$this->shippingid];
    }

    public function getPaymentMethodId()
    {
        return $this->acc_paymentid;
    }

    /**
     * @param OrderGroup $model
     */
    public function afterFetchDataModel($model)
    {

    }

    private $productModels = null;

    public function getProductModels()
    {
        if (is_null($this->productModels)) {
            $this->productModels = ProductModel::objects()
                ->getQuerySet()
                ->join('inner join', 'xcart_order_details', ['productid' => 'od.productid'], 'od')
                ->filter(['manufacturerid' => $this->manufacturerid, 'od.orderid' => $this->orderid])
                ->all();
        }
        return $this->productModels;
    }

    public function getRefunds()
    {
        $refs = $this->refunds->all();
        return $refs ? array_sum(array_map(function ($a) {
            return $a->total_gross;
        }, $refs)) : 0;
    }

    public function afterSave($owner, $isNew)
    {
        parent::afterSave($owner, $isNew); // TODO: Change the autogenerated stub

        foreach ($this->getAttributes() as $attribute => $value) {
            OrderEventHelper::registerAfterSaveEvent($this->orderid, $attribute, $value, $this->getOldAttribute($attribute));
        }
    }

    public function getEstimateProfit($additional_shipping_charge = null): ?array
    {
        if ($order_payment_method = $this->payment_method ?: $this->order->payment_method_model) {

            $estimated_profit = (1 - $order_payment_method->acc_percent / 100) * $this->total_gross - $order_payment_method->acc_per_trans - $this->getTotalCostToUs() - $this->actual_shipping_gross;

            $estimated_profit_margin = $estimated_profit / ((1 - $order_payment_method->acc_percent / 100) * $this->total_gross);

            if ($additional_shipping_charge) {

                $estimated_profit_after_additional_payment = $estimated_profit + (1 - $order_payment_method->acc_percent / 100) * $additional_shipping_charge - $order_payment_method->acc_per_trans;

                $estimated_profit_margin_after_additional_payment = $estimated_profit_after_additional_payment / ((1 - $order_payment_method->acc_percent / 100) * ($this->total_gross + $additional_shipping_charge));

            }

            return [$estimated_profit, $estimated_profit_margin, $estimated_profit_after_additional_payment ?: null, $estimated_profit_margin_after_additional_payment ?: null];

        }

        return null;
    }

    public function getAmazonCompetitorsMinPrice(): ?float
    {
        $res = null;

        if ($details = $this->detail_models) {
            foreach ($details as $detail) {
                [$product] = $detail->getAmazonCompetitorMinPrice();
                $res += $product * $detail->amount;
            }
        }
        return $res;
    }

    public function getAmazonCompetitorsMinShipping(): ?float
    {
        $res = null;

        if ($details = $this->detail_models) {
            foreach ($details as $detail) {
                [, $shipping] = $detail->getAmazonCompetitorMinPrice();
                $res += $shipping;
            }
        }
        return $res;
    }

    public function getAmazonCompetitorsMinTotal(): ?float
    {
        return $this->getAmazonCompetitorsMinPrice() + $this->getAmazonCompetitorsMinShipping();
    }

    public function isEnterOnAmazon(): bool
    {
        return ($this->getAmazonCompetitorsMinTotal() <= $this->actual_shipping_gross + $this->getTotalCostToUs());
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function showPendingOrderMessage()
    {
        $distributor = $this->manufacturer;

        if ($is_amazon = $this->isEnterOnAmazon()) {
            $label = 'lbl_pending_order_message_amazon';
            $orig_code = $distributor->code;
            if ($distributor = DistributorModel::objects()->get(['code' => DistributorModel::AMAZON_MANUFACTURER_CODE])) {
                $distributor->code = $orig_code;
            }
        } else {
            $label = 'lbl_pending_order_message1';
        }

        $pending_order_message = func_get_langvar_by_name($label, null, false, true);

        $enter_on_amazon = Xcart::app()->template->render('inSmarty/enter_order_on_amazon.tpl', [
            'distributor' => $distributor,
            'is_amazon' => $is_amazon
        ]);

        return str_replace('{enter_this_on_amazon}', $enter_on_amazon, $pending_order_message);
    }
}