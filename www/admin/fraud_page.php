<?php

use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\FraudCheckModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Order\Models\OrderFraudCheckModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;

global $smarty, $mode, $xcart_dir;

require './auth.php';
require $xcart_dir . '/include/security.php';

/** @var OrderModel $orderModel */

$orderid = Xcart::app()->request->request->get('orderid');

if ($orderid) {
    $orderModel = OrderModel::objects()->get(['orderid' => $orderid]);
}
if (!isset($orderModel)) {
    Xcart::app()->request->redirect('/admin/');
}

$REQUEST_METHOD = Xcart::app()->request->getMethod();

if ($REQUEST_METHOD === 'POST' && $mode === 'unlock_order') {
    $orderModel->time_last_opened_or_saved = 0;
    $orderModel->save();
    $unlock_message = 'Order unlocked.';
    $smarty->assign('order_unlocked', 'Y');
    $smarty->assign('unlock_message', $unlock_message);
} elseif ($REQUEST_METHOD === 'POST' && $mode === 'unlock_orders') {
    OrderModel::objects()->filter(['login_last_opened_or_saved' => Xcart::app()->user->login])->update(['time_last_opened_or_saved' => 0]);
    $unlock_message = 'All orders unlocked.';
    $smarty->assign('order_unlocked', 'Y');
    $smarty->assign('unlock_message', $unlock_message);
} else if ($orderModel) {
    $time_for_order_in_mins = 10; //Setting: operators can be on this mage during this time.
    $current_time = time();
    $login_last_opened_or_saved = $orderModel->login_last_opened_or_saved;
    $time_last_opened_or_saved = $orderModel->time_last_opened_or_saved;
    $diff_time_in_mins = ($current_time - $time_last_opened_or_saved) / 60;
    $you_have_right_to_change_order = true;
    if ($login_last_opened_or_saved === Xcart::app()->user->login) {
        $orderModel->time_last_opened_or_saved = $current_time;
        $time_last_opened_or_saved = $current_time;
    } else if ($diff_time_in_mins > $time_for_order_in_mins) {
        $orderModel->login_last_opened_or_saved = Xcart::app()->user->login;
        $orderModel->time_last_opened_or_saved = $current_time;
        $time_last_opened_or_saved = $current_time;
    } else {
        $you_have_right_to_change_order = false;
    }
    $orderModel->save();
    $time_unlock = $time_last_opened_or_saved + $time_for_order_in_mins * 60 + 60 * 60;
    if (!$you_have_right_to_change_order) {
        if ($REQUEST_METHOD === 'POST') {
            $top_message['content'] = 'Order not saved!';
            $top_message['type'] = 'E';
            Xcart::app()->request->redirect("fraud_page.php?orderid={$orderid}");
        }
        $operator_firstname = '';
        if ($operator_on_order = UserModel::objects()->get(['login' => $login_last_opened_or_saved])) {
            $operator_firstname = $operator_on_order->firstname ?: $operator_on_order->s_firstname ?: $operator_on_order->b_firstname;
        }

        $warning_message = "This order is locked by {$operator_firstname} ({$login_last_opened_or_saved}) until " . date("G:i", $time_unlock) . ".
If you need to make urgent changes to the order, ask {$operator_firstname} to unlock it.";
        $smarty->assign('warning_message', $warning_message);
        $smarty->assign('you_cannot_modify_order', 'Y');
    } else {
        $lock_message = 'You locked this order. Nobody can make any changes to it. The order will be unlocked at ' . date("G:i", $time_unlock) . '. You can also ';
        $smarty->assign('lock_message', $lock_message);
        $tmp_diff_time = time() - 60 * $time_for_order_in_mins;
        $count_locked_orders = OrderModel::objects()->filter(['login_last_opened_or_saved' => Xcart::app()->user->login, 'time_last_opened_or_saved__gt' => $tmp_diff_time])->count();
        $smarty->assign('count_locked_orders', $count_locked_orders);
    }
}

