<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files', 'order');

set_time_limit(0);
ini_set('memory_limit', '512M');

x_session_register("search_data");

$all_tabs = array("unreconciled", "reconciled", "dropped", "expense_report", "import", "calculation", "accounts_payable", "receivables", "rules");


function func_find_reconciliations_orders($reconciliations_to_check, $orders_to_check) {
	global $sql_tbl, $manufacturerid_info, $login;	

	$found_invoices_and_memos = array();
	
        foreach ($reconciliations_to_check as $k => $v){

		func_flush(".");
		func_flush("<br />\n");

		$r_id = $v["id"];

/*
//if (!($r_id == "15304" || $r_id == "15302" || $r_id == "15352")){
if ($r_id != "15352"){
	continue;  //  <--------------------------------
}
*/

		if ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_may_charge_for_several_orders_at_once"){

//                        db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='0' WHERE reconciliation_id='$r_id' AND status='U'");
//                        db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='0' WHERE reconciliation_id='$r_id' AND status='U'");


                        $amount_csv = price_format($v["amount_csv"]);
			$amount_csv_abs = abs($amount_csv);
			$amount_csv_abs = price_format($amount_csv_abs);

                        if (!empty($orders_to_check) && is_array($orders_to_check)){

//				$SUM_invoice_total_OF_found_invoices = func_query_first_cell("SELECT SUM(invoice_total) FROM $sql_tbl[order_group_invoices] WHERE reconciliation_id='$r_id' AND status='U'");
//				if ($SUM_invoice_total_OF_found_invoices == ""){
//					$SUM_invoice_total_OF_found_invoices = 0;
//				}

				$SUM_invoice_total_OF_found_invoices = 0;
				$SUM_ref_to_us_total_OF_found_memos = 0;

                                foreach($orders_to_check as $kk => $vv){

	                            if ($kk % 10 == 0) {
                                        func_flush(".");
                                        if($kk % 500 == 0) {
         	                               func_flush("<br />\n");
                                        }
                                        func_flush();
                                    }

				    if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"]){

				     if (!empty($vv["order_group_invoices"]) && is_array($vv["order_group_invoices"])){
				       foreach ($vv["order_group_invoices"] as $invoice_number => $invoice_info){

					 $price_to_search = $invoice_info["invoice_total"];
                                         $sum_total = $SUM_invoice_total_OF_found_invoices + $price_to_search;
                                         $sum_total = price_format($sum_total);

					 $count_reconciled_invoices_for_current_manufacturerid_with_such_reconciliation_id = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_group_invoices] WHERE reconciliation_id='$r_id' AND orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND invoice_number='$invoice_number'");

                                         if (
					      (
						$amount_csv < 0 && $amount_csv_abs == $invoice_info["part_of_total_transaction_in_amount_of"]
						&& $price_to_search <= $amount_csv_abs 
						&& $sum_total <= $amount_csv_abs
						&& !in_array($vv["orderid"], $found_invoices_and_memos)
						&& empty($invoice_info["reconciliation_id"])
						&& empty($count_reconciled_invoices_for_current_manufacturerid_with_such_reconciliation_id)
					      )
					      ||
					      ( // https://basecamp.com/2070980/projects/1577907/messages/44550708
                                                $amount_csv < 0 && $invoice_info["part_of_total_transaction_in_amount_of"] == "0.00" 
                                                && $price_to_search == $amount_csv_abs
                                                && $sum_total == $amount_csv_abs
                                                && !in_array($vv["orderid"], $found_invoices_and_memos)
                                                && empty($invoice_info["reconciliation_id"])
                                                && empty($count_reconciled_invoices_for_current_manufacturerid_with_such_reconciliation_id)
					      )
					 ){
						$SUM_invoice_total_OF_found_invoices += $price_to_search;
                                                $found_invoices_and_memos[] = $vv["orderid"]."_I_".$invoice_number;

                                                db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND invoice_number='$invoice_number'");
                                         }
				       } //foreach ($vv["order_group_invoices"] as $invoice_number => $invoice_info)
				     } //if (!empty($vv["order_group_invoices"]) && is_array($vv["order_group_invoices"]))
###
###
				     if (!empty($vv["order_group_memos"]) && is_array($vv["order_group_memos"])){
				       foreach ($vv["order_group_memos"] as $memo_number => $memo_info){

                                         $price_to_search = $memo_info["ref_to_us_total"];
                                         $sum_total = $SUM_ref_to_us_total_OF_found_memos + $price_to_search;
                                         $sum_total = price_format($sum_total);

                                         $count_reconciled_memos_for_current_manufacturerid_with_such_reconciliation_id = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_group_memos] WHERE reconciliation_id='$r_id' AND orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND memo_number='$memo_number'");

                                         if (
                                                $amount_csv < 0 && $amount_csv_abs == $memo_info["ref_to_us_part_of_transaction"]
                                                && $price_to_search <= $amount_csv_abs
                                                && !in_array($vv["orderid"], $found_invoices_and_memos)
                                                && empty($memo_info["reconciliation_id"])
                                                && empty($count_reconciled_memos_for_current_manufacturerid_with_such_reconciliation_id)
                                         ){
                                                $SUM_ref_to_us_total_OF_found_memos += $price_to_search;
                                                $found_invoices_and_memos[] = $vv["orderid"]."_R_".$invoice_number;

						db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND memo_number='$memo_number'");
                                         }
 				       } // foreach ($vv["order_group_memos"] as $memo_number => $memo_info)
 				     } // if (!empty($vv["order_group_memos"]) && is_array($vv["order_group_memos"]))
				   } //if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"])
                                } //foreach($orders_to_check as $kk => $vv)
                        } //if (!empty($orders_to_check) && is_array($orders_to_check))
		} 
		elseif ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"){

//                        db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='0' WHERE reconciliation_id='$r_id' AND status='U'");
//                        db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='0' WHERE reconciliation_id='$r_id' AND status='U'");


                        $amount_csv = price_format($v["amount_csv"]);
			$amount_csv_abs = abs($amount_csv);
			$amount_csv_abs = price_format($amount_csv_abs);

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

					if (!empty($vv["order_group_invoices"]) && is_array($vv["order_group_invoices"])){

					  $SUM_invoice_total = 0;
                                          foreach ($vv["order_group_invoices"] as $invoice_number => $invoice_info){
						$SUM_invoice_total += $invoice_info["invoice_total"];
					  }

					  if ($SUM_invoice_total > 0){
					    $price_to_search = price_format($SUM_invoice_total);
					    if ($amount_csv < 0 && $price_to_search == $amount_csv_abs && !in_array($vv["orderid"], $found_invoices_and_memos)){
					      foreach ($vv["order_group_invoices"] as $invoice_number => $invoice_info){
						$found_invoices_and_memos[] = $vv["orderid"]."_I_".$invoice_number;
						db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND invoice_number='$invoice_number'");
					      }
					    }
					  }
					} // if (!empty($vv["order_group_invoices"]) && is_array($vv["order_group_invoices"]))


                                        if (!empty($vv["order_group_memos"]) && is_array($vv["order_group_memos"])){

					  $SUM_ref_to_us_total = 0;
                                          foreach ($vv["order_group_memos"] as $memo_number => $memo_info){
						$SUM_ref_to_us_total += $memo_info["ref_to_us_total"];
					  }

					  if ($SUM_ref_to_us_total > 0){
					    $price_to_search = price_format($SUM_ref_to_us_total);
					    if ($amount_csv > 0 && $price_to_search == $amount_csv && !in_array($vv["orderid"], $found_invoices_and_memos)){
					      foreach ($vv["order_group_memos"] as $memo_number => $memo_info){
						$found_invoices_and_memos[] = $vv["orderid"]."_R_".$memo_number;
						db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND memo_number='$memo_number'");
					      }
					    }
					  }
					} // (!empty($vv["order_group_memos"]) && is_array($vv["order_group_memos"]))
				    }
                                }
                        }
		}
		elseif ($manufacturerid_info[$v["manufacturerid"]]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_separately"){

//			db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='0' WHERE reconciliation_id='$r_id' AND status='U'");
//			db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='0' WHERE reconciliation_id='$r_id' AND status='U'");

			$amount_csv = price_format($v["amount_csv"]);
                        $amount_csv_abs = abs($amount_csv);
                        $amount_csv_abs = price_format($amount_csv_abs);

			if (!empty($orders_to_check) && is_array($orders_to_check)){

				foreach($orders_to_check as $kk => $vv){

                                    if ($kk % 10 == 0) {
                                        func_flush(".");
                                        if($kk % 500 == 0) {
                                               func_flush("<br />\n");
                                        }

                                        func_flush();
                                    }


				    $is_reconciliation_id_in_invoices = func_query_first_cell("SELECT reconciliation_id FROM $sql_tbl[order_group_invoices] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]' AND reconciliation_id>0");
				    $is_reconciliation_id_in_memos = func_query_first_cell("SELECT reconciliation_id FROM $sql_tbl[order_group_memos] WHERE orderid='$vv[orderid]' AND manufacturerid='$v[manufacturerid]' AND reconciliation_id>0");


				    if (!empty($is_reconciliation_id_in_invoices) || !empty($is_reconciliation_id_in_memos)){
					continue;
				    }

				    if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"]){

					if (!empty($vv["order_group_invoices"]) && is_array($vv["order_group_invoices"])){
						foreach ($vv["order_group_invoices"] as $invoice_number => $invoice){

							$price_to_search = $invoice["invoice_total"];
							$price_to_search = price_format($price_to_search);

							if ($amount_csv < 0 && $price_to_search == $amount_csv_abs && !in_array($vv["orderid"], $found_invoices_and_memos)){
								$is_such_invoice_in_db = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_group_invoices] WHERE reconciliation_id='$r_id'");

								if (empty($is_such_invoice_in_db)){
									db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND invoice_number='$invoice_number'");
									$found_invoices_and_memos[] = $vv["orderid"]."_I_".$invoice_number;
								}
							}
						}
					}


					if (!empty($vv["order_group_memos"]) && is_array($vv["order_group_memos"])){
						foreach ($vv["order_group_memos"] as $memo_number => $memo){

							$price_to_search = $memo["ref_to_us_total"];
							$price_to_search = price_format($price_to_search);

							if ($amount_csv > 0 && $price_to_search == $amount_csv && !in_array($vv["orderid"], $found_invoices_and_memos)){
								$is_such_memo_in_db = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[order_group_memos] WHERE reconciliation_id='$r_id'");

								if (empty($is_such_memo_in_db)){
									db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='$r_id' WHERE orderid='$vv[orderid]' AND manufacturerid='$vv[manufacturerid]' AND memo_number='$memo_number'");
									$found_invoices_and_memos[] = $vv["orderid"]."_R_".$memo_number;
								}
							}
						}
					}

				    } // if ($v["manufacturerid"] == $vv["manufacturerid"] && $vv["date"] < $v["date_csv"])
				} // foreach($orders_to_check as $kk => $vv)
			} // if (!empty($orders_to_check) && is_array($orders_to_check))

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
//func_print_r($config_reconciliation_search_keyphrases);
//die();

if ($tab == "rules"){

	$search_keyphrase_list = array();
	$tmp_counter = 0;

	foreach ($manufacturerid_info as $k => $v){
		if (!empty($v["d_search_keyphrase_for_reconciliation"])){

			$d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $v["d_search_keyphrase_for_reconciliation"]);
			foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r){
				$vv_s_r = trim($vv_s_r);

				$search_keyphrase_list[$tmp_counter]["search_keyphrase"] = $vv_s_r;
				$search_keyphrase_list[$tmp_counter]["manufacturerid"] = $k;
				$search_keyphrase_list[$tmp_counter]["manufacturer"] = $v["manufacturer"];
				$tmp_counter++;
			}
		}
	}

	if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
		foreach ($config_reconciliation_search_keyphrases as $k => $v){
			if (!empty($v["search_keyphrase"])){
				$v_search_keyphrase_UPPER_arr = explode("<OR>", $v["search_keyphrase"]);
				foreach ($v_search_keyphrase_UPPER_arr as $v_search_keyphrase_UPPER){
					$v_search_keyphrase_UPPER = trim($v_search_keyphrase_UPPER);
					$search_keyphrase_list[$tmp_counter]["search_keyphrase"] = $v_search_keyphrase_UPPER;
					$search_keyphrase_list[$tmp_counter]["id"] = $v["id"];
					$search_keyphrase_list[$tmp_counter]["code"] = $v["code"];
					$tmp_counter++;
				}
			}
		}
	}

	if (!empty($search_keyphrase_list)){

		$search_keyphrase_list = my_array_sort($search_keyphrase_list, "search_keyphrase");
		$search_keyphrase_list = array_values($search_keyphrase_list);
	}

//	func_print_r($search_keyphrase_list);
}

if ($REQUEST_METHOD == "POST") {


#
##
###

//func_print_r($_POST);
//die();

	if (!empty($data_orders_selectbox)){

                foreach ($all_tabs as $t){
                        $search_data["reconciliation_tab_".$t]["data_orders_selectbox"] = $data_orders_selectbox;
                }

		if (!empty($date_csv_End)){
			$date_End = $date_csv_End;
			$end_date_arr = explode("/", $date_csv_End);
			$end_date = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);

			$date_Start_time = $end_date - $data_orders_selectbox*31*60*60*24;
			$date_Start = date("m/d/Y", $date_Start_time);
		}
	}


	if (!empty($date_csv_Start)){
		$posted_data["date_csv"]["start_date_str"] = $date_csv_Start;
		$start_date_arr = explode("/", $date_csv_Start);
		$posted_data["date_csv"]["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);

		foreach ($all_tabs as $t){
			$search_data["reconciliation_tab_".$t]["date_csv"]["start_date"] = $posted_data["date_csv"]["start_date"];
			$search_data["reconciliation_tab_".$t]["date_csv"]["start_date_str"] = $posted_data["date_csv"]["start_date_str"];
		}
	}

	if (!empty($date_csv_End)){
		$posted_data["date_csv"]["end_date_str"] = $date_csv_End;
		$end_date_arr = explode("/", $date_csv_End);
		$posted_data["date_csv"]["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);

		foreach ($all_tabs as $t){
			$search_data["reconciliation_tab_".$t]["date_csv"]["end_date"] = $posted_data["date_csv"]["end_date"];
			$search_data["reconciliation_tab_".$t]["date_csv"]["end_date_str"] = $posted_data["date_csv"]["end_date_str"];
		}
	}

        if (!empty($date_Start)){
                $posted_data["date"]["start_date_str"] = $date_Start;
                $start_date_arr = explode("/", $date_Start);
                $posted_data["date"]["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);

                foreach ($all_tabs as $t){
                        $search_data["reconciliation_tab_".$t]["date"]["start_date"] = $posted_data["date"]["start_date"];
                        $search_data["reconciliation_tab_".$t]["date"]["start_date_str"] = $posted_data["date"]["start_date_str"];
                }       
        }

        if (!empty($date_End)){
                $posted_data["date"]["end_date_str"] = $date_End;
                $end_date_arr = explode("/", $date_End);
                $posted_data["date"]["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);

                foreach ($all_tabs as $t){
                        $search_data["reconciliation_tab_".$t]["date"]["end_date"] = $posted_data["date"]["end_date"];
                        $search_data["reconciliation_tab_".$t]["date"]["end_date_str"] = $posted_data["date"]["end_date_str"];
                }
        }

	if ($tab == "unreconciled" || $tab == "reconciled" || $tab == "calculation"){
		 $search_data["reconciliation_tab_unreconciled"]["manufacturers"] = $posted_data["manufacturers"];
		 $search_data["reconciliation_tab_reconciled"]["manufacturers"] = $posted_data["manufacturers"];
		 $search_data["reconciliation_tab_calculation"]["manufacturers"] = $posted_data["manufacturers"];

                 $search_data["reconciliation_tab_unreconciled"]["select_distributors"] = $posted_data["select_distributors"];
                 $search_data["reconciliation_tab_reconciled"]["select_distributors"] = $posted_data["select_distributors"];
                 $search_data["reconciliation_tab_calculation"]["select_distributors"] = $posted_data["select_distributors"];
	}

        if ($tab == "unreconciled"){

		if (!isset($posted_data["show_unreconciled_invoices_and_memos"]) || empty($posted_data["show_unreconciled_invoices_and_memos"])){
			$posted_data["show_unreconciled_invoices_and_memos"] = "N";
		}
                $search_data["reconciliation_tab_unreconciled"]["show_unreconciled_invoices_and_memos"] = $posted_data["show_unreconciled_invoices_and_memos"];
	}

	x_session_save("search_data");
###
##
#


//func_print_r($_POST, $search_data);
//die();


        if ($mode == "search") {

/*
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
*/
        }
	elseif ($mode == "import" && $_FILES["userfile"]["error"]=="0"){

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

					if ($PayPal_file){
						$buffer[9] = trim($buffer[9]);
						$buffer[8] = trim($buffer[9]);

						$buffer[9] = str_replace(",", "", $buffer[9]);
						$buffer[8] = str_replace(",", "", $buffer[8]);


					} else {
						$buffer[1] = trim($buffer[1]);
//						$buffer[1] = str_replace(",", "", $buffer[1]);

// https://basecamp.com/2070980/projects/1577907/messages/30895704
						$buffer[1] = str_replace(",", ".", $buffer[1]);
					}

					if ( !($buffer[7] < 0 && $buffer[8]<=0) && $PayPal_file)
                                                continue;

//func_print_r($buffer, $PayPal_file, $line_number);
//die();

					if ($PayPal_file){
                                                $description_csv = addslashes(trim($buffer[3]));
                                                $amount_csv = $buffer[9];
					}
					else {
						$description_csv = addslashes(trim($buffer[4]));
						$amount_csv = $buffer[1];
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

								$description_csv_UPPER = strtoupper($description_csv);
								$v_search_keyphrase_UPPER = strtoupper($v["search_keyphrase"]);

								$v_search_keyphrase_UPPER_arr = explode("<OR>", $v_search_keyphrase_UPPER);
								foreach ($v_search_keyphrase_UPPER_arr as $v_search_keyphrase_UPPER){
									$v_search_keyphrase_UPPER = trim($v_search_keyphrase_UPPER);
									if (strpos($description_csv_UPPER, $v_search_keyphrase_UPPER) !== false){
										$action = "D"; // Drop
									}
								}
							    }
							}
						}

						$manufacturerid = '0';
                                                foreach ($manufacturerid_info as $k => $v){
						    if (!empty($description_csv) && !empty($v["d_search_keyphrase_for_reconciliation"])){

							$d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $v["d_search_keyphrase_for_reconciliation"]);
							foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r){
								$vv_s_r = trim($vv_s_r);
//		                                                if (strpos($description_csv, $v["d_search_keyphrase_for_reconciliation"]) !== false){

								$description_csv_UPPER = strtoupper($description_csv);
								$vv_s_r_UPPER = strtoupper($vv_s_r);

		                                                if (strpos($description_csv_UPPER, $vv_s_r_UPPER) !== false){
        		                                                $manufacturerid = $k;
									break;
                	                                        }

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

		$reconciliation_search_condition = "$sql_tbl[reconciliations].date_csv>='".($search_data["reconciliation_tab_calculation"]["date_csv"]["start_date"])."'";
		$reconciliation_search_condition .= " AND $sql_tbl[reconciliations].date_csv<='".($search_data["reconciliation_tab_calculation"]["date_csv"]["end_date"])."'";
		$reconciliation_search_condition .= " AND $sql_tbl[reconciliations].action=''";

// ex.5
//		$reconciliation_search_condition .= " AND $sql_tbl[reconciliations].amount_csv<='0'";

#
##
###
		$check_reconciliations_for_mid = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $reconciliation_search_condition AND manufacturerid='0'");

		if (!empty($check_reconciliations_for_mid)){
			foreach ($check_reconciliations_for_mid as $k_c => $v_c){

	                        $action = "";
        	                if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
 	        	               foreach ($config_reconciliation_search_keyphrases as $k => $v){
					    if (!empty($v_c["description_csv"]) && !empty($v["search_keyphrase"])){

						$v_c_description_csv_UPPER = strtoupper($v_c["description_csv"]);
						$v_search_keyphrase_UPPER = strtoupper($v["search_keyphrase"]);

                                                $v_search_keyphrase_UPPER_arr = explode("<OR>", $v_search_keyphrase_UPPER);
                                                foreach ($v_search_keyphrase_UPPER_arr as $v_search_keyphrase_UPPER){
							$v_search_keyphrase_UPPER = trim($v_search_keyphrase_UPPER);
	        	        	                if (strpos($v_c_description_csv_UPPER, $v_search_keyphrase_UPPER) !== false){
        	        	        	                $action = "D"; // Drop
								break;
	                	                        }
						}
					    }
        	                	}
                	        }


		                $manufacturerid = '0';
        	                foreach ($manufacturerid_info as $k => $v){
				    if (!empty($v_c["description_csv"]) && !empty($v["d_search_keyphrase_for_reconciliation"])){

                                        $d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $v["d_search_keyphrase_for_reconciliation"]);
                                        foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r){

						$vv_s_r = trim($vv_s_r);

						$v_c_description_csv_UPPER = strtoupper($v_c["description_csv"]);
						$vv_s_r_UPPER = strtoupper($vv_s_r);

	        		                if (strpos($v_c_description_csv_UPPER, $vv_s_r_UPPER) !== false){
        	        		                $manufacturerid = $k;
							break;
                        	        	}
					}

					if ($manufacturerid > 0)
						break;
				    }
	                        }

				db_query("UPDATE $sql_tbl[reconciliations] SET manufacturerid='$manufacturerid', action='$action' WHERE id='$v_c[id]'");
			}
		}
###
##
#

#
##
                $reconciliations_manufacturers_search_condition = "";
                $orders_manufacturers_search_condition = "";

                if ($search_data["reconciliation_tab_".$tab]["select_distributors"] == "from_the_list"){
                        if (!empty($search_data["reconciliation_tab_".$tab]["manufacturers"])){
                                $tmp_manufacturers_str = implode("','",$search_data["reconciliation_tab_".$tab]["manufacturers"]);
                                $tmp_manufacturers_str = "'".$tmp_manufacturers_str."'";
                        } else {
                		$top_message["content"] = "Select distributor(s)";
		                $top_message["type"] = "E";

			        func_header_location("reconciliation.php?tab=".$tab);
                        }

                        $reconciliations_manufacturers_search_condition = " AND $sql_tbl[reconciliations].manufacturerid IN ($tmp_manufacturers_str)";
                        $orders_manufacturers_search_condition = " AND $sql_tbl[order_groups].manufacturerid IN ($tmp_manufacturers_str)";
		}
		else {
			// select_distributors == "ALL"
			$reconciliations_manufacturers_search_condition = " AND $sql_tbl[reconciliations].manufacturerid!='0'";
			$orders_manufacturers_search_condition = " AND $sql_tbl[order_groups].manufacturerid!='0'";
		}
##
#


		$reconciliation_search_condition .= $reconciliations_manufacturers_search_condition;

		$reconciliations = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $reconciliation_search_condition");

                $order_search_condition = "$sql_tbl[orders].date>='".($search_data["reconciliation_tab_calculation"]["date"]["start_date"])."'";
                $order_search_condition .= " AND $sql_tbl[orders].date<='".($search_data["reconciliation_tab_calculation"]["date"]["end_date"])."'";
		$order_search_condition .= $orders_manufacturers_search_condition;

		func_flush(".");

		$orders = func_query("SELECT $sql_tbl[order_groups].orderid, $sql_tbl[order_groups].manufacturerid, $sql_tbl[orders].date, $sql_tbl[order_groups].manufacturerid FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $order_search_condition");


		if (!empty($orders)){

                        foreach ($orders as $ko => $vo){

				$order_group_invoices = func_query_hash("SELECT * FROM $sql_tbl[order_group_invoices] WHERE orderid='$vo[orderid]' && manufacturerid='$vo[manufacturerid]' AND status='U'","invoice_number", false);
				$order_group_memos = func_query_hash("SELECT * FROM $sql_tbl[order_group_memos] WHERE orderid='$vo[orderid]' && manufacturerid='$vo[manufacturerid]' AND status='U'","memo_number", false);

				if (!empty($order_group_invoices)){
					$orders[$ko]["order_group_invoices"] = $order_group_invoices;
				}

				if (!empty($order_group_memos)){
					$orders[$ko]["order_group_memos"] = $order_group_memos;
				}

				if (empty($order_group_invoices) && empty($order_group_memos)){
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
        elseif ($mode == "update"){


		if (!empty($action) && is_array($action)){
	                foreach ($action as $k => $v){
        	                db_query("UPDATE $sql_tbl[reconciliations] SET action='$v' WHERE id='$k'");

				$status = "U";

				if ($v == "R"){
					$status = "R";
				}

        	                db_query("UPDATE $sql_tbl[order_group_invoices] SET status='$status' WHERE reconciliation_id='$k'");
                	        db_query("UPDATE $sql_tbl[order_group_memos] SET status='$status' WHERE reconciliation_id='$k'");
			}
                }

		// Untie selected transaction-order connections
                if (!empty($clear_invoices_memos) && is_array($clear_invoices_memos)){
                        foreach ($clear_invoices_memos as $k => $v){
                                if ($v == "Y"){

                                        $invoice_memo_arr = explode("_", $k);

                                        $invoice_OR_memo = $invoice_memo_arr[0];
                                        $reconciliation_id = $invoice_memo_arr[1];
                                        $number = $invoice_memo_arr[2];
                                        $manufacturerid = $invoice_memo_arr[3];
                                        $orderid = $invoice_memo_arr[4];

                                        if ($invoice_OR_memo == "I"){
                                                db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='0' WHERE reconciliation_id='$reconciliation_id' AND invoice_number='$number' AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
                                        } else {
                                                db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='0' WHERE reconciliation_id='$reconciliation_id' AND memo_number='$number' AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
                                        }
                                }
                        }
                }

		
		if (!empty($add_order_manually) && is_array($add_order_manually)){
			foreach ($add_order_manually as $r_id => $v_arr){
			  if (!empty($v_arr) && is_array($v_arr)){

			    $manufacturerid__amount_csv = func_query_first("SELECT manufacturerid, amount_csv FROM $sql_tbl[reconciliations] WHERE id='$r_id'");
			    $manufacturerid = $manufacturerid__amount_csv["manufacturerid"];

			    if (!empty($manufacturerid)){

				$amount_csv_abs = abs($manufacturerid__amount_csv["amount_csv"]);
				$amount_csv_abs = price_format($amount_csv_abs);

				foreach ($v_arr as $v){

					$orderid = trim($v["orderid"]);
                                	if (strpos($orderid,"-") !== false){
                                        	$orderid_arr = explode("-", $orderid);
	                                        $orderid = $orderid_arr["1"];
        	                        }
                	                $orderid = trim($orderid);

					$order_group_invoices = func_query($qqq="SELECT invoice_number FROM $sql_tbl[order_group_invoices] WHERE status='U' AND part_of_total_transaction_in_amount_of IN ('0.00','$amount_csv_abs') AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
					if (!empty($order_group_invoices)){
						foreach ($order_group_invoices as $vv){
							$invoice_number = $vv["invoice_number"];
							db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='$r_id', status='R' WHERE manufacturerid='$manufacturerid' AND orderid='$orderid' AND invoice_number='$invoice_number' AND reconciliation_id='0'");
						}
					}

                                        $order_group_memos = func_query("SELECT memo_number FROM $sql_tbl[order_group_memos] WHERE status='U' AND ref_to_us_part_of_transaction IN ('0.00','$amount_csv_abs') AND manufacturerid='$manufacturerid' AND orderid='$orderid' AND reconciliation_id='0'");
                                        if (!empty($order_group_memos)){
                                                foreach ($order_group_memos as $vv){
                                                        $memo_number = $vv["memo_number"];
                                                        db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='$r_id', status='R' WHERE manufacturerid='$manufacturerid' AND orderid='$orderid' AND memo_number='$memo_number'");
                                                }
                                        }
				} // foreach ($v_arr as $v)
			    } // if (!empty($manufacturerid))
			  } // if (!empty($v_arr) && is_array($v_arr))
			} // foreach ($add_order_manually as $r_id => $v_arr)
		} // if (!empty($add_order_manually) && is_array($add_order_manually))


                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
        }
	elseif ($mode == "unreconcile"){

                foreach ($action as $k => $v){
			if ($v == "UR"){
	                        db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");

                                db_query("UPDATE $sql_tbl[order_group_invoices] SET status='U' WHERE reconciliation_id='$k'");
                                db_query("UPDATE $sql_tbl[order_group_memos] SET status='U' WHERE reconciliation_id='$k'");
			}
                }

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
	}
        elseif ($mode == "undrop"){

                foreach ($action as $k => $v){
                        if ($v == "UD"){
                                db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");

                                db_query("UPDATE $sql_tbl[order_group_invoices] SET status='U' WHERE reconciliation_id='$k'");
                                db_query("UPDATE $sql_tbl[order_group_memos] SET status='U' WHERE reconciliation_id='$k'");
                        }
                }

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
        }
/*
	elseif ($mode == "clear_invoices_memos"){

		if (!empty($clear_invoices_memos) && is_array($clear_invoices_memos)){
			foreach ($clear_invoices_memos as $k => $v){
				if ($v == "Y"){

					$invoice_memo_arr = explode("_", $k);

					$invoice_OR_memo = $invoice_memo_arr[0];
					$reconciliation_id = $invoice_memo_arr[1];
					$number = $invoice_memo_arr[2];
					$manufacturerid = $invoice_memo_arr[3];
					$orderid = $invoice_memo_arr[4];

					if ($invoice_OR_memo == "I"){
						db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='0' WHERE reconciliation_id='$reconciliation_id' AND invoice_number='$number' AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
					} else {
						db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='0' WHERE reconciliation_id='$reconciliation_id' AND memo_number='$number' AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
					}
				}
			}
		}

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
	}
*/

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

        if (empty($search_data["reconciliation_tab_".$tab]["date"])){
                $search_data["reconciliation_tab_".$tab]["date"]["end_date"] = time();
                $search_data["reconciliation_tab_".$tab]["date"]["start_date"] = time() - 30*60*60*24;
        }

	$search_condition = "";

	if ($tab == "accounts_payable" || $tab == "receivables") {

                $order_search_condition = "$sql_tbl[orders].date>='".($search_data["reconciliation_tab_".$tab]["date"]["start_date"])."'";
                $order_search_condition .= " AND $sql_tbl[orders].date<='".($search_data["reconciliation_tab_".$tab]["date"]["end_date"])."'";

//              $order_search_condition .= " AND $sql_tbl[order_groups].reconciliation_id='0'";

		if ($tab == "receivables"){
			 $order_search_condition .= " AND xcart_order_groups.cb_status IN ('O', 'IO') AND xcart_order_groups.dc_status IN ('S','G','L','C') AND xcart_order_groups.po_status IN ('PN', 'P1', 'P2')";

			$order_search_fields = "$sql_tbl[orders].po_number, $sql_tbl[orders].firstname, $sql_tbl[orders].details, ";
		} else {
			$order_search_condition .= " AND $sql_tbl[order_groups].bd_status='X'";
		}


                $orders = func_query("SELECT $sql_tbl[order_groups].orderid, $sql_tbl[order_groups].manufacturerid, $order_search_fields $sql_tbl[orders].date, $sql_tbl[orders].order_prefix, $sql_tbl[order_groups].bd_status, $sql_tbl[order_groups].accounting, $sql_tbl[order_groups].manufacturerid, $sql_tbl[order_groups].total_gross FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $order_search_condition");

		if (!empty($orders) && $tab == "receivables"){

			x_load('crypt');
			$total_gross_accounting_1_2 = 0;
			$total_gross_accounting_0 = 0;
			$total_gross = 0;


			foreach ($orders as $k => $v){


				$accounting = func_make_accounting($v["orderid"], $v["manufacturerid"]);

//				$accounting = unserialize($v["accounting"]);

//func_print_r($v["total_gross"]);
//die();

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
				$current_total_gross_accounting_0 = $accounting[0]["gross"];        
				$current_total_gross = $v["total_gross"];

				$total_gross_accounting_1_2 += $current_total_gross_accounting_1_2;
				$total_gross_accounting_0 += $current_total_gross_accounting_0;
				$total_gross += $current_total_gross;

				$v["current_total_gross_accounting_1_2"] = $current_total_gross_accounting_1_2;
				$v["current_total_gross_accounting_0"] = $current_total_gross_accounting_0;
				$v["current_total_gross"] = $current_total_gross;

				$orders[$k] = $v;
			}

			$smarty->assign("orders", $orders);

			$smarty->assign("total_gross_accounting_1_2", $total_gross_accounting_1_2);
			$smarty->assign("total_gross_accounting_0", $total_gross_accounting_0);
			$smarty->assign("total_gross", $total_gross);

//func_print_r($orders, $total_gross_accounting_1_2);

		}

		if (!empty($orders) && $tab == "accounts_payable"){

			$all_manufacturers_in_orders = array();

			foreach($orders as $k => $v){
				$all_manufacturers_in_orders[] = $v["manufacturerid"];
			}

			$all_manufacturers_in_orders_unique = array_unique($all_manufacturers_in_orders);

			$sum_total_gross_accounting_1_2 = 0;

			foreach ($all_manufacturers_in_orders_unique as $k => $v){
				foreach ($orders as $kk => $vv){
					if ($v == $vv["manufacturerid"]){

						$all_manufacturers_orders[$v]["manufacturerid"] = $v;
						$all_manufacturers_orders[$v]["distr_code"] = $manufacturerid_info[$v]["code"];
						$all_manufacturers_orders[$v]["manufacturer"] = $manufacturerid_info[$v]["manufacturer"];


						$accounting = func_make_accounting($vv["orderid"], $vv["manufacturerid"]);
//						$accounting = unserialize($vv["accounting"]);
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

				$sum_total_gross_accounting_1_2 += $all_manufacturers_orders[$v]["total_gross_accounting_1_2"];
			}


			$all_manufacturers_orders = my_array_sort($all_manufacturers_orders, "manufacturer", SORT_ASC);

			$smarty->assign("sum_total_gross_accounting_1_2", $sum_total_gross_accounting_1_2);
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

		if ($tab == "reconciled" || $tab == "unreconciled"){

		    $tmp_manufacturers_search_condition = "";

		    if ($search_data["reconciliation_tab_".$tab]["select_distributors"] == "from_the_list"){
			if (!empty($search_data["reconciliation_tab_".$tab]["manufacturers"])){
				$tmp_manufacturers_str = implode("','",$search_data["reconciliation_tab_".$tab]["manufacturers"]);
				$tmp_manufacturers_str = "'".$tmp_manufacturers_str."'";
			} else {
				$tmp_manufacturers_str = "'0'";
			}

			$tmp_manufacturers_search_condition = " AND manufacturerid IN ($tmp_manufacturers_str)";
			$search_condition .= $tmp_manufacturers_search_condition;
		    }
		    else {
			// select_distributors == "ALL"
			// therefore 'manufacturerid' is not included to the query and all manufacturers will be selected
		    }


		    if ($tab == "unreconciled" && $search_data["reconciliation_tab_".$tab]["show_unreconciled_invoices_and_memos"] == "Y"){

	                $unreconciled_order_search_condition = "$sql_tbl[orders].date>='".($search_data["reconciliation_tab_".$tab]["date"]["start_date"])."'";
        	        $unreconciled_order_search_condition .= " AND $sql_tbl[orders].date<='".($search_data["reconciliation_tab_".$tab]["date"]["end_date"])."'";

	                $unreconciled_orders = func_query("SELECT $sql_tbl[order_groups].orderid, $sql_tbl[order_groups].manufacturerid, $sql_tbl[orders].date, $sql_tbl[order_groups].manufacturerid, $sql_tbl[orders].order_prefix FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $unreconciled_order_search_condition $tmp_manufacturers_search_condition AND $sql_tbl[order_groups].cb_status IN ('O','P','3','H') ORDER BY $sql_tbl[order_groups].orderid desc");

        	        if (!empty($unreconciled_orders)){

                	        foreach ($unreconciled_orders as $ko => $vo){

                        	        $order_group_invoices = func_query_hash("SELECT * FROM $sql_tbl[order_group_invoices] WHERE orderid='$vo[orderid]' && manufacturerid='$vo[manufacturerid]' AND (status='U' || status='A') AND reconciliation_id='0'","invoice_number", false);
	                                $order_group_memos = func_query_hash("SELECT * FROM $sql_tbl[order_group_memos] WHERE orderid='$vo[orderid]' && manufacturerid='$vo[manufacturerid]' AND (status='U' || status='A') AND reconciliation_id='0'","memo_number", false);

        	                        if (!empty($order_group_invoices)){
                	                        $unreconciled_orders[$ko]["order_group_invoices"] = $order_group_invoices;
                        	        }

                                	if (!empty($order_group_memos)){
                                        	$unreconciled_orders[$ko]["order_group_memos"] = $order_group_memos;
	                                }
        	                }
	
				$smarty->assign("unreconciled_orders", $unreconciled_orders);
	                }

		    } // if ($tab == "unreconciled")


		}

		$reconciliations = func_query("SELECT * FROM $sql_tbl[reconciliations] WHERE $search_condition ORDER BY date_csv");
	}
}

//func_print_r($reconciliations);

if (!empty($reconciliations) && is_array($reconciliations)){

	$charged_twice_orders = array();

	foreach ($reconciliations as $k => $v){

                $manufacturerid = 0;
                foreach ($manufacturerid_info as $kk => $vv){
	                if (!empty($v["description_csv"]) && !empty($vv["d_search_keyphrase_for_reconciliation"])){

                                $d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $vv["d_search_keyphrase_for_reconciliation"]);

	                        foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r){

					$vv_s_r = trim($vv_s_r);
					
					$v_description_csv_UPPER = strtoupper($v["description_csv"]);	
					$vv_s_r_UPPER = strtoupper($vv_s_r);


		      	                if (strpos($v_description_csv_UPPER, $vv_s_r_UPPER) !== false){

	        	                        $manufacturerid = $kk;
//						$reconciliations[$k]["description_csv"] = str_replace($vv_s_r_UPPER, "<B>".$vv_s_r_UPPER."</B>", $reconciliations[$k]["description_csv"]);
						$reconciliations[$k]["description_csv"] = str_replace($vv_s_r_UPPER, "<B>".$vv_s_r_UPPER."</B>", $v_description_csv_UPPER);

//						break;
					}
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

//			$orderids = func_query("SELECT orderid, invoice_number, memo_number FROM $sql_tbl[reconciliation_orderid] WHERE reconciliation_id='$v[id]' OR reconciliation_id='$v[id]' ORDER BY orderid");

			$invoices = func_query("SELECT * FROM $sql_tbl[order_group_invoices] WHERE reconciliation_id='$v[id]'");
			$memos = func_query("SELECT * FROM $sql_tbl[order_group_memos] WHERE reconciliation_id='$v[id]'");

			$found_records = array();
			$counter = 0;
			$found_order_info_for_speed_search = array();
			$total_invoices_and_memos_amounts = 0;
			$total_invoices_amounts = 0;
			$tota_memos_amounts = 0;
			if (!empty($invoices)){
				foreach ($invoices as $kk => $vv){

					if (empty($found_order_info_for_speed_search[$vv["orderid"]]["order_prefix"])){
						$order_info_arr = func_query_first("SELECT order_prefix, date FROM $sql_tbl[orders] WHERE orderid='$vv[orderid]'");
						$found_order_info_for_speed_search[$vv["orderid"]] = $order_info_arr;
					}
					else {
						$order_info_arr = $found_order_info_for_speed_search[$vv["orderid"]];
					}
					$found_records[$counter] = $order_info_arr;
					$found_records[$counter]["orderid"] = $vv["orderid"];
					$found_records[$counter]["invoice_info"] = $vv;

					$total_invoices_and_memos_amounts += $vv["invoice_total"];
					$total_invoices_amounts += $vv["invoice_total"];

					$counter++;
				}
			}
			if (!empty($memos)){
                                foreach ($memos as $kk => $vv){

                                        if (empty($found_order_info_for_speed_search[$vv["orderid"]]["order_prefix"])){
                                                $order_info_arr = func_query_first("SELECT order_prefix, date FROM $sql_tbl[orders] WHERE orderid='$vv[orderid]'");
                                                $found_order_info_for_speed_search[$vv["orderid"]] = $order_info_arr;
                                        }
                                        else {
						$order_info_arr = $found_order_info_for_speed_search[$vv["orderid"]];
                                        }
					$found_records[$counter] = $order_info_arr;
					$found_records[$counter]["orderid"] = $vv["orderid"];
					$found_records[$counter]["memo_info"] = $vv;

					$total_invoices_and_memos_amounts += $vv["ref_to_us_total"];
					$tota_memos_amounts += $vv["ref_to_us_total"];

					$counter++;
				}
			}

			if (!empty($found_records)){
				foreach ($found_records as $kk => $vv){
					$diff_date = ($v["date_csv"] - $vv["date"])/(60*60*24);
					$found_records[$kk]["diff_date"] = $diff_date;
				}

				$reconciliations[$k]["invoices_and_memos"] = $found_records;

				$reconciliations[$k]["total_invoices_amounts"] = $total_invoices_amounts;
				$reconciliations[$k]["tota_memos_amounts"] = $tota_memos_amounts;
				$reconciliations[$k]["total_invoices_amounts_MIN_memos_amounts"] = $total_invoices_amounts - $tota_memos_amounts;

				$reconciliations[$k]["total_invoices_and_memos_amounts"] = $total_invoices_and_memos_amounts;

                                if ($total_invoices_and_memos_amounts > 0 && $reconciliations[$k]["amount_csv_abs"] > 0 && $total_invoices_and_memos_amounts != $reconciliations[$k]["amount_csv_abs"]){
                                	$total_invoices_and_memos_amounts__amount_csv_abs_diff = $reconciliations[$k]["amount_csv_abs"] - $total_invoices_and_memos_amounts;
                                        $total_invoices_and_memos_amounts__amount_csv_abs_diff_abs = abs($total_invoices_and_memos_amounts__amount_csv_abs_diff);
                                        $reconciliations[$k]["total_invoices_and_memos_amounts__amount_csv_abs_diff_abs"] = price_format($total_invoices_and_memos_amounts__amount_csv_abs_diff_abs);
                                        $reconciliations[$k]["total_invoices_and_memos_amounts__amount_csv_abs_diff"] = price_format($total_invoices_and_memos_amounts__amount_csv_abs_diff);
				}
			}

		    } // if (!empty($manufacturerid))
		} // if ($tab == "unreconciled" || $tab == "reconciled")



                        if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
                                foreach ($config_reconciliation_search_keyphrases as $kk => $vv){
                                    if (!empty($v["description_csv"]) && !empty($vv["search_keyphrase"])){

					$v_description_csv_UPPER = strtoupper($v["description_csv"]);
					$vv_search_keyphrase_UPPER = strtoupper($vv["search_keyphrase"]);

					$flag_config_search_keyphrase_found = false;
                                        $vv_search_keyphrase_UPPER_arr = explode("<OR>", $vv_search_keyphrase_UPPER);

//func_print_r($vv_search_keyphrase_UPPER_arr);
                                        foreach ($vv_search_keyphrase_UPPER_arr as $vv_search_keyphrase_UPPER){
						$vv_search_keyphrase_UPPER = trim($vv_search_keyphrase_UPPER);
						if (strpos($v_description_csv_UPPER, $vv_search_keyphrase_UPPER) !== false){
							$flag_config_search_keyphrase_found = true;
							break;
						}
					}

                                        if ($flag_config_search_keyphrase_found){


//func_print_r($vv_search_keyphrase_UPPER);
                                                $reconciliations[$k]["description_csv"] = str_replace($vv_search_keyphrase_UPPER, "<B>".$vv_search_keyphrase_UPPER."</B>", $v_description_csv_UPPER);
                                                $reconciliations[$k]["config_search_keyphrase_found"] = "Y";

						if ($tab == "expense_report"){
	                                                $config_reconciliation_search_keyphrases[$kk]["total_amount"] += $reconciliations[$k]["amount_csv"];

							if (!empty($reconciliations[$k]["amount_csv_abs"])){
	        	                                        $config_reconciliation_search_keyphrases[$kk]["total_amount_with_abs"] += $reconciliations[$k]["amount_csv_abs"];
							}

                	                                $config_reconciliation_search_keyphrases[$kk]["found_records"][] = $reconciliations[$k];
						}

                                                break;
                                        }
                                    }
                                }
                        }
/*
#
##
###
		if ($reconciliations[$k]["d_bulk_or_individual_order_payments"] == "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"){
			if (!empty($reconciliations[$k]["invoices_and_memos"][0]["orderid"])){

				$charged_twice_orders[] = $reconciliations[$k]["invoices_and_memos"][0]["orderid"];

				$two_reconciliations = func_query("SELECT $sql_tbl[reconciliation_orderid].*, $sql_tbl[reconciliations].date_csv,  $sql_tbl[reconciliations].file_upload_date, $sql_tbl[reconciliations].description_csv, $sql_tbl[reconciliations].amount_csv,  $sql_tbl[reconciliations].action, $sql_tbl[reconciliations].transaction_type, $sql_tbl[reconciliations].manufacturerid FROM $sql_tbl[reconciliation_orderid] LEFT JOIN $sql_tbl[reconciliations] ON $sql_tbl[reconciliations].id=$sql_tbl[reconciliation_orderid].reconciliation_id WHERE $sql_tbl[reconciliation_orderid].orderid='".$reconciliations[$k]["invoices_and_memos"][0]["orderid"]."' ORDER BY $sql_tbl[reconciliations].date_csv");

				if (!empty($two_reconciliations)){

					foreach ($two_reconciliations as $k_r => $v_r){


				                foreach ($manufacturerid_info as $kk => $vv){
				                        if (!empty($v_r["description_csv"]) && !empty($vv["d_search_keyphrase_for_reconciliation"])){

						                $d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $vv["d_search_keyphrase_for_reconciliation"]);

					                        foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r){

        				                                $vv_s_r = trim($vv_s_r);
	
				                                        $v_description_csv_UPPER = strtoupper($v_r["description_csv"]);
                				                        $vv_s_r_UPPER = strtoupper($vv_s_r);
		

                                				        if (strpos($v_description_csv_UPPER, $vv_s_r_UPPER) !== false){
                                				                $two_reconciliations[$k_r]["description_csv"] = str_replace($vv_s_r_UPPER, "<B>".$vv_s_r_UPPER."</B>", $v_description_csv_UPPER);
				                                        }
                                				}
				                        }
				                }


						if ($v_r["amount_csv"] < 0){
							$two_reconciliations[$k_r]["amount_csv_abs"] = abs($v_r["amount_csv"]);
						}
					}

					$reconciliations[$k]["two_reconciliations"] = $two_reconciliations;
				}
			}
		}
###
##
#
*/
	} // foreach ($reconciliations as $k => $v)

	$charged_twice_orders_count = array_count_values($charged_twice_orders);

	if (!empty($charged_twice_orders_count)){
		foreach ($charged_twice_orders_count as $orderid => $count){
			if ($count == "2"){
				$cnt=1;
				foreach ($reconciliations as $k => $v){

					if ($v["invoices_and_memos"][0]["orderid"] == $orderid){
						$reconciliations[$k]["row"] = $cnt;
						$cnt++;
					}
				}
			}
		}
	}


	$smarty->assign("charged_twice_orders_count", $charged_twice_orders_count);
	$smarty->assign("charged_twice_orders", $charged_twice_orders);
}


//func_print_r($reconciliations);

//func_print_r($reconciliations, $charged_twice_orders, $charged_twice_orders_count);

//func_print_r($config_reconciliation_search_keyphrases);

$location[] = array("Reconciliation", "");

if ($tab == "expense_report"){

	$expense_report_sum_total_amount = 0;
	$expense_report_sum_total_amount_with_abs = 0;

	if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)){
		foreach ($config_reconciliation_search_keyphrases as $k => $v){
			$expense_report_sum_total_amount += $v["total_amount"];

			if (!empty($v["total_amount_with_abs"])){
				$expense_report_sum_total_amount_with_abs += $v["total_amount_with_abs"];
			}
		}
/*
		if ($expense_report_sum_total_amount < 0){
			$expense_report_sum_total_amount_with_abs = abs($expense_report_sum_total_amount);
			$smarty->assign("expense_report_sum_total_amount_with_abs", $expense_report_sum_total_amount_with_abs);
		}
*/
	}

	$smarty->assign("expense_report_sum_total_amount", $expense_report_sum_total_amount);
	$smarty->assign("expense_report_sum_total_amount_with_abs", $expense_report_sum_total_amount_with_abs);
}

$smarty->assign("tab", $tab);
$smarty->assign("config_reconciliation_search_keyphrases", $config_reconciliation_search_keyphrases);
//$smarty->assign("search_prefilled1", $search_data["find_orders"]);

$smarty->assign("search_prefilled", $search_data["reconciliation_tab_".$tab]);

#
##
$manufacturers = func_query_hash("SELECT manufacturerid, manufacturer, code FROM $sql_tbl[manufacturers] WHERE avail='Y' ORDER BY manufacturer, orderby","manufacturerid",false);
$smarty->assign('manufacturers', $manufacturers);
##
#
//func_print_r($manufacturers);
//func_print_r($reconciliations);

$smarty->assign("search_keyphrase_list", $search_keyphrase_list);
$smarty->assign("reconciliations", $reconciliations);
$smarty->assign("reconciliation_upload_info", $reconciliation_upload_info);
$smarty->assign("main", "reconciliation");
$smarty->assign("location", $location);
$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
