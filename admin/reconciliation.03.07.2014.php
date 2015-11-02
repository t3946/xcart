<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files');

set_time_limit(0);
ini_set('memory_limit', '512M');

x_session_register("search_data");

#####################################
//if ($login != "michael2")
//die("Sorry. Michael tests.");
#####################################

$SS_current_time = time();

function func_SS($i, $wz, $wx, $yes, $s, $sum, $Tsum, $xW, $r_id, $sorted_orders){
	global $sql_tbl, $SS_current_time;


	if ((time() - $SS_current_time) > 0.05){
		func_flush(".");
		$SS_current_time = time();
	}

/*
        if ($i % 10 == 0) {
	        func_flush(".");
                if($i % 500 == 0) {
         	       func_flush("<br />\n");
                }
	        func_flush();
        }
*/

	if ($yes) {
          /* i+1 - это индекс заказа (в маcсиве xW), который берем в вариант решения*/
          /* строку s содержащую вариант решения можно заполнять только индексами заказов через запятую, тогда когда найдем решение (очередной вариант), индексы можно распарсить и сохранить где то во внешнем массиве решений */
//		$s = $s . ' ' . $wx . '[' .($i+1). '] ';
		$s = $s . (!empty($s)?',':'') . ($i+1);
		$sum = $sum + $wx;
	}

	if ($i == 0) {
		$res = 0;
	} else {
		if (($wz - $xW[$i]) < 0) {
			$res = func_SS($i-1, $wz, 0, false, $s, $sum, $Tsum, $xW, $r_id, $sorted_orders);
		}
		else {
			$res = max(func_SS($i-1, $wz, 0, false, $s, $sum, $Tsum, $xW, $r_id, $sorted_orders), $xW[$i]+func_SS($i-1, $wz-$xW[$i], $xW[$i], true, $s, $sum, $Tsum, $xW, $r_id, $sorted_orders));
		}
	}

	if ($sum == $Tsum*100 && !empty($s) && !empty($r_id)) {
            /*получили очередной вариант выводим на экран*/
            /* здесь можно строку s распарсить и сохранить во внешнем массиве */
//func_print_r($sorted_orders);
		print("r_id :" . $r_id . ",  keys : " . $s . '. Sum: ' . $sum . "<br />");
		func_flush(".");

//die();

		$s_arr = explode(",",$s);

		$allow_to_insert = true;

		$order_ids = array();
		foreach ($s_arr as $k => $v){
			$order_ids[] = $sorted_orders[$v]['orderid'];
		}
		$count_order_ids = count($order_ids);
//		asort($order_ids);

		$distinct_orders_variant = func_query("SELECT DISTINCT orders_variant FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id'");

		if (!empty($distinct_orders_variant)){
			foreach($distinct_orders_variant as $kd => $vd){
				$orders_variant = $vd["orders_variant"];
				$count_orders_in_db = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id' AND orders_variant='$orders_variant' AND orderid IN ('" . implode("','", $order_ids) . "')");

				if ($count_orders_in_db == $count_order_ids){
					$allow_to_insert = false;
					break;
				}
			}
		}


//func_print_r($order_ids);
//die();


		if ($allow_to_insert){
			$time_to_db = time();
			$max_orders_variant = func_query_first_cell("SELECT MAX(orders_variant) FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id'");
			$max_orders_variant = $max_orders_variant + 1;
			$bd_status_invoiced = 0;

			foreach ($s_arr as $k => $v){
				$orderid = $sorted_orders[$v]['orderid'];
				$bd_status = $sorted_orders[$v]['bd_status'];

				if ($bd_status == "X"){
					$bd_status_invoiced++;
				}

  		        	if (!empty($orderid))
					db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db, orders_variant) VALUES ('$r_id', '$orderid', '$time_to_db', '$max_orders_variant')");
			}

			$action='';
			if (count($s_arr) == $bd_status_invoiced && $bd_status_invoiced > 0){
				$action='R';
			}
			db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");
		}
//die("1");

	}

	return $res;
}  

