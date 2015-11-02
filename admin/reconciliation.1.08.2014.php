<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files');

set_time_limit(0);
ini_set('memory_limit', '512M');

x_session_register("search_data");


function func_find_reconciliations_orders($reconciliations_to_check, $orders_to_check) {
	global $sql_tbl, $manufacturerid_info, $login;	

	$found_orders_for_several_orders_at_once = array();

	$found_orders_for_charges_each_order_separately = array();
	$found_orders_for_charges_each_order_separately_ref_to_us = array();

	$found_orders_for_each_order_separately_p = array();
	$found_orders_for_each_order_separately_s = array();
	$found_orders_for_each_order_separately_ref_to_us = array();


        foreach ($reconciliations_to_check as $k => $v){

		func_flush(".");
		func_flush("<br />\n");

		$r_id = $v["id"];

		if ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_may_charge_for_several_orders_at_once"){

                        $amount_csv = $v["amount_csv"];
			$amount_csv_abs = abs($amount_csv);

                        if (!empty($orders_to_check) && is_array($orders_to_check)){

				$bd_status_invoiced = 0;
				$bd_status_not_invoiced = 0;
				$found_orders_for_several_orders_at_once_total_amount = 0;

###

		                $current_orders = func_query("SELECT $sql_tbl[order_groups].orderid, $sql_tbl[orders].date, $sql_tbl[order_groups].bd_status, $sql_tbl[order_groups].part_of_total_transaction_in_amount_of, $sql_tbl[order_groups].accounting, $sql_tbl[order_groups].manufacturerid, $sql_tbl[order_groups].total_gross FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $sql_tbl[order_groups].reconciliation_id='$r_id'");

  		                if (!empty($current_orders)){
		
                		        foreach ($current_orders as $ko => $vo){

			                        if ($ko % 10 == 0) {
                        			        func_flush(".");
			                                if($ko % 500 == 0) {
                        			                func_flush("<br />\n");
			                                }

                        			        func_flush();
			                        }

		                                $empty_accounting = true;

        		                        if (!empty($vo["accounting"])){
                		                        $accounting = unserialize($vo["accounting"]);
                        		                $Cost_to_us = price_format($accounting[1]["gross"]);
                                		        $Shipping = price_format($accounting[2]["gross"]);

	                                	        if ( (!empty($Cost_to_us) && $Cost_to_us > 0) || (!empty($Shipping) && $Shipping > 0) ){
        	                                	        $current_orders[$ko]["accounting"]= $accounting;
                	                                	$empty_accounting = false;
	                        	                }
		                                }

        	                	        if ($empty_accounting){
                		                        unset($current_orders[$ko]);
                        	        	}
	        	                }

        		                $current_orders = array_values($current_orders);
	
					if (!empty($current_orders)){
						foreach($current_orders as $kk => $vv){
							$found_orders_for_several_orders_at_once_total_amount += $vv["accounting"][1]["gross"] + $vv["accounting"][2]["gross"];
						}
					}
                		}
###

//if ($r_id == "1636"){
//func_print_r($found_orders_for_several_orders_at_once_total_amount);
//die();
//}


                                foreach($orders_to_check as $kk => $vv){

	                            if ($kk % 10 == 0) {
                                        func_flush(".");
                                        if($kk % 500 == 0) {
         	                               func_flush("<br />\n");
                                        }

                                        func_flush();
                                    }


				    if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"]){

                                        $time_to_db = time();

					$current_reconciliation_id = func_query_first_cell("SELECT reconciliation_id FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");

//func_print_r($current_reconciliation_id, $r_id, $vv["orderid"], $v["manufacturerid"]);

                                        $price_to_search = $vv["accounting"][1]["gross"] + $vv["accounting"][2]["gross"];

					$is_such_orderid_in_reconciliation_orderid_table = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id' AND orderid='$vv[orderid]'");

                                        if (
						$amount_csv < 0 && $amount_csv_abs == $vv["part_of_total_transaction_in_amount_of"] 
						&& $price_to_search <= abs($amount_csv) 
						&& ($found_orders_for_several_orders_at_once_total_amount + $price_to_search) <= abs($amount_csv)
						&& !in_array($vv["orderid"], $found_orders_for_several_orders_at_once)
						&& empty($current_reconciliation_id)
						&& empty($is_such_orderid_in_reconciliation_orderid_table)
					){

//						if (empty($part_of_total_transaction_in_amount_of))
//							$part_of_total_transaction_in_amount_of = $vv["part_of_total_transaction_in_amount_of"];

						$found_orders_for_several_orders_at_once_total_amount += $price_to_search;
                                                $found_orders_for_several_orders_at_once[] = $vv["orderid"];

                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
						db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");


//func_print_r($k, $vv["orderid"], $v);
//die();

                                                if ($vv['bd_status'] == "X"){
							$bd_status_invoiced++;
                                                } else {
							$bd_status_not_invoiced++;
						}
                                        }
				    }
                                }
//die();

	                        $action='';
        	                if (empty($bd_status_not_invoiced) && $bd_status_invoiced > 0 && $amount_csv_abs == $found_orders_for_several_orders_at_once_total_amount){
                	                $action='R';

###
	                                $orderids = func_query("SELECT orderid FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$k'");

	                                if (!empty($orderids)){

					    foreach($orderids as $kk => $vv){
                                                $current_bd_status = func_query_first_cell("SELECT bd_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");

                                                if ($current_bd_status != "Y"){
                                                        $current_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_bd_status'");
                                                        $new_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='Y'");
                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B> ";

                                                        $log .= "B2D status: ".$current_bd_status_name." -> ".$new_bd_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);

                                                        db_query("UPDATE $sql_tbl[order_groups] SET bd_status='Y' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                                }
					    }
					}
###


                        	}

//				db_query("UPDATE $sql_tbl[reconciliations] SET action='$action', total_order_amounts='$found_orders_for_several_orders_at_once_total_amount' WHERE id='$r_id'");
				db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");

                        }

		} 
		elseif ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"){


			db_query("DELETE FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id'");
			db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='0' WHERE reconciliation_id='$r_id'");

//func_print_r($v);
//die();


                        $amount_csv = $v["amount_csv"];
                        if (!empty($orders_to_check) && is_array($orders_to_check)){

                                foreach($orders_to_check as $kk => $vv){

                                    if ($kk % 10 == 0) {
                                        func_flush(".");
                                        if($kk % 500 == 0) {
                                               func_flush("<br />\n");
                                        }

                                        func_flush();
                                    }

				    if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"]){

                                        $time_to_db = time();

                                        $price_to_search_p = $vv["accounting"][1]["gross"];
                                        if ($amount_csv < 0 && $price_to_search_p == abs($amount_csv) && !in_array($vv["orderid"], $found_orders_for_each_order_separately_p)){
                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
                                                $found_orders_for_each_order_separately_p[] = $vv["orderid"];

						$update_bd_status = $vv['bd_status'];
                                                $action='';
                                                if ($vv['bd_status'] == "X"){
                                                        $action='R';

                                                        $current_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='X'");
                                                        $new_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='Y'");
                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B> ";
                                                
                                                        $log .= "B2D status: ".$current_bd_status_name." -> ".$new_bd_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);
							$update_bd_status = 'Y';
                                                }
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");
						db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$r_id', bd_status='$update_bd_status' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                        }

                                        $price_to_search_s = $vv["accounting"][2]["gross"];
                                        if ($amount_csv < 0 && $price_to_search_s == abs($amount_csv) && !in_array($vv["orderid"], $found_orders_for_each_order_separately_s)){
                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
                                                $found_orders_for_each_order_separately_s[] = $vv["orderid"];

						$update_bd_status = $vv['bd_status'];
                                                $action='';
                                                if ($vv['bd_status'] == "X"){
                                                        $action='R';

                                                        $current_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='X'");
                                                        $new_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='Y'");
                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B> ";

                                                        $log .= "B2D status: ".$current_bd_status_name." -> ".$new_bd_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);
                                                        $update_bd_status = 'Y';

                                                }
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");
						db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$r_id', bd_status='$update_bd_status' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                        }

                                        $price_to_search = $vv["accounting"][4]["gross"];
                                        if ($amount_csv > 0 && $price_to_search == $amount_csv && !in_array($vv["orderid"], $found_orders_for_each_order_separately_ref_to_us)){
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='R' WHERE id='$r_id'");
                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db, ref_to_us) VALUES ('$r_id', '$vv[orderid]', '$time_to_db', 'Y')");
						db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                                $found_orders_for_each_order_separately_ref_to_us[] = $vv["orderid"];

###

                                                $current_ru_status = func_query_first_cell("SELECT ru_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                                if ($current_ru_status != "RR"){

                                                        $current_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_ru_status'");
                                                        $new_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='RR'");

                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B> ";
                                                        $log .= "REF TO US status: ".$current_ru_status_name." -> ".$new_ru_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);

                                                        db_query("UPDATE $sql_tbl[order_groups] SET ru_status='RR' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                                }
###
                                        }
				    }
                                }
                        }


		}
		elseif ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_separately"){

			db_query("DELETE FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id'");
			db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='0' WHERE reconciliation_id='$r_id'");

			$amount_csv = $v["amount_csv"];
			if (!empty($orders_to_check) && is_array($orders_to_check)){

				foreach($orders_to_check as $kk => $vv){

                                    if ($kk % 10 == 0) {
                                        func_flush(".");
                                        if($kk % 500 == 0) {
                                               func_flush("<br />\n");
                                        }

                                        func_flush();
                                    }

				    if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"]){

					$time_to_db = time();

					$price_to_search = $vv["accounting"][1]["gross"] + $vv["accounting"][2]["gross"];
					if ($amount_csv < 0 && $price_to_search == abs($amount_csv) && !in_array($vv["orderid"], $found_orders_for_charges_each_order_separately)){
						db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
						$found_orders_for_charges_each_order_separately[] = $vv["orderid"];

						$action='';
						if ($vv['bd_status'] == "X"){
							$action='R';

                                                        $current_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='X'");
                                                        $new_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='Y'");
                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B> ";

                                                        $log .= "B2D status: ".$current_bd_status_name." -> ".$new_bd_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);

							$vv['bd_status'] = "Y";
						}


						db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");
						db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$r_id', bd_status='$vv[bd_status]' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
					}

					$price_to_search = $vv["accounting"][4]["gross"];
					if ($amount_csv > 0 && $price_to_search == $amount_csv && !in_array($vv["orderid"], $found_orders_for_charges_each_order_separately_ref_to_us)){
						db_query("UPDATE $sql_tbl[reconciliations] SET action='R' WHERE id='$r_id'");
						db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db, ref_to_us) VALUES ('$r_id', '$vv[orderid]', '$time_to_db', 'Y')");
						db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
						$found_orders_for_charges_each_order_separately_ref_to_us[] = $vv["orderid"];

###

						$current_ru_status = func_query_first_cell("SELECT ru_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
						if ($current_ru_status != "RR"){

		                                        $current_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_ru_status'");
                		                        $new_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='RR'");

							$log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B> ";
                                		        $log .= "REF TO US status: ".$current_ru_status_name." -> ".$new_ru_status_name."<br />";
							func_log_order($vv["orderid"], 'X', $log, $login);

							db_query("UPDATE $sql_tbl[order_groups] SET ru_status='RR' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
						}
###
					}
				    }
				}
			}
