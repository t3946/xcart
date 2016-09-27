<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir . "/include/security.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classIssuesProcessingRules.php";

if (!empty($issue) && is_numeric($issue)) {
    $oIssue = new classIssuesProcessingRules(['issue_id'=>$issue]);

    $aImpactedProducts = $oIssue->getProductImpacted();

    $smarty->assign("oIssueProcessingRule", $oIssue);

    if (count($aImpactedProducts) > 0) {
        $objects_per_page = 50;
        $total_nav_pages = ceil(count($aImpactedProducts) / $objects_per_page) + 1;
        include $xcart_dir . "/include/navigation.php";
    }

    $smarty->assign("main", "external_marketplaces_quality_issues_view");
} else {
    $smarty->assign("main", "external_marketplaces_quality_issues");

    $smarty->assign('aProcessingRules', classIssuesProcessingRules::getIssuesList());
    $smarty->assign('statuses', ['exclude', 'manual']);
}
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);