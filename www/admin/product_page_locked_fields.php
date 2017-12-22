<?php
require $xcart_dir."/include/security.php";

if ($REQUEST_METHOD=="POST") {
	if ($mode == "update") {

		if (is_array($feed_id)) {
			foreach ($feed_id as $k=>$v) {

				if (isset($admin_lock[$k]) && $admin_lock[$k] == "Y"){
					$admin_lock_value = "Y";
				} else {
					$admin_lock_value = "N";
				}

				db_query("UPDATE $sql_tbl[manufacturer_feed_fields] SET admin_lock='$admin_lock_value' WHERE manufacturerid='$manufacturerid' AND feed_id='$v' AND field_name='".$field_name[$k]."'");
			}

			$top_message["content"] = "Done.";
		}
	}

	func_header_location("manufacturers.php?manufacturerid=$manufacturerid&distributor_section=$distributor_section");
}

$manufacturer_feed_fields = func_query("SELECT $sql_tbl[manufacturer_feed_fields].*, $sql_tbl[supplier_feeds].feed_name FROM $sql_tbl[manufacturer_feed_fields] LEFT JOIN $sql_tbl[supplier_feeds] ON $sql_tbl[supplier_feeds].feed_id=$sql_tbl[manufacturer_feed_fields].feed_id WHERE $sql_tbl[manufacturer_feed_fields].manufacturerid='$manufacturerid' ORDER BY $sql_tbl[manufacturer_feed_fields].field_name");
$smarty->assign("manufacturer_feed_fields", $manufacturer_feed_fields);
?>
