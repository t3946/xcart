<?php

namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Types;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Helpers\OrderEventHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroup;

/**
 * @property float total_gross
 * @property int orderid
 * @property int order_group_id
 * @property float total_net
 * @property DistributorModel manufacturer
 * @property Manager|OrderTrackingModel[] trackings
 * @property OrderStatusModel|null cb_status_model
 * @property mixed cb_status
 * @property mixed actual_shipping_gross
 * @property mixed actual_shipping_net
 * @property OrderModel order
 * @property OrderStatusModel|null dc_status_model
 * @property mixed refunds
 * @property bool notify_sent
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
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'null' => false,
                'primary' => true,
            ],
            'shippingModel' => [
                'field' => 'shippingid',
                'class' => ForeignField::class,
                'modelClass' => ShippingModel::class,
                'null' => false,
            ],
            'cb_status_model' => [
                'class' => ForeignField::class,
                'field' => 'cb_status',
                'sqlType' => Types::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['cb_status' => 'code'],
                'null' => true,
            ],
            'dc_status_model' => [
                'class' => ForeignField::class,
                'field' => 'dc_status',
                'sqlType' => Types::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['dc_status' => 'code'],
                'null' => true,
            ],
            'bd_status_model' => [
                'class' => ForeignField::class,
                'field' => 'bd_status',
                'sqlType' => Types::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['bd_status' => 'code'],
                'null' => true,
            ],
            'd2a_status_model' => [
                'class' => ForeignField::class,
                'field' => 'd2a_status',
                'sqlType' => Types::STRING,
                'modelClass' => OrderStatusModel::class,
                'link' => ['d2a_status' => 'code'],
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
                'class' => HasManyField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['order_group_id' => 'order_group_id'],
            ],
            'trackings' => [
                'class' => HasManyField::class,
                'modelClass' => OrderTrackingModel::class,
                'link' => ['order_group_id' => 'order_group_id'],
            ],
            'invoices' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupInvoiceModel::class,
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ],
            'memos' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupMemoModel::class,
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ],
            'refunds' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupRefundModel::class,
                'link' => ['orderid' => 'orderid', 'manufacturerid' => 'manufacturerid'],
            ],
            'tracking' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => '',
            ],
            'accounting' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'manufacturer_data' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'OLD_accounting' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'amz_customer_notes' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'shipping_quote' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'distributor_price_multiplier' => [
                'class' => DecimalField::class,
                'default' => 1,
                'null' => false,
            ],
            'notify_sent' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false
            ]
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

    public function getRefunds()
    {
        $refs = $this->refunds->all();
        return $refs ? array_sum(array_map(static fn($a) => $a->total_gross, $refs)) : 0;
    }

    public function getRefundsModel()
    {
        return $this->refunds->limit(1)->get();
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
                if ($price = $detail->getAmazonCompetitorMinPrice()) {
                    $product = $price[0];
                    $res += $product * $detail->amount;
                } else {
                    return null;
                }
            }
        }
        return $res;
    }

    public function getAmazonCompetitorsMinShipping(): ?float
    {
        $res = null;

        if ($details = $this->detail_models) {
            foreach ($details as $detail) {
                if ($price = $detail->getAmazonCompetitorMinPrice()) {
                    $shipping = $price[1];
                    $res += $shipping;
                } else {
                    return null;
                }
            }
        }
        return $res;
    }

    public function getAmazonCompetitorsMinTotal(): ?float
    {
        if (!$this->getAmazonCompetitorsMinPrice()) {
            return null;
        }
        return $this->getAmazonCompetitorsMinPrice() + $this->getAmazonCompetitorsMinShipping();
    }

    public function isEnterOnAmazon(): bool
    {
        $min_total = $this->getAmazonCompetitorsMinTotal();
        if ($min_total === null) {
            return false;
        }
        return ($min_total <= $this->actual_shipping_gross + $this->getTotalCostToUs());
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function showPendingOrderMessage()
    {
        $enter_on_amazon = '';
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

        if ($distributor->submit_to_operator === 'through_distributor_website') {
            $enter_on_amazon = Xcart::app()->template->render('inSmarty/enter_order_on_amazon.tpl', [
                'distributor' => $distributor,
                'is_amazon' => $is_amazon,
                'order_group' => $this
            ]);
        }

        return str_replace('{enter_this_on_website}', $enter_on_amazon, $pending_order_message);
    }

    public function getProfitMargin(): ?float
    {
        $profit_margin = null;

        if ((float) $this->accounting_net_0) {
            $profit_margin = round($this->accounting_net_5_profit / $this->accounting_net_0 * 100, 2);
        }
        return $profit_margin;
    }

    public function getShippingCalculateLink(): string
    {
        if ($model = $this->shippingModel) {
            if ($model->code === 'UPS') {
                $dx = $this->manufacturer;
                $order = $this->order;
                return <<<URL
https://wwwapps.ups.com/ctc/request?loc=en_US&destCountry={$order->s_country}&destPostal={$order->s_zipcode}&destCity={$order->s_city}
&origPostal={$dx->m_zipcode}&origCity={$dx->m_city}&origCountry={$dx->m_country}
URL;
            }
        }
        return '';
    }

    public function __toString(): string
    {
        return (string) $this->manufacturer;
    }
}