<?php
use Xcart\External_Marketplaces\IssuesProcessingRules;
global $xcart_dir;

if ($REQUEST_METHOD == 'POST') {
    if (!empty($processing_rule_name) && is_array($processing_rule_name)) {
        foreach($processing_rule_name as $processing_rule_id => $processing_rule) {
            $oProcessingRule = new IssuesProcessingRules(['issue_id'=>$processing_rule_id]);
            $oProcessingRule->updateIssueName($processing_rule);
        }
    }
}

$smarty->assign('aProcessingRules', IssuesProcessingRules::getIssuesList());
$smarty->assign('statuses', ['exclude','manual']);