function func_find_reconciliations_orders($reconciliations_to_check) {
	global $sql_tbl, $manufacturerid_info, $login;	

	$found_orders_for_several_orders_at_once = array();
	$found_orders_for_several_orders_at_once_total_amount = 0;

	$found_orders_for_charges_each_order_separately = array();
	$found_orders_for_charges_each_order_separately_ref_to_us = array();

	$found_orders_for_each_order_separately_p = array();
	$found_orders_for_each_order_separately_s = array();
	$found_orders_for_each_order_separately_ref_to_us = array();


        foreach ($reconciliations_to_check as $k => $v){

		$r_id = $v["id"];

		if ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_may_charge_for_several_orders_at_once"){

/*	
	        	$N = count($v["orders_to_check"]);

		        $sorted_orders[0] = 0;
        		$xW[0] = 0;

		        for ($i=1; $i<=$N; $i++){
        	        	$sorted_orders[$i] = $v["orders_to_check"][$i-1];
//        		        $xW[$i] = $sorted_orders[$i]["total"] * 100;
//        		        $price_to_search = $sorted_orders[$i]["total_gross"] * 100;

//				if (empty($sorted_orders[$i]["accounting"][1]["gross"]) && empty($sorted_orders[$i]["accounting"][2]["gross"]))
//					continue;

        		        $price_to_search = ($sorted_orders[$i]["accounting"][1]["gross"] + $sorted_orders[$i]["accounting"][2]["gross"]) * 100;

        		        $xW[$i] = $price_to_search;
		        }

//			$r_id = $v["id"];
	                $Tsum = abs($v["amount_csv"]);

	                $T = $Tsum * 100;

			db_query("DELETE FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id'");

                	func_SS($N, $Tsum*100, 0, false, '', 0, $Tsum, $xW, $r_id, $sorted_orders);
			unset($sorted_orders);
			unset($xW);
*/


//func_print_r($v);
//die();


                        $amount_csv = $v["amount_csv"];
			$amount_csv_abs = abs($amount_csv);

                        if (!empty($v["orders_to_check"]) && is_array($v["orders_to_check"])){
                                foreach($v["orders_to_check"] as $kk => $vv){
                                        $time_to_db = time();

                                        $price_to_search = $vv["accounting"][1]["gross"] + $vv["accounting"][2]["gross"];

                                        if (
						$amount_csv < 0 && $amount_csv_abs == $vv["part_of_total_transaction_in_amount_of"] 
						&& $price_to_search <= abs($amount_csv) 
						&& ($found_orders_for_several_orders_at_once_total_amount + $price_to_search) <= abs($amount_csv)
						&& !in_array($vv["orderid"], $found_orders_for_several_orders_at_once)
					){

						$found_orders_for_several_orders_at_once_total_amount += $price_to_search;
                                                $found_orders_for_several_orders_at_once[] = $vv["orderid"];

                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");

                                                $action='';
                                                if ($vv['bd_status'] == "X"){
                                                        $action='R';
                                                }
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='$action', total_order_amounts='$found_orders_for_several_orders_at_once_total_amount' WHERE id='$r_id'");
                                        }

                                }
                        }

		} 
		elseif ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"){


//func_print_r($v);
//die();


                        $amount_csv = $v["amount_csv"];
                        if (!empty($v["orders_to_check"]) && is_array($v["orders_to_check"])){
                                foreach($v["orders_to_check"] as $kk => $vv){
                                        $time_to_db = time();

                                        $price_to_search_p = $vv["accounting"][1]["gross"];
                                        if ($amount_csv < 0 && $price_to_search_p == abs($amount_csv) && !in_array($vv["orderid"], $found_orders_for_each_order_separately_p)){
                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
                                                $found_orders_for_each_order_separately_p[] = $vv["orderid"];

                                                $action='';
                                                if ($vv['bd_status'] == "X"){
                                                        $action='R';
                                                }
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");

                                        }

                                        $price_to_search_s = $vv["accounting"][2]["gross"];
                                        if ($amount_csv < 0 && $price_to_search_s == abs($amount_csv) && !in_array($vv["orderid"], $found_orders_for_each_order_separately_s)){
                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
                                                $found_orders_for_each_order_separately_s[] = $vv["orderid"];

                                                $action='';
                                                if ($vv['bd_status'] == "X"){
                                                        $action='R';
                                                }
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");
                                        }

                                        $price_to_search = $vv["accounting"][4]["gross"];
                                        if ($amount_csv > 0 && $price_to_search == $amount_csv && !in_array($vv["orderid"], $found_orders_for_each_order_separately_ref_to_us)){
                                                db_query("UPDATE $sql_tbl[reconciliations] SET action='R' WHERE id='$r_id'");
                                                db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db, ref_to_us) VALUES ('$r_id', '$vv[orderid]', '$time_to_db', 'Y')");
                                                $found_orders_for_each_order_separately_ref_to_us[] = $vv["orderid"];

###

                                                $current_ru_status = func_query_first_cell("SELECT ru_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                                if ($current_ru_status != "RR"){

                                                        $current_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_ru_status'");
                                                        $new_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='RR'");

                                                        $log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B><br />";
                                                        $log .= "REF TO US status: ".$current_ru_status_name." -> ".$new_ru_status_name."<br />";
                                                        func_log_order($vv["orderid"], 'X', $log, $login);

                                                        db_query("UPDATE $sql_tbl[order_groups] SET ru_status='RR' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
                                                }
###
                                        }

                                }
                        }


		}
		elseif ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_separately"){