//func_print_r($reconciliations_to_check);
//die("AAA");
		}
        }
} //function func_find_reconciliations_orders


$manufacturerid_info = func_query_hash("SELECT code, manufacturerid, d_bulk_or_individual_order_payments, d_search_keyphrase_for_reconciliation, manufacturer FROM $sql_tbl[manufacturers]", 'manufacturerid', false);

$config_reconciliation_search_keyphrases = func_query("SELECT * FROM $sql_tbl[reconciliation_search_keyphrases] ORDER BY code");

if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
	foreach ($config_reconciliation_search_keyphrases as $k => $v){
		$config_reconciliation_search_keyphrases[$k]["total_amount"] = 0;
		$config_reconciliation_search_keyphrases[$k]["total_amount_with_abs"] = 0;
		$config_reconciliation_search_keyphrases[$k]["found_records"] = array();
	}
}

//func_print_r($manufacturerid_info);
//die();

if ($REQUEST_METHOD == "POST") {

        if ($mode == "search") {

                if (!empty($date_csv_Start)){
			$posted_data["date_csv"]["start_date_str"] = $date_csv_Start;
                        $start_date_arr = explode("/", $date_csv_Start);
                        $posted_data["date_csv"]["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);
                }

                if (!empty($date_csv_End)){
                        $posted_data["date_csv"]["end_date_str"] = $date_csv_End;
                        $end_date_arr = explode("/", $date_csv_End);
                        $posted_data["date_csv"]["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
                }

                $search_data["reconciliation_tab_".$tab] = $posted_data;
        }
	elseif ($mode == "update" && !empty($action) && is_array($action)){

		foreach ($action as $k => $v){
			db_query("UPDATE $sql_tbl[reconciliations] SET action='$v' WHERE id='$k'");

                        if ($v == "R"){
                
                                $orderids = func_query("SELECT orderid FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$k'");
                                $manufacturerid = func_query_first_cell("SELECT manufacturerid FROM $sql_tbl[reconciliations] WHERE id='$k'");

                                if (!empty($orderids) && !empty($manufacturerid)){

                                        foreach ($orderids as $kk => $vv){

                                                $current_bd_status = func_query_first_cell("SELECT bd_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$manufacturerid'");
                        
                                                if ($current_bd_status != "Y"){
                                                        $current_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_bd_status'");
                                                        $new_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='Y'");
                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$manufacturerid]["code"].":</B> ";

                                                        $log .= "B2D status: ".$current_bd_status_name." -> ".$new_bd_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);
                                
                                                        db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='$k', bd_status='Y' WHERE orderid='$vv[orderid]' AND manufacturerid='$manufacturerid'");
                                                }
                                        }
                                }
                        }


		}

		$top_message["content"] = "Done.";
		$top_message["type"] = "I";
	}
//	elseif ($mode == "import"){
//	elseif ($mode == "import" && $_FILES["userfile"]["type"] == "text/csv"){
	elseif ($mode == "import" && $_FILES["userfile"]["error"]=="0"){

//func_print_r($_FILES);
//die("1");

		$cur_time = time();

		$userfile = $xcart_dir . "/files/reconciliation_feeds/".$cur_time.".csv";

		if (move_uploaded_file($_FILES["userfile"]['tmp_name'], $userfile)) {

                        if ($delimiter == 'tab')
                                $delimiter = "\t";

	                $handle = @func_fopen($userfile, "r", true);
			if ($handle) {

//				$config_reconciliation_search_keyphrases = func_query("SELECT * FROM $sql_tbl[reconciliation_search_keyphrases]");

				$line_number = 0;
				$count_added_rows = 0;
				$amount_csv_total = 0;
				$min_date_in_file = 0;
				$max_date_in_file = 0;

				$PayPal_file = false;
				$transaction_type = "";


				while (($buffer = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {

					$line_number++;

					$tmp_net_field = trim($buffer[9]);
					$tmp_net_field = strtoupper($tmp_net_field);
					if ($tmp_net_field == "NET"){
						$PayPal_file = true;
						$transaction_type = "P";
					}

					if ($line_number == "1" && $PayPal_file)
						continue;

					if ( !($buffer[7] < 0 && $buffer[8]<=0) && $PayPal_file)
                                                continue;

//func_print_r($buffer, $PayPal_file, $line_number);
//die();

					if ($PayPal_file){
                                                $description_csv = addslashes(trim($buffer[3]));
                                                $amount_csv = trim($buffer[9]);
					}
					else {
						$description_csv = addslashes(trim($buffer[4]));
						$amount_csv = trim($buffer[1]);
					}

					$date_csv_tmp = trim($buffer[0]);
					$date_csv_arr = explode("/", $date_csv_tmp);
					$date_csv = mktime(0, 0, 0, $date_csv_arr[0], $date_csv_arr[1], $date_csv_arr[2]);
					
					if (empty($min_date_in_file)){
						$min_date_in_file = $date_csv;
					} elseif ($date_csv < $min_date_in_file && !empty($date_csv)) {
						$min_date_in_file = $date_csv;
					}

					if ($date_csv > $max_date_in_file){
						$max_date_in_file = $date_csv;
					}

                                        $amount_csv_total += $amount_csv;

					$insert_to_db = false;

					$is_such_description_csv = func_query_first("SELECT * FROM $sql_tbl[reconciliations] WHERE description_csv='$description_csv' AND date_csv='$date_csv' AND amount_csv='$amount_csv'");

					if (!empty($is_such_description_csv) && is_array($is_such_description_csv)){
						if ($is_such_description_csv["file_upload_date"] == $cur_time){
							$insert_to_db = true;
						}
					} else {
						$insert_to_db = true;
					}

					if ($insert_to_db){
						
						$action = "";
						if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
							foreach ($config_reconciliation_search_keyphrases as $k => $v){
							    if (!empty($description_csv) && !empty($v["search_keyphrase"])){
								if (strpos($description_csv, $v["search_keyphrase"]) !== false){
									$action = "D"; // Drop
								}
							    }
							}
						}

						$manufacturerid = '0';
                                                foreach ($manufacturerid_info as $k => $v){
						    if (!empty($description_csv) && !empty($v["d_search_keyphrase_for_reconciliation"])){
	                                                if (strpos($description_csv, $v["d_search_keyphrase_for_reconciliation"]) !== false){
        	                                                $manufacturerid = $k;
                                                        }
						    }
                                                }

						db_query("INSERT INTO $sql_tbl[reconciliations] (description_csv, date_csv, amount_csv, file_upload_date, action, manufacturerid, transaction_type) VALUES ('$description_csv', '$date_csv', '$amount_csv', '$cur_time', '$action', '$manufacturerid', '$transaction_type')");
						$count_added_rows++;
					}
				}
				fclose($handle);

				db_query("INSERT INTO $sql_tbl[reconciliation_upload_info] (date, orig_file_name, local_file, login, min_date_in_file, max_date_in_file, count_lines, checksum, count_added_rows) VALUES ('$cur_time', '".$_FILES["userfile"]["name"]."', '$userfile', '$login', '$min_date_in_file', '$max_date_in_file', '$line_number', '$amount_csv_total', '$count_added_rows')");

				$top_message["content"] = "File uploaded.";
				$top_message["type"] = "I";
			} else {
				fclose($handle);
				@unlink($userfile);

                                $top_message["content"] = func_get_langvar_by_name("msg_err_file_wrong");
                                $top_message["type"] = "E";
			}
		}
	}
	elseif ($mode == "find_orders"){

                if (!empty($date_csv_Start)){
                        $posted_data["date_csv"]["start_date_str"] = $date_csv_Start;
                        $start_date_arr = explode("/", $date_csv_Start);
                        $posted_data["date_csv"]["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);
                }

                if (!empty($date_csv_End)){
                        $posted_data["date_csv"]["end_date_str"] = $date_csv_End;
                        $end_date_arr = explode("/", $date_csv_End);
                        $posted_data["date_csv"]["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
                }

                if (!empty($date_Start)){
                        $posted_data["date"]["start_date_str"] = $date_Start;
                        $start_date_arr = explode("/", $date_Start);
                        $posted_data["date"]["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);
                }

                if (!empty($date_End)){
                        $posted_data["date"]["end_date_str"] = $date_End;
                        $end_date_arr = explode("/", $date_End);
                        $posted_data["date"]["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
                }

		$search_data["find_orders"] = $posted_data;

		$reconciliation_search_condition = "$sql_tbl[reconciliations].date_csv>='".($search_data["find_orders"]["date_csv"]["start_date"])."'";
		$reconciliation_search_condition .= " AND $sql_tbl[reconciliations].date_csv<='".($search_data["find_orders"]["date_csv"]["end_date"])."'";

// ex.5
//		$reconciliation_search_condition .= " AND $sql_tbl[reconciliations].amount_csv<='0'";

#
##
###
		$check_reconciliations_for_mid = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $reconciliation_search_condition AND manufacturerid='0'");


//func_print_r($check_reconciliations_for_mid);
//die("testings");

		if (!empty($check_reconciliations_for_mid)){
			foreach ($check_reconciliations_for_mid as $k_c => $v_c){

	                        $action = "";
        	                if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
 	        	               foreach ($config_reconciliation_search_keyphrases as $k => $v){
					    if (!empty($v_c["description_csv"]) && !empty($v["search_keyphrase"])){
        	        	               if (strpos($v_c["description_csv"], $v["search_keyphrase"]) !== false){
                	        	                $action = "D"; // Drop
							break;
	                                       }
					    }
        	                	}
                	        }


		                $manufacturerid = '0';
        	                foreach ($manufacturerid_info as $k => $v){
				    if (!empty($v_c["description_csv"]) && !empty($v["d_search_keyphrase_for_reconciliation"])){
        		                if (strpos($v_c["description_csv"], $v["d_search_keyphrase_for_reconciliation"]) !== false){
                		                $manufacturerid = $k;
						break;
                                	}
				    }
	                        }

				db_query("UPDATE $sql_tbl[reconciliations] SET manufacturerid='$manufacturerid', action='$action' WHERE id='$v_c[id]'");
			}
		}
###
##
#

		$reconciliation_search_condition .= " AND $sql_tbl[reconciliations].manufacturerid!='0'";

		$reconciliations = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $reconciliation_search_condition");

                $order_search_condition = "$sql_tbl[orders].date>='".($search_data["find_orders"]["date"]["start_date"])."'";
                $order_search_condition .= " AND $sql_tbl[orders].date<='".($search_data["find_orders"]["date"]["end_date"])."'";
//		$order_search_condition .= " AND $sql_tbl[order_groups].reconciliation_id='0'";

		func_flush(".");
		$orders = func_query("SELECT $sql_tbl[order_groups].orderid, $sql_tbl[orders].date, $sql_tbl[order_groups].bd_status, $sql_tbl[order_groups].part_of_total_transaction_in_amount_of, $sql_tbl[order_groups].accounting, $sql_tbl[order_groups].manufacturerid, $sql_tbl[order_groups].total_gross FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $order_search_condition");

//func_print_r($orders);
//die();

		if (!empty($orders)){

                        foreach ($orders as $ko => $vo){

				$empty_accounting = true;

                                if (!empty($vo["accounting"])){
                                        $accounting = unserialize($vo["accounting"]);
					$Cost_to_us = price_format($accounting[1]["gross"]);
					$Shipping = price_format($accounting[2]["gross"]);

                                        if ( (!empty($Cost_to_us) && $Cost_to_us > 0) || (!empty($Shipping) && $Shipping > 0) ){
                                                $orders[$ko]["accounting"]= $accounting;
						$empty_accounting = false;
					}
                                }

				if ($empty_accounting){
					unset($orders[$ko]);
				}
                        }

			$orders = array_values($orders);
		}

		func_flush(".");


		if (!empty($reconciliations) && !empty($orders)){
			func_find_reconciliations_orders($reconciliations, $orders);
		}

	}
	elseif ($mode == "unreconcile"){

                foreach ($action as $k => $v){
			if ($v == "UR"){
	                        db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");

	                        $orderids = func_query("SELECT orderid FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$k'");
				$manufacturerid = func_query_first_cell("SELECT manufacturerid FROM $sql_tbl[reconciliations] WHERE id='$k'");

	                        if (!empty($orderids) && !empty($manufacturerid)){

        	                        foreach ($orderids as $kk => $vv){

						$current_bd_status = func_query_first_cell("SELECT bd_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$manufacturerid'");

						if ($current_bd_status != "X"){
	                                                $current_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_bd_status'");
        	                                        $new_bd_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='X'");
                	                                $log = "(Reconciliation) <B>".$manufacturerid_info[$manufacturerid]["code"].":</B> ";
                        	                        $log .= "B2D status: ".$current_bd_status_name." -> ".$new_bd_status_name."<br />";
                                	                func_log_order($vv["orderid"], 'X', $log, $login);

							db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='0', bd_status='X' WHERE orderid='$vv[orderid]' AND manufacturerid='$manufacturerid'");
						}
                                        }
                                }
			}
                }

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
	}
        elseif ($mode == "undrop"){

                foreach ($action as $k => $v){
                        if ($v == "UD"){
                                db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");
                        }
                }

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
        }
	elseif ($mode == "clear_orders"){

		if (!empty($clear_orders) && is_array($clear_orders)){
			foreach ($clear_orders as $k => $v){
				if (!empty($v) && is_array($v)){
					db_query("UPDATE $sql_tbl[order_groups] SET reconciliation_id='0' WHERE reconciliation_id='$k'");

					foreach ($v as $kk => $vv){
						db_query("DELETE FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$k' AND orderid='$kk'");
					}
				}
			}
		}

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
	}

	func_header_location("reconciliation.php?tab=".$tab);
}

if (empty($tab)) $tab = "unreconciled";

if ($tab == "import"){

        $reconciliation_upload_info = func_query("SELECT * FROM $sql_tbl[reconciliation_upload_info] ORDER BY date");
        if (!empty($reconciliation_upload_info) && is_array($reconciliation_upload_info)){
                foreach ($reconciliation_upload_info as $k => $v){
                        $reconciliation_upload_info[$k]["firstname"] = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$v[login]'");
                }
        }
}

if ($tab == "unreconciled" || $tab == "reconciled" || $tab == "dropped" || $tab == "expense_report" || $tab == "accounts_payable" || $tab == "receivables"){

	if (empty($search_data["reconciliation_tab_".$tab]["date_csv"])){
		$search_data["reconciliation_tab_".$tab]["date_csv"]["end_date"] = time();
		$search_data["reconciliation_tab_".$tab]["date_csv"]["start_date"] = time() - 30*60*60*24;
	}

	$search_condition = "";

	if ($tab == "accounts_payable" || $tab == "receivables") {

                $order_search_condition = "$sql_tbl[orders].date>='".($search_data["reconciliation_tab_".$tab]["date_csv"]["start_date"])."'";
                $order_search_condition .= " AND $sql_tbl[orders].date<='".($search_data["reconciliation_tab_".$tab]["date_csv"]["end_date"])."'";
		$order_search_condition .= " AND $sql_tbl[order_groups].bd_status='X'";

//              $order_search_condition .= " AND $sql_tbl[order_groups].reconciliation_id='0'";

		if ($tab == "receivables"){
			 $order_search_condition .= " AND xcart_order_groups.cb_status IN ('O') AND xcart_order_groups.dc_status IN ('S','G','L','C') AND xcart_order_groups.po_status IN ('PN', 'P1', 'P2')";

			$order_search_fields = "$sql_tbl[orders].po_number, $sql_tbl[orders].firstname, $sql_tbl[orders].details, ";
		}

                $orders = func_query("SELECT $sql_tbl[order_groups].orderid, $order_search_fields $sql_tbl[orders].date, $sql_tbl[orders].order_prefix, $sql_tbl[order_groups].bd_status, $sql_tbl[order_groups].part_of_total_transaction_in_amount_of, $sql_tbl[order_groups].accounting, $sql_tbl[order_groups].manufacturerid, $sql_tbl[order_groups].total_gross FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $order_search_condition");

		if (!empty($orders) && $tab == "receivables"){

			x_load('crypt');
			$total_gross_accounting_1_2 = 0;

			foreach ($orders as $k => $v){

				$accounting = unserialize($v["accounting"]);
				$v["accounting"] = $accounting;

				$details = text_decrypt($v["details"]);
		                $tmp = explode("\n",$details);

		                if ($tmp) {
                		        $po_fields = array("po_number" => "PO Number", "company_name" => "Company name",        "name_of_purchaser" => "Name of purchaser",     "position" => "Position", "po_fax" => "po fax");
		                        $po_details = array();
                		        foreach ($tmp as $line) {
                                		if (empty($po_fields)) {
		                                        break;
                		                }
                                		foreach ($po_fields as $kk => $po_text) {
		                                        if (($a = strpos($line, $po_text)) !== false) {
                		                                $value = substr($line,$a+strlen($po_text)+2);
                                		                $po_details[$kk] = $value;
                                                		unset($po_fields[$kk]);
		                                                break;
                		                        }
                                		}
		                        }
		                }

				$v["details"] = $details;
				$v["po_details"] = $po_details;

				$current_total_gross_accounting_1_2 = $accounting[1]["gross"] + $accounting[2]["gross"];        
				$total_gross_accounting_1_2 += $current_total_gross_accounting_1_2;
				$v["current_total_gross_accounting_1_2"] = $current_total_gross_accounting_1_2;
				$orders[$k] = $v;
			}

			$smarty->assign("orders", $orders);
			$smarty->assign("total_gross_accounting_1_2", $total_gross_accounting_1_2);

//func_print_r($orders, $total_gross_accounting_1_2);

		}

		if (!empty($orders) && $tab == "accounts_payable"){

			$all_manufacturers_in_orders = array();

			foreach($orders as $k => $v){
				$all_manufacturers_in_orders[] = $v["manufacturerid"];
			}

			$all_manufacturers_in_orders_unique = array_unique($all_manufacturers_in_orders);

			foreach ($all_manufacturers_in_orders_unique as $k => $v){
				foreach ($orders as $kk => $vv){
					if ($v == $vv["manufacturerid"]){

						$all_manufacturers_orders[$v]["manufacturerid"] = $v;
						$all_manufacturers_orders[$v]["distr_code"] = $manufacturerid_info[$v]["code"];
						$all_manufacturers_orders[$v]["manufacturer"] = $manufacturerid_info[$v]["manufacturer"];

						$accounting = unserialize($vv["accounting"]);
						$vv["accounting"] = $accounting;

						$total_gross_accounting_1_2 = $all_manufacturers_orders[$v]["total_gross_accounting_1_2"];
						if (empty($total_gross_accounting_1_2))
							$total_gross_accounting_1_2 = 0;
			
						$current_total_gross_accounting_1_2 = $accounting[1]["gross"] + $accounting[2]["gross"];	
						$total_gross_accounting_1_2 += $current_total_gross_accounting_1_2;
						$all_manufacturers_orders[$v]["total_gross_accounting_1_2"] = $total_gross_accounting_1_2;
						$vv["current_total_gross_accounting_1_2"] = $current_total_gross_accounting_1_2;
	
						$all_manufacturers_orders[$v]["orders"][] = $vv;
					}
				}
			}

			$smarty->assign("all_manufacturers_orders", $all_manufacturers_orders);
		}

//func_print_r($all_manufacturers_orders);

	} else {

		$search_condition = "";
		$search_condition .= "$sql_tbl[reconciliations].date_csv>='".($search_data["reconciliation_tab_".$tab]["date_csv"]["start_date"])."'";
		$search_condition .= " AND $sql_tbl[reconciliations].date_csv<='".($search_data["reconciliation_tab_".$tab]["date_csv"]["end_date"])."'";

		if ($tab == "reconciled"){
		        $search_condition .= " AND action='R'";
		}
		elseif ($tab == "dropped"){
	        	$search_condition .= " AND action='D'";
		}
        	elseif ($tab == "unreconciled"){
                	$search_condition .= " AND action=''";
	        }

		$reconciliations = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $search_condition");
	}
}

//func_print_r($reconciliations);

if (!empty($reconciliations) && is_array($reconciliations)){
	foreach ($reconciliations as $k => $v){

                $manufacturerid = 0;
                foreach ($manufacturerid_info as $kk => $vv){
	                if (!empty($v["description_csv"]) && !empty($vv["d_search_keyphrase_for_reconciliation"])){
	      	                if (strpos($v["description_csv"], $vv["d_search_keyphrase_for_reconciliation"]) !== false){
	                                $manufacturerid = $kk;
					$reconciliations[$k]["description_csv"] = str_replace($vv["d_search_keyphrase_for_reconciliation"], "<B>".$vv["d_search_keyphrase_for_reconciliation"]."</B>", $v["description_csv"]);
					break;
				}
			}
		}

		if ($manufacturerid != $v["manufacturerid"]){
			$v["manufacturerid"] = $manufacturerid;
			$reconciliations[$k]["manufacturerid"] = $v["manufacturerid"];
			db_query("UPDATE $sql_tbl[reconciliations] SET manufacturerid='$manufacturerid' WHERE id='$v[id]'");
		}

		if (!empty($manufacturerid)){
			$reconciliations[$k]["d_bulk_or_individual_order_payments"] = $manufacturerid_info[$manufacturerid]["d_bulk_or_individual_order_payments"];
		}

                if (!empty($v["amount_csv"]) && $v["amount_csv"] < 0){
                        $reconciliations[$k]["amount_csv_abs"] = abs($v["amount_csv"]);
                }

		if ($tab == "unreconciled" || $tab == "reconciled"){
		    if (!empty($manufacturerid)){

			$reconciliations[$k]["distr_code"] = $manufacturerid_info[$manufacturerid]["code"];

			$orderids = func_query("SELECT orderid, orders_variant, ref_to_us FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$v[id]' ORDER BY orders_variant, orderid");
			if (!empty($orderids)){

				$found_orders = array();
				foreach ($orderids as $kk => $vv){
					$order_query_first = func_query_first("SELECT $sql_tbl[orders].orderid, $sql_tbl[orders].order_prefix, $sql_tbl[orders].date, $sql_tbl[order_groups].total_gross, $sql_tbl[order_groups].accounting, $sql_tbl[order_groups].bd_status, $sql_tbl[order_groups].ru_status FROM $sql_tbl[orders] LEFT JOIN $sql_tbl[order_groups] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $sql_tbl[order_groups].manufacturerid='$manufacturerid' AND $sql_tbl[orders].orderid='$vv[orderid]'");

					if (!empty($order_query_first)){

						$order_query_first["orders_variant"] = $vv["orders_variant"];
						$order_query_first["ref_to_us"] = $vv["ref_to_us"];

		                                if (!empty($order_query_first["accounting"])){
                       				        $accounting = unserialize($order_query_first["accounting"]);

			                                if (!empty($accounting))
                               				        $order_query_first["accounting"]= $accounting;
						}

						$found_orders[$kk] = $order_query_first;
					}
				}
	
				$reconciliations[$k]["orders"] = $found_orders;

				if (!empty($found_orders)){

					$total_order_amounts = 0;

					foreach ($found_orders as $ko => $vo){
						$total_order_amounts += $vo["accounting"]["1"]["gross"] + $vo["accounting"]["2"]["gross"];
					}

					$reconciliations[$k]["total_order_amounts"] = $total_order_amounts;
				}

				unset($found_orders);
			}
		    }
		}


//                if ($tab == "unreconciled" || $tab == "expense_report"){
                        if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
                                foreach ($config_reconciliation_search_keyphrases as $kk => $vv){
                                    if (!empty($v["description_csv"]) && !empty($vv["search_keyphrase"])){
                                        if (strpos($v["description_csv"], $vv["search_keyphrase"]) !== false){
//                                              $action = "D"; // Drop
                                                $reconciliations[$k]["description_csv"] = str_replace($vv["search_keyphrase"], "<B>".$vv["search_keyphrase"]."</B>", $v["description_csv"]);
                                                $reconciliations[$k]["config_search_keyphrase_found"] = "Y";

						if ($tab == "expense_report"){
	                                                $config_reconciliation_search_keyphrases[$kk]["total_amount"] += $reconciliations[$k]["amount_csv"];

							if (!empty($reconciliations[$k]["amount_csv_abs"])){
	        	                                        $config_reconciliation_search_keyphrases[$kk]["total_amount_with_abs"] += $reconciliations[$k]["amount_csv_abs"];
							}

                	                                $config_reconciliation_search_keyphrases[$kk]["found_records"][] = $reconciliations[$k];
						}
/*
                                                if ($action != $v["action"]){
                                                        db_query("UPDATE $sql_tbl[reconciliations] SET action='D' WHERE id='$v[id]'");
                                                        $v["action"] = $action;
                                                        $reconciliations[$k]["action"] = $v["action"];
                                                }
*/

                                                break;
                                        }
                                    }
                                }
                        }
//                }

	}
}

//func_print_r($reconciliations);

//func_print_r($config_reconciliation_search_keyphrases);

$location[] = array("Reconciliation", "");

$smarty->assign("tab", $tab);
$smarty->assign("config_reconciliation_search_keyphrases", $config_reconciliation_search_keyphrases);
$smarty->assign("search_prefilled1", $search_data["find_orders"]);

$smarty->assign("search_prefilled", $search_data["reconciliation_tab_".$tab]);

$smarty->assign("reconciliations", $reconciliations);
$smarty->assign("reconciliation_upload_info", $reconciliation_upload_info);
$smarty->assign("main", "reconciliation");
$smarty->assign("location", $location);
$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
