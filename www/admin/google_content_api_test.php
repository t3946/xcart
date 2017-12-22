<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files');

set_time_limit(0);
ini_set('memory_limit', '512M');




if ($REQUEST_METHOD == "POST") {

        if ($mode == "insert") {


                $top_message["content"] = "Inserted.";
                $top_message["type"] = "I";
        }
	elseif ($mode == "delete"){


		$top_message["content"] = "Deleted.";
		$top_message["type"] = "I";
	}

	func_header_location("google_content_api_test.php");
}

$location[] = array("Google Content API: test", "");

$smarty->assign("main", "google_content_api_test");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