//			db_query("DELETE FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$r_id'");

			$amount_csv = $v["amount_csv"];
			if (!empty($v["orders_to_check"]) && is_array($v["orders_to_check"])){
				foreach($v["orders_to_check"] as $kk => $vv){
					$time_to_db = time();

					$price_to_search = $vv["accounting"][1]["gross"] + $vv["accounting"][2]["gross"];
					if ($amount_csv < 0 && $price_to_search == abs($amount_csv) && !in_array($vv["orderid"], $found_orders_for_charges_each_order_separately)){
						db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db) VALUES ('$r_id', '$vv[orderid]', '$time_to_db')");
						$found_orders_for_charges_each_order_separately[] = $vv["orderid"];

						$action='';
						if ($vv['bd_status'] == "X"){
							$action='R';
						}
						db_query("UPDATE $sql_tbl[reconciliations] SET action='$action' WHERE id='$r_id'");
					}

					$price_to_search = $vv["accounting"][4]["gross"];
					if ($amount_csv > 0 && $price_to_search == $amount_csv && !in_array($vv["orderid"], $found_orders_for_charges_each_order_separately_ref_to_us)){
						db_query("UPDATE $sql_tbl[reconciliations] SET action='R' WHERE id='$r_id'");
						db_query("INSERT INTO $sql_tbl[reconciliation_orderid] (reconciliation_id, orderid, time_to_db, ref_to_us) VALUES ('$r_id', '$vv[orderid]', '$time_to_db', 'Y')");
						$found_orders_for_charges_each_order_separately_ref_to_us[] = $vv["orderid"];

###

						$current_ru_status = func_query_first_cell("SELECT ru_status FROM $sql_tbl[order_groups] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
						if ($current_ru_status != "RR"){

		                                        $current_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_ru_status'");
                		                        $new_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='RR'");

							$log = "(Reconciliation) <B>".$manufacturerid_info[$v["manufacturerid"]]["code"].":</B><br />";
                                		        $log .= "REF TO US status: ".$current_ru_status_name." -> ".$new_ru_status_name."<br />";
							func_log_order($vv["orderid"], 'X', $log, $login);

							db_query("UPDATE $sql_tbl[order_groups] SET ru_status='RR' WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]'");
						}
###
					}
				}
			}
//func_print_r($reconciliations_to_check);
//die("AAA");
		}
        }
}


$manufacturerid_info = func_query_hash("SELECT code, manufacturerid, d_bulk_or_individual_order_payments, d_search_keyphrase_for_reconciliation FROM $sql_tbl[manufacturers]", 'manufacturerid', false);

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

/*
                if (!empty($date_csv_StartMonth)) {
                        $posted_data["date_csv"]["start_date"] = mktime(0,0,0,$date_csv_StartMonth,$date_csv_StartDay,$date_csv_StartYear);
                        $posted_data["date_csv"]["end_date"] = mktime(23,59,59,$date_csv_EndMonth,$date_csv_EndDay,$date_csv_EndYear);
                }
*/

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
		}

		$top_message["content"] = "Done.";
		$top_message["type"] = "I";
	}
	elseif ($mode == "import" && $_FILES["userfile"]["type"] == "text/csv"){

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

				while (($buffer = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {

					$description_csv = addslashes(trim($buffer[4]));
					$amount_csv = trim($buffer[1]);

					$date_csv_tmp = trim($buffer[0]);
					$date_csv_arr = explode("/", $date_csv_tmp);
					$date_csv = mktime(0, 0, 0, $date_csv_arr[0], $date_csv_arr[1], $date_csv_arr[2]);
					
					if (empty($min_date_in_file)){
						$min_date_in_file = $date_csv;
					} elseif ($date_csv < $min_date_in_file) {
						$min_date_in_file = $date_csv;
					}

					if ($date_csv > $max_date_in_file){
						$max_date_in_file = $date_csv;
					}

                                        $amount_csv_total += $amount_csv;
                                        $line_number++;

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

						db_query("INSERT INTO $sql_tbl[reconciliations] (description_csv, date_csv, amount_csv, file_upload_date, action, manufacturerid) VALUES ('$description_csv', '$date_csv', '$amount_csv', '$cur_time', '$action', '$manufacturerid')");
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

/*
                $posted_data["date_csv"]["start_date"] = mktime(0,0,0,$date_csv_StartMonth,$date_csv_StartDay,$date_csv_StartYear);
                $posted_data["date_csv"]["end_date"] = mktime(23,59,59,$date_csv_EndMonth,$date_csv_EndDay,$date_csv_EndYear);

                $posted_data["date"]["start_date"] = mktime(0,0,0,$date_StartMonth,$date_StartDay,$date_StartYear);
                $posted_data["date"]["end_date"] = mktime(23,59,59,$date_EndMonth,$date_EndDay,$date_EndYear);
*/

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

//func_print_r($orders);
//func_print_r($reconciliations);
//die();


		if (!empty($reconciliations) && !empty($orders)){

			$reconciliations_to_check = array();
			$index_r = 0;

			foreach ($reconciliations as $kr => $vr){

				db_query("DELETE FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$vr[id]'");

				$orders_to_check = array();

				foreach ($orders as $ko => $vo){
					if ($vr["manufacturerid"] == $vo["manufacturerid"] && $vo["date"] < $vr["date_csv"]){
						$orders_to_check[] = $vo;
					}
				}

				if (!empty($orders_to_check)){
					$vr["orders_to_check"] = $orders_to_check;
					$reconciliations_to_check[$index_r] = $vr;
					$index_r++;
				}

				unset($orders_to_check);
			}

//func_print_r($reconciliations_to_check);
//die();

			func_find_reconciliations_orders($reconciliations_to_check);
		}

//func_print_r($orders);
//func_print_r($orders_to_check);

//func_print_r($reconciliations_to_check);
//die();
//func_print_r($reconciliations, $orders);
//func_print_r($_POST);



//die("Find");


	}
	elseif ($mode == "unreconcile"){

                foreach ($action as $k => $v){
			if ($v == "UR"){
	                        db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");
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

	func_header_location("reconciliation.php?tab=".$tab);
}

if (empty($tab)) $tab = "unreconciled";

if ($tab == "unreconciled"){

        $reconciliation_upload_info = func_query("SELECT * FROM $sql_tbl[reconciliation_upload_info] ORDER BY date");
        if (!empty($reconciliation_upload_info) && is_array($reconciliation_upload_info)){
                foreach ($reconciliation_upload_info as $k => $v){
                        $reconciliation_upload_info[$k]["firstname"] = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$v[login]'");
                }
        }
}

if ($tab == "unreconciled" || $tab == "reconciled" || $tab == "dropped" || $tab == "expense_report"){

	if (empty($search_data["reconciliation_tab_".$tab]["date_csv"])){
		$search_data["reconciliation_tab_".$tab]["date_csv"]["end_date"] = time();
		$search_data["reconciliation_tab_".$tab]["date_csv"]["start_date"] = time() - 30*60*60*24;
	}

	$search_condition = "";
	$search_condition .= "$sql_tbl[reconciliations].date_csv>='".($search_data["reconciliation_tab_".$tab]["date_csv"]["start_date"])."'";
	$search_condition .= " AND $sql_tbl[reconciliations].date_csv<='".($search_data["reconciliation_tab_".$tab]["date_csv"]["end_date"])."'";

	if ($tab == "reconciled"){
	        $search_condition .= " AND action='R'";
	}
	elseif ($tab == "dropped"){
	        $search_condition .= " AND action='D'";
	}

	$reconciliations = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $search_condition");
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


		if ($tab == "unreconciled"){
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
				unset($found_orders);
			}
		    }
		}


                if (!empty($v["amount_csv"]) && $v["amount_csv"] < 0){
                        $reconciliations[$k]["amount_csv_abs"] = abs($v["amount_csv"]);
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