if ($REQUEST_METHOD === 'POST' && !($mode === 'unlock_order' || $mode === 'unlock_orders')) {
    $log = '';
    if ($mode === 'apply_changes_and_update_fraud_scores') {
        $log = "'Apply changes and update fraud scores' at 'Fraud page'";
    } elseif ($mode === 'apply_changes_and_update_fraud_scores_and_change_fraud_check_status') {
        $log = "'Apply changes, update fraud scores and change fraud check status' at 'Fraud page'";
    }
    $posted_data = Xcart::app()->request->post->get('posted_data');
    if (($mode === 'apply_changes_and_update_fraud_scores' || $mode === 'apply_changes_and_update_fraud_scores_and_change_fraud_check_status') && $posted_data) {
        $manual_action_not_selected = '';
        $overall_fraud_score = $bare_fraud_score = 0;
        foreach ($posted_data as $k => $v) {
            $question_code = strtoupper($v['question_code']);
            $manual_action = $v['manual_action'];
            if ($fraudCheckModel = FraudCheckModel::objects()->filter(['question_code' => $question_code])->limit(1)->get()) {
                [$orderFraudCheckModel, $is_created] = OrderFraudCheckModel::objects()->getOrNew([
                    'orderid' => $orderid,
                    'question_code' => $question_code
                ]);
                if ($fraudCheckModel->auto !== 'Y') {
                    $fraud_score = $fraudCheckModel->getScore($orderModel);
                    [$fraud_result, $bare_fraud_score, $additional_info] = $fraudCheckModel->getMethodResult($orderModel);
                    $orderFraudCheckModel->setAttributes([
                        'manual_action' => $manual_action,
                        'fraud_score' => $fraud_score,
                        'bare_fraud_score' => $bare_fraud_score,
                        'fraud_result' => $fraud_result,
                        'additional_info' => $additional_info
                    ]);
                }
                [$orderFraudCheckModel->fraud_score, $orderFraudCheckModel->bare_fraud_score, $orderFraudCheckModel->fraud_result] =
                    $orderFraudCheckModel->getScore($fraudCheckModel);

                $overall_fraud_score += $orderFraudCheckModel->fraud_score;
                if ($fraudCheckModel->question_code !== 'CHECK_TOTAL') {
                    $bareFraudScore += (float)$orderFraudCheckModel->fraud_score;
                }
                $orderFraudCheckModel->save();

                if ($fraudCheckModel->auto !== 'Y' && !$orderFraudCheckModel->manual_action) {
                    $manual_action_not_selected = 'Y';
                }
            }
        }

        $coreModule = Xcart::app()->getModule('Sites');
        $config = $coreModule->getSite()->getGlobalConfig();

        $current_overall_fraud_score = $orderModel->overall_fraud_score;
        $current_bare_fraud_score = $orderModel->bare_fraud_score;

        if ($acc_paymentid = Xcart::app()->request->post->get('acc_paymentid')) {
            foreach ($acc_paymentid as $key => $payment_id) {
                $orderModel->groups->filter(['order_group_id' => $key])->update(['acc_paymentid' => $payment_id]);
            }
        }

        $overall_fraud_score = price_format($overall_fraud_score);

        if ($current_overall_fraud_score != $overall_fraud_score) {
            if ($log) {
                $log .= '<br />';
            }
            $log .= "overall_fraud_score: {$current_overall_fraud_score} -> {$overall_fraud_score}";
            $orderModel->overall_fraud_score = $overall_fraud_score;
            $orderModel->bare_fraud_score = $bare_fraud_score;
            $orderModel->save();
            $orderModel->recalculateAccounting();
        }

        $current_fraud_status = $orderModel->fraud_status;
        $old_fraud_status = $current_fraud_status;
        if ($mode === 'apply_changes_and_update_fraud_scores') {
            if ($overall_fraud_score > $config['Overall_FC_threshold_for_Clear_status']) {
                $new_fraud_status = $config['Threshold_status'];
                if ($manual_action_not_selected === 'Y') {
                    $new_fraud_status = $config['below_threshold_status'];
                }
            } else {
                $new_fraud_status = $config['below_threshold_status'];
            }
        }

        $fraud_status = Xcart::app()->request->post->get('fraud_status');
        if ($mode === 'apply_changes_and_update_fraud_scores_and_change_fraud_check_status') {
            $new_fraud_status = $fraud_status;
        }

        if (!empty($new_fraud_status) && $current_fraud_status !== $new_fraud_status) {
            if ($log) {
                $log .= '<br />';
            }
            $current_fraud_status_name = $fraud_statuses[$current_fraud_status];
            $fraud_status_name = $fraud_statuses[$new_fraud_status];
            $log .= "fraud_status: {$current_fraud_status_name} -> {$fraud_status_name}";

            if ($orderModel->fraud_status === FraudStatusModel::STATUS_NEED_EXPERT &&
                $new_fraud_status === FraudStatusModel::STATUS_CLEARED &&
                $orderModel->getRiskScore() <= (float)$config['Overall_RS_threshold_for_Clear_status']) {
                $log .= "<br/>fraud_status: {$fraud_status_name} -> {$fraud_statuses[$config['Risk_Score_Threshold_status']]}";
                $new_fraud_status = $config['Risk_Score_Threshold_status'];
            }

            $underReviewUsers = explode(',', $config['Under_review_users']);
            if ($orderModel->fraud_status === $config['Risk_Score_Threshold_status'] &&
                !in_array(Xcart::app()->user->id, $underReviewUsers, true)) {
                $log .= "<br/>fraud_status: {$fraud_status_name} -> {$fraud_statuses[$config['Risk_Score_Threshold_status']]}";
                $new_fraud_status = $config['Risk_Score_Threshold_status'];
            }

            $orderModel->fraud_status = $new_fraud_status;
            $orderModel->save();

            if (OrderHelper::isAllowSendToOrderEntry($orderModel)) {
                OrderHelper::submitOrderEntry($orderModel);
            }
        }

        OrderLogModel::createLog($orderid, OrderLogModel::LOG_TYPE_XCART, $log);
    }

    $top_message['content'] = 'Done.';
    $top_message['type'] = 'I';
    Xcart::app()->request->redirect("fraud_page.php?orderid={$orderid}#buttons");
}

$smarty->assign('orderid', $orderid);
$smarty->assign('orderModel', $orderModel);
$smarty->assign('overall_fraud_score', $orderModel->overall_fraud_score);
$smarty->assign('fraud_checks', FraudCheckModel::objects()->order(['orderby']));
$smarty->assign('main', 'fraud_page');
$smarty->assign('all_processors', PaymentMethodModel::objects()->filter(['acc_proc' => 'Y'])->order(['orderby']));

$fraud_page_name = "Fraud check for order # {$orderModel->getOrderNumber()}";
$smarty->assign('fraud_page_name', $fraud_page_name);

array_push($location,
    [func_get_langvar_by_name('lbl_orders_management'), 'orders.php'],
    [func_get_langvar_by_name('lbl_order_details_label'), $orderModel->getAdminUrl()],
    [$fraud_page_name]
);

$smarty->assign('location', $location);

@include "{$xcart_dir}/modules/gold_display.php";
func_display('admin/home.tpl', $smarty);
