<?php
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classIssuesProcessingRules.php";

$smarty->assign('aProcessingRules', classIssuesProcessingRules::getIssuesList());
$smarty->assign('statuses', ['exclude','manual']);