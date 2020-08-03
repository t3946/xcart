<?php


namespace Modules\Order\Commands;


use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Models\FraudCheckModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderFraudCheckModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class FraudCheckCommand extends Command
{

    public function handle($arguments = [])
    {
        $config = Xcart::app()->getModule('Sites')->getSite()->getGlobalConfig();

        foreach (OrderModel::objects()->filter([
            'groups__cb_status__in' => [
                OrderStatusModel::ORDER_STATUS_UNPAID_PO,
                OrderStatusModel::ORDER_STATUS_AUTHORIZED,
                OrderStatusModel::ORDER_STATUS_COMPLETED,
                OrderStatusModel::ORDER_STATUS_PENDING_PARTIAL_REFUND,
                OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND,
            ],
            'fraud_status' => FraudStatusModel::STATUS_NOT_YET_STARTED
        ])->group(['orderid']) as $order) {
            $overallFraudScore = $bareFraudScore = 0;
            $new_fraud_status = null;
            $log = '';

            $extraModel = $order->extra_model;
            if ($extraModel && ($ip = $extraModel->getIP()) && $geoModel = GeoIpHelper::getMelissaIpLocation($ip)) {
                $ip .= " ({$geoModel})";
                $extraModel->ip = $ip;
                $extraModel->save();
            }

            /** @var FraudCheckModel $fraud */
            foreach (FraudCheckModel::objects()->order(['orderby']) as $fraud) {
                $fraud_score = $fraud->getScore($order);
                [$fraud_result, $bare_fraud_score, $additional_info, $manual_action] = $fraud->getMethodResult($order);
                [$orderFraud] = OrderFraudCheckModel::objects()->updateOrCreate([
                    'orderid' => $order->orderid,
                    'question_code' => $fraud->question_code
                ], [
                    'manual_action' => $manual_action,
                    'fraud_score' => $fraud_score,
                    'bare_fraud_score' => $bare_fraud_score,
                    'fraud_result' => $fraud_result,
                    'additional_info' => $additional_info
                ]);
                [$orderFraud->fraud_score, $orderFraud->bare_fraud_score, $orderFraud->fraud_result] = $orderFraud->getScore($fraud);
                $overallFraudScore += (float) $orderFraud->fraud_score;
                if ($fraud->question_code !== 'CHECK_TOTAL') {
                    $bareFraudScore += (float) $orderFraud->fraud_score;
                }
                $orderFraud->save();
            }

            $current_overall_fraud_score = (float) $order->overall_fraud_score;
            $current_bare_fraud_score = (float) $order->bare_fraud_score;

            if ($current_overall_fraud_score !== $overallFraudScore) {
                $log .= "overall_fraud_score: {$current_overall_fraud_score} -> {$overallFraudScore}<br/>";
                $log .= "bare_fraud_score: {$current_bare_fraud_score} -> {$bareFraudScore}";
            }
            $order->overall_fraud_score = $overallFraudScore;
            $order->bare_fraud_score = $bareFraudScore;


            /** @var FraudStatusModel $new_fraud_status */
            $new_fraud_status = $overallFraudScore > $config['Overall_FC_threshold_for_Clear_status'] ?
                FraudStatusModel::objects()->get(['code' => $config['Threshold_status']]) :
                FraudStatusModel::objects()->get(['code' => $config['below_threshold_status']]);

            /** @var FraudStatusModel $current_fraud_status */
            $current_fraud_status = $order->fraud_status_model;
            if ($new_fraud_status && $current_fraud_status->code !== $new_fraud_status->code) {
                if ($log) {
                    $log .= '<br />';
                }
                $log .= "fraud_status: {$current_fraud_status->name} -> {$new_fraud_status->name}";
            }

            $order->fraud_status = $new_fraud_status->code;

            (new OrderLogModel([
                'orderid' => $order->orderid,
                'type' => OrderLogModel::LOG_TYPE_XCART,
                'login' => '',
                'log' => $log
            ])
            )->save();

            $order->groups->update(['acc_paymentid' => $order->paymentid]);

            $order->save();
            //$order->recalculateAccounting();
        }
    }
}