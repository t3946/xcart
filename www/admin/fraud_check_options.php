<?php

use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\FraudCheckModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;

global $smarty, $mode, $REQUEST_METHOD, $sql_tbl, $fraud_domains_free_email_provider, $Overall_FC_threshold_for_Clear_status;
global $Threshold_status, $Risk_Score_Threshold_status, $Overall_RS_threshold_for_Clear_status, $below_threshold_status;
global $fraud_Google_address_search_exclusions, $fraud_Google_phone_search_exclusions, $fraud_Google_email_search_exclusions, $fraud_checks;

if ($mode === 'Update_Fraud_check' && $REQUEST_METHOD === 'POST') {

    GlobalConfigModel::objects()->updateOrCreate(['name' => 'fraud_domains_free_email_provider'], ['value' => $fraud_domains_free_email_provider]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'Overall_FC_threshold_for_Clear_status'], ['value' => price_format($Overall_FC_threshold_for_Clear_status)]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'Threshold_status'], ['value' => $Threshold_status]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'Risk_Score_Threshold_status'], ['value' => $Risk_Score_Threshold_status]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'Overall_RS_threshold_for_Clear_status'], ['value' => $Overall_RS_threshold_for_Clear_status]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'below_threshold_status'], ['value' => $below_threshold_status]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'fraud_Google_address_search_exclusions'], ['value' => $fraud_Google_address_search_exclusions]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'fraud_Google_phone_search_exclusions'], ['value' => $fraud_Google_phone_search_exclusions]);
    GlobalConfigModel::objects()->updateOrCreate(['name' => 'fraud_Google_email_search_exclusions'], ['value' => $fraud_Google_email_search_exclusions]);

    if ($users = Xcart::app()->request->post->get('Under_review_users')) {
        GlobalConfigModel::objects()->updateOrCreate(['name' => 'Under_review_users'], ['value' => implode(',', $users)]);
    } else {
        GlobalConfigModel::objects()->updateOrCreate(['name' => 'Under_review_users'], ['value' => '']);
    }
    if ($fraud_checks !== null && is_array($fraud_checks)) {
        foreach ($fraud_checks as $k => $v) {
            $qc = strtoupper($v["question_code"]);
            FraudCheckModel::objects()->updateOrCreate(['question_code' => strtoupper($v["question_code"])],
                ['auto' => $v['auto'], 'importance_factor' => $v['importance_factor'], 'orderby' => $v['orderby'], 'question_template_body' => stripslashes($v['question_template_body'])]);
            $qq[] = $qc;
        }
        FraudCheckModel::objects()->exclude(['question_code__in' => $qq])->delete();
    }

    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";

    func_header_location("configuration.php?option=Fraud_check");
}

$fraud_checks = FraudCheckModel::objects()->asArray();
if (!$fraud_checks) {
    $fraud_checks[0]["id"] = "0";
}

$smarty->assign("fraud_checks", $fraud_checks);

$users = UserModel::objects()
    ->exclude(['position__in' => ['VRS', 'programmer']])
    ->filter(['usertype' => 'A', 'status' => 'Y', 'activity' => 'Y'])
    ->order(['firstname'])
    ->all();

$smarty->assign("users", $users);
$site = Xcart::app()->getModule('Sites')->getSite();
$smarty->assign('global_config', $site->getGlobalConfig());

$smarty->assign("row_max_index", $fraud_checks->count());

?>
