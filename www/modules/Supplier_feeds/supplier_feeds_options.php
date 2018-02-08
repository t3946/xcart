<?php

if ($REQUEST_METHOD == 'POST'){

    if ($mode == 'Update_Supplier_feeds'){

	db_query("UPDATE $sql_tbl[config] SET value='$Feeds_storage_path' WHERE name='Feeds_storage_path'");
	db_query("UPDATE $sql_tbl[config] SET value='$Feeds_storage_login' WHERE name='Feeds_storage_login'");
	db_query("UPDATE $sql_tbl[config] SET value='$Feeds_storage_password' WHERE name='Feeds_storage_password'");



        if (!empty($Supplier_feeds) && is_array($Supplier_feeds)){
                foreach ($Supplier_feeds as $k => $v){
			if ($v["delete"] == "Y"){
				db_query("DELETE FROM $sql_tbl[supplier_feeds] WHERE feed_id='$v[feed_id]'");
			} else {
				db_query("UPDATE $sql_tbl[supplier_feeds] SET feed_name='$v[feed_name]', feed_type='$v[feed_type]', manufacturerid='$v[manufacturerid]', storefront_id='$v[storefront_id]', base_category_id='$v[base_category_id]', feed_file_name='$v[feed_file_name]', average_update_period='$v[average_update_period]', last_update_items_count='$v[last_update_items_count]', threshold='$v[threshold]', add_new_only='$v[add_new_only]', last_md5='$v[last_md5]', enabled='$v[enabled]', multiple_feed_destinations='$v[multiple_feed_destinations]', disable_search_of_discontinued_items='$v[disable_search_of_discontinued_items]', native_full_description='$v[native_full_description]' WHERE feed_id='$v[feed_id]'");
			}
                }
        }
    }
    elseif ($mode == 'Add_Supplier_feed') {
	db_query("INSERT INTO $sql_tbl[supplier_feeds] (feed_name) VALUES ('new feed name')");
    }

    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";

    func_header_location("configuration.php?option=Supplier_feeds");
}

$Supplier_feeds = func_query("SELECT * FROM $sql_tbl[supplier_feeds] ORDER BY feed_id ASC");

if (!empty($Supplier_feeds)){
	foreach ($Supplier_feeds as $k => $v){

                $cur_time = time();
                $date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time));
                $date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time+$v["average_update_period"]));
                $interval = $date1->diff($date2);
                $years = $interval->format("%y");
                $months = $interval->format("%m");
                $days = $interval->format("%d");
                $hours = $interval->format("%h");
                $mins = $interval->format("%i");
                $age_str = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";
                $Supplier_feeds[$k]["average_update_period_str"] = $age_str;
		$Supplier_feeds[$k]["last_update_late"] = intval($Supplier_feeds[$k]["last_update_late"]);
	}
}

$smarty->assign("Supplier_feeds", $Supplier_feeds);
?>
