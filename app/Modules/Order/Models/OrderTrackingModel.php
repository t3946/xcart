<?php


namespace Modules\Order\Models;

use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTrackingHelper;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Modules\Shipping\Models\TrackingLinksModel;
use Xcart\App\Main\Xcart;
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
            $current_dc_status = $this->order_group;
            $log = "<b>Tracking numbers:</b>\n<b>Added:</b> ";
            $log .= "{$owner->carrier->carrier} {$owner->link->shipping}: {$owner->tracknum}\n";

            if ($current_dc_status->dc_status !== OrderStatusModel::ORDER_DC_STATUS_SHIPPED) {
                $current_dc_status_value = $current_dc_status->dc_status_model->name;
                $current_dc_status->dc_status = OrderStatusModel::ORDER_DC_STATUS_SHIPPED;
                $new_value = $current_dc_status->dc_status_model->name;
                $log .= "<b>{$current_dc_status->manufacturer->code}:</b> dc_status: {$current_dc_status_value} -> {$new_value}\n";
                $current_dc_status->save();
            }
            (new OrderLogModel([
                'orderid' => $current_dc_status,
                'type' => OrderLogModel::LOG_TYPE_XCART,
                'login' => Xcart::app()->user->login,
                'log' => nl2br($log),
            ]))->save();
            OrderHelper::checkOrderTrackedAll($current_dc_status->order);
            if (($r = OrderTrackingHelper::trackAfterShip($owner)) && isset($r['data']['tracking']['id'])) {
                $this->aftership_id = $r['data']['tracking']['id'];
                $this->update(['aftership_id']);
            } else {
                //set AttentionTag
            }
        }
    }
    public function afterDelete($owner) {
        parent::afterDelete($owner);
        $log = "<b>Tracking numbers:</b>\n<b>Deleted:</b> ";
        $log .= "{$owner->carrier->carrier} {$owner->link->shipping}: {$owner->tracknum}\n";
        (new OrderLogModel([
            'orderid' => $owner->order_group->orderid,
            'type' => OrderLogModel::LOG_TYPE_XCART,
            'login' => Xcart::app()->user->login,
            'log' => nl2br($log),
        ]))->save();
        OrderHelper::checkOrderTrackedAll($this->order_group->order);
        OrderTrackingHelper::deleteAfterShip($owner);
    }
}