<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir."/include/security.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classIssuesProcessingRules.php";

$smarty->assign("main","external_marketplaces_quality_issues");

$smarty->assign('aProcessingRules', classIssuesProcessingRules::getIssuesList());
$smarty->assign('statuses', ['exclude','manual']);

$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);