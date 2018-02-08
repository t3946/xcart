<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Amazon Settlement Reports Analyzer", "");

if ($REQUEST_METHOD=="POST") {


	if ($mode == "Acknowledgement" && !empty($reportId) && !empty($setAcknowledged)){

	}
	elseif ($mode == "GetReport" && !empty($reportId)){

	}
	elseif ($mode == "GetReportList" && !empty($setAcknowledged1)){

	}
}

$smarty->assign("mode", $mode);
$smarty->assign("main", "amazon_settlement_report_analyzer");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);
?>
