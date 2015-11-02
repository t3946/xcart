<?php
@set_time_limit(0);

require "./auth.php";
require $xcart_dir."/include/security.php";
x_load('files', 'db');

$location[] = array("BPU", "");

if ($REQUEST_METHOD=="POST") {

    if ($mode == "upload"){

	if (!empty($config["bpu_operator_login"]) && $login != $config["bpu_operator_login"]){
		func_header_location("bpu.php");
	}

	db_query("UPDATE $sql_tbl[config] SET value='$login' WHERE name='bpu_operator_login'");

	$userfile = func_move_uploaded_file("userfile");

	if ($fp = func_fopen($userfile,"r",true)) {

		$decimal_format_fields = array("weight", "list_price", "shipping_freight", "cost_to_us", "map_price", "new_map_price", "product_price_multiplier");

		$line_number = 0;

		db_query("DELETE FROM $sql_tbl[bpu_rows]");

		while ($row = fgetcsv ($fp, 65536, $delimiter)) {

			$line_number++;

                        if ($line_number % 100 == 0) {
                                func_flush(".");
                                if ($line_number % 5000 == 0) {
                                        func_flush("<br />\n");
                                }

                                func_flush();
                        }

                        if (empty($row)) {
                                continue;
                        }

			if ($line_number == "1"){

				$table_fields = func_query_first('SELECT * FROM ' . $sql_tbl['products'] . ' LIMIT 1');
				$table_fields = array_keys($table_fields);

				$not_found_fields = array();
				$productcode_field_found = false;

				foreach ($row as $k => $v){

					$name = str_replace("!", "", $v);
					$name = trim(strtolower($name));

					if ($name == "productcode"){
						$key_productcode = $k;
						$productcode_field_found = true;
					}

					$row_names[$k] = $name;

					if (!in_array($name, $table_fields)){
						$not_found_fields[] = $name;
					}
				}

				$err_message = "";

				if (!$productcode_field_found){
					$err_message .= "!PRODUCTCODE field is mandatory. <br />";
				}

				if (!empty($not_found_fields)){
					foreach ($not_found_fields as $k => $v){
						$err_message .= "Field ".$v ." is not found in the XCART_PRODUCTS table. <br />";
					}
				}

				if (!empty($err_message)){
				        fclose($fp);
				        @unlink($userfile);
				        db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='bpu_operator_login'");

		                        $top_message['content'] = $err_message;
                		        $top_message['type'] = 'E';
		                        func_header_location('bpu.php');
				}

				db_query('INSERT INTO '.$sql_tbl["bpu_rows"].' (serialized_row) VALUES ("'.addslashes(serialize($row_names)).'")');
			} else {

				$productcode = addslashes($row[$key_productcode]);

				if (empty($productcode))
					continue;

				$serialized_row_arr = array();

                                foreach ($row as $k => $v){
                                        $value = trim($v);

					if (in_array($row_names[$k], $decimal_format_fields)){
						$value = ($value != "") ? abs(doubleval(str_replace(",",".",$value))) : 0;
					}

					$serialized_row_arr[][$row_names[$k]] = $value;
                                }

				$serialized_row = serialize($serialized_row_arr);

				db_query("INSERT INTO $sql_tbl[bpu_rows] (productcode, serialized_row) VALUES ('$productcode', '".addslashes($serialized_row)."')");

				unset($serialized_row_arr);
			}
		}
	}
	fclose($fp);
	@unlink($userfile);

	func_header_location("bpu.php?step=2");
    }
    elseif ($mode == "cancel"){

	db_query("DELETE FROM $sql_tbl[bpu_rows]");
	db_query("DELETE FROM $sql_tbl[bpu_result]");
	db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='bpu_operator_login'");

	func_header_location("bpu.php");
    }
    elseif ($mode == "import"){

	$bpu_rows = func_query("SELECT * FROM $sql_tbl[bpu_rows] WHERE productcode!=''");

	if (!empty($bpu_rows)){

		db_query("DELETE FROM $sql_tbl[bpu_result]");

		foreach ($bpu_rows as $k => $v){

			if (empty($v["productcode"])){
				continue;
			}

                        if ($k % 100 == 0) {
                                func_flush(".");
                                if ($k % 5000 == 0) {
                                        func_flush("<br />\n");
                                }

                                func_flush();
                        }

			$row = unserialize($v["serialized_row"]);

			if (!empty($row) && is_array($row)){

				$productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='$v[productcode]'");

				if (!empty($productid)){

					$field_and_value_arr = array();

					foreach ($row as $kk => $vv){
						if (!empty($vv) && is_array($vv)){
							foreach ($vv as $kkk => $vvv){
								if ($kkk != "productcode"){
									$field_and_value_arr[] = $kkk."='".addslashes($vvv)."'";
								}
							}
						}
					}

					$field_and_value = implode(", ", $field_and_value_arr);
					unset($field_and_value_arr);
	
					if (!empty($field_and_value)){
						db_query("UPDATE $sql_tbl[products] SET $field_and_value WHERE productid='$productid'");

						$result = "updated";
					} else {
						$result = "skipped";
					}
				} else {
					$result = "no such product";
				}
			} else {
				$result = "skipped";
			}

			db_query("INSERT INTO $sql_tbl[bpu_result] (productcode, result) VALUES ('$v[productcode]', '$result')");
		}

		db_query("DELETE FROM $sql_tbl[bpu_rows]");
	        db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='bpu_operator_login'");
	}

        func_header_location("bpu.php?step=3");
    }
}

$step = !empty($step) ? abs(intval($step)) : 1;

if (!empty($config["bpu_operator_login"]) && $login != $config["bpu_operator_login"]){

	$step = 0;

	$bpu_operator_name = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$config[bpu_operator_login]'");
	$smarty->assign ("bpu_operator_name", $bpu_operator_name);
}

$bpu_rows_count = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[bpu_rows]");

if ($bpu_rows_count > 0 && $step != '2'){
	$step = 2;
}

if ($step == "2"){

	$bpu_rows = func_query("SELECT * FROM $sql_tbl[bpu_rows] ORDER BY id");

	if (empty($bpu_rows)){
		func_header_location("bpu.php");
	} else {
		foreach ($bpu_rows as $k => $v){
			$bpu_rows[$k]["row"] = unserialize($v["serialized_row"]);
		}
	}

	$smarty->assign("bpu_rows", $bpu_rows);
} 
elseif ($step == "3"){

	$results = func_query("SELECT DISTINCT result, count(*) as count FROM $sql_tbl[bpu_result] GROUP BY result");
	$smarty->assign("results", $results);

	$full_result = func_query("SELECT * FROM $sql_tbl[bpu_result] ORDER BY id");
	$smarty->assign("full_result", $full_result);
}

//func_print_r($bpu_rows);
//func_print_r($config["bpu_operator_login"]);

// func_build_quick_flags($pids);
// func_build_quick_prices($pids);

$smarty->assign("step", $step);
$smarty->assign("main", "bpu");

$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
