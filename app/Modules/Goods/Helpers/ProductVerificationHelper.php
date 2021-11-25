<?php


namespace Modules\Goods\Helpers;


use DateTime;
use Modules\Goods\Admin\ProductVerificationAdmin;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\VerificationHistoryModel;
use Modules\Goods\Models\VerificationStatusModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Main\Xcart;

class ProductVerificationHelper
{
    public static function changeVerificationStatus(
        ProductModel $model,
        VerificationStatusModel $status,
        string $note
    ): void {
        if ((int)$model->verification_statusid === (int)$status->statusid) {
            return;
        }

        $old_status = $model->verification_status;
        $model->verification_status = $status;

        if ((int)$status->statusid === VerificationStatusModel::PRODUCT_STATUS_VERIFY) {
            $model->last_verify_date = (new DateTime())->getTimestamp();
        }

        if ($model->save()) {
            (new VerificationHistoryModel(
                [
                    'verification_note' => $note,
                    'product' => $model,
                    'user' => Xcart::app()->user,
                    'oldstatusid' => $old_status->statusid,
                    'newstatusid' => $status->statusid
                ]
            ))->save();

            $orders = OrderModel::objects()->filter(
                [
                    'groups__cb_status__in' => ProductVerificationAdmin::ORDER_CB_STATUSES,
                    'groups__detail_models__productid' => $model->productid,
                    'vn_status__isnt' => OrderStatusModel::ORDER_VN_STATUS_VERIFIED
                ]
            );
            /** @var OrderModel $order */
            foreach ($orders as $order) {
                if (in_array(
                    (int)$status->statusid,
                    [
                        VerificationStatusModel::PRODUCT_STATUS_PROBLEM_NOT_FIXED,
                        VerificationStatusModel::PRODUCT_STATUS_PROBLEM_FIXED
                    ],
                    true
                )) {
                    $log = "<b>{$model->productcode}</b> product verification status: {$old_status} -> {$status}\n";
                    if ($note) {
                        $log .= "Problem/fix description: {$note}";
                    }

                    OrderLogModel::createLog(
                        $order->orderid,
                        OrderLogModel::LOG_TYPE_XCART,
                        nl2br($log)
                    );
                }
                $order->updateVerificationStatus();
            }
        }
    }
}