<?php


namespace Modules\Order\Commands;


use Modules\Core\Models\FraudFAQuestionModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Helpers\FraudCheckFAHelper;
use Modules\Order\Models\FraudCheckModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderFraudCheckModel;
use Modules\Order\Models\OrderFraudFACheckModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class FraudCheckCommandV2 extends Command
{

    public function handle($arguments = [])
    {
        $config = Xcart::app()->getModule('Sites')->getSite()->getGlobalConfig();
        /** @var OrderModel $order */
        foreach (OrderModel::objects()->filter([
            'groups__cb_status__in' => [
                OrderStatusModel::ORDER_STATUS_UNPAID_PO,
                OrderStatusModel::ORDER_STATUS_AUTHORIZED,
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
            foreach (FraudCheckModel::objects()->order(['orderby'])->filter(['active' => 'Y']) as $fraud) {
                [$fraud_result, $fraud_score, $add_info, $action] = $fraud->getScore($order);
                [$orderFraud] = OrderFraudCheckModel::objects()->updateOrCreate([
                    'orderid' => $order->orderid,
                    'question_id' => $fraud->id
                ], [
                    'manual_action' => $action,
                    'fraud_score' => $fraud_score,
                    'fraud_result' => $fraud_result,
                    'additional_info' => $add_info
                ]);
                $overallFraudScore += (float)$orderFraud->fraud_score;
                if ($fraud->question_code !== 'DC-GT') {
                    $bareFraudScore += (float)$orderFraud->fraud_score;
                }
                $orderFraud->save();
            }

            $fa_heler = new FraudCheckFAHelper($order);
            $fa_heler->fetchBaseDataOrder();
            /** @var FraudFAQuestionModel $fraud_fa */
            foreach (FraudFAQuestionModel::objects()->order(['order_by']) as $fraud_fa) {
                [$fraud_result, $fraud_score, $info] = $fraud_fa->getScore($order, true, $fa_heler);
                [$order_fraud_fa] = OrderFraudFACheckModel::objects()->updateOrCreate([
                    'order_id' => $order->orderid,
                    'question_id' => $fraud_fa->question_id
                ], [
                    'fraud_result' => $fraud_result,
                    'fraud_score' => $fraud_score,
                    'additional_info' => $info ?? null
                ]);
                /** @var OrderFraudFACheckModel $order_fraud_fa */
                $overallFraudScore += (float)$order_fraud_fa->fraud_score;
                $bareFraudScore += (float)$order_fraud_fa->fraud_score;

                $order_fraud_fa->save();
            }

            $current_overall_fraud_score = (float)$order->overall_fraud_score;
            $current_bare_fraud_score = (float)$order->bare_fraud_score;

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

            if ($order->groups->filter(['manufacturer__expertise' => true])->count()) {
                $new_fraud_status = FraudStatusModel::objects()->get(['code' => 'E']);
            }

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