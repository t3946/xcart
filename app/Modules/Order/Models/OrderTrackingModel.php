<?php


namespace Modules\Order\Models;

use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTrackingHelper;
use Modules\Payment\Models\ProcessorModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Modules\Shipping\Models\TrackingLinksModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property OrderGroupModel order_group
 * @property mixed carrier_id
 * @property TrackingLinksCarrierModel carrier
 * @property TrackingLinksModel link
 * @property mixed tracknum
 * @property mixed shipping_date
 * @property mixed|\Xcart\App\Orm\Fields\Field|\Xcart\App\Orm\Fields\FileField|\Xcart\App\Orm\Fields\ModelFieldInterface|null aftership_id
 */
class OrderTrackingModel extends Model
{
    public static function tableName()
    {
        return 'xcart_order_tracking';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'order_group' => [
                'field' => 'order_group_id',
                'class' => ForeignField::class,
                'modelClass' => OrderGroupModel::class,
                'link' => ['order_group_id' => 'order_group_id'],
            ],
            'link' => [
                'field' => 'linkid',
                'class' => ForeignField::class,
                'modelClass' => TrackingLinksModel::class,
                'link' => ['linkid' => 'linkid'],
                'null' => true,
                'default' => null
            ],
            'tracknum' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'shipping_date' => [
                'class' => DateField::class,
            ],
            'carrier' => [
                'field' => 'carrier_id',
                'class' => ForeignField::class,
                'modelClass' => TrackingLinksCarrierModel::class,
                'link' => ['carrier_id' => 'carrier_id'],
            ],
            'aftership_id' => [
                'class' => CharField::class,
                'null' => true
            ],
            'send_to_amazon' => [
                'class' => BooleanField::class,
                'default' => false
            ]
        ];
    }

    public function getLink(): string
    {
        if ($link = $this->carrier->link) {
            return str_replace('{{tracknum}}', $this->tracknum, $link);
        }
        return '';
    }

    /**
     * @param OrderTrackingModel$owner
     * @param $isNew
     * @throws \Exception
     */
    public function afterSave($owner, $isNew)
    {
        parent::afterSave($owner, $isNew);
        if ($isNew) {
            $order_group = $this->order_group;
            $head_log = [
                "<b>Tracking numbers:</b>",
                "<b>Added:</b> {$owner->carrier->carrier} {$owner->link->shipping}: $owner->tracknum"
            ];
            foreach ($head_log as $log) {
                OrderLogModel::createLog($order_group->orderid, OrderLogModel::LOG_TYPE_XCART, $log);
            }

            if (!in_array(
                $order_group->dc_status,
                [OrderStatusModel::ORDER_DC_STATUS_SHIPPED, OrderStatusModel::ORDER_DC_STATUS_DELIVERED],
                true
            )) {
                $current_dc_status_value = $order_group->dc_status_model->name;
                $order_group->dc_status = OrderStatusModel::ORDER_DC_STATUS_SHIPPED;
                $new_value = $order_group->dc_status_model->name;
                $order_group->save();
                OrderLogModel::createLog(
                    $order_group->orderid,
                    OrderLogModel::LOG_TYPE_XCART,
                    "<b>{$order_group->manufacturer->code}:</b> dc_status: $current_dc_status_value -> $new_value"
                );
            }

            OrderHelper::checkOrderTrackedAll($order_group->order);

            if (($r = OrderTrackingHelper::trackAfterShip($owner)) && isset($r['data']['tracking']['id'])) {
                $this->aftership_id = $r['data']['tracking']['id'];
                $this->update(['aftership_id']);
            }

            $order = $order_group->order;

            /** @var OrderTransactionModel $transaction */
            $transaction = $order->transactions
                ->filter(['transaction_status' => OrderTransactionModel::STATUS_CAPTURED])
                ->order(['id'])
                ->limit(1)
                ->get();

            if ($transaction && $transaction->payment_method_model->processor->processor_name === ProcessorModel::PAYMENT_NAME_STRIPE) {
                // TODO need to be tested
                //OrderTrackingHelper::trackStripe($owner, $order, $transaction);
            }
        }
    }
    public function afterDelete($owner) {
        parent::afterDelete($owner);

        OrderLogModel::createLog(
            $owner->order_group->orderid,
            OrderLogModel::LOG_TYPE_XCART,
            "<b>Tracking numbers:</b>"
        );
        OrderLogModel::createLog(
            $owner->order_group->orderid,
            OrderLogModel::LOG_TYPE_XCART,
            "<b>Deleted:</b>{$owner->carrier->carrier} {$owner->link->shipping}: $owner->tracknum"
        );

        OrderHelper::checkOrderTrackedAll($this->order_group->order);
        OrderTrackingHelper::deleteAfterShip($owner);
    }

    public function __toString()
    {
        if ($this->tracknum) {
            $carrier = $this->carrier;
            $link = $carrier->link;
            $sl = $link ? $link->shipping : '';
            $str = "Shipped on ". $this->getField('shipping_date')->getValue()->format('M d, Y'). " by {$carrier} {$sl} : {$this->tracknum}";
        }
        return $str ?? '';
    }
}