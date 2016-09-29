<?php
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classIssuesProcessingRules.php";

if ($REQUEST_METHOD == 'POST') {
    if (!empty($processing_rule_name) && is_array($processing_rule_name)) {
        foreach($processing_rule_name as $processing_rule_id => $processing_rule) {
            $oProcessingRule = new classIssuesProcessingRules(['issue_id'=>$processing_rule_id]);
            $oProcessingRule->updateIssueName($processing_rule);
        }
    }
}

$smarty->assign('aProcessingRules', classIssuesProcessingRules::getIssuesList());
$smarty->assign('statuses', ['exclude','manual']);