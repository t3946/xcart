<?php
define("CIDEV_CRON_START", "CRON");

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

require $xcart_dir."/include/countries.php";
require $xcart_dir."/include/states.php";

include $xcart_dir."/shipping/shipping.php";

x_load('http');

//$show_arb_account_field = func_use_arb_account();

$current_carrier = "UPS";

if ($config["Shipping"]["use_intershipper"] == "Y")
        include $xcart_dir."/shipping/intershipper.php";
else
        include $xcart_dir."/shipping/myshipper.php";

//$tmp_manufacturers = func_query("SELECT manufacturerid, shipping_rates_last_update_date FROM $sql_tbl[manufacturers] WHERE update_approximation_shipping_rates='Y'");
$tmp_manufacturers = func_query("SELECT manufacturerid, shipping_rates_last_update_date, update_approximation_shipping_rates FROM $sql_tbl[manufacturers]");
$max_manufacturerid = func_query_first_cell("SELECT MAX(manufacturerid) FROM $sql_tbl[manufacturers]");

$current_date = date("j", time());

if (!empty($tmp_manufacturers)){
	$manufacturers = array();
	foreach ($tmp_manufacturers as $k => $v){
		$manufacturerid = $v["manufacturerid"];
		$shipping_rates_last_update_date = $v["shipping_rates_last_update_date"];

//		$date_shipping_rates_last_update_date = date("j", $shipping_rates_last_update_date);

		$m_val = ($manufacturerid - 1)/($max_manufacturerid - 1);
		$IDNorm = 27 * $m_val +1;
		$IDNorm = intval($IDNorm);

//func_print_r($IDNorm, $date_shipping_rates_last_update_date, $manufacturerid);

		if ((($shipping_rates_last_update_date > 0 && $current_date == $IDNorm) || $shipping_rates_last_update_date == '0') || $v["update_approximation_shipping_rates"] == "Y"){
			$manufacturers[] = $manufacturerid;
		}
	}
}

//func_print_r($tmp_manufacturers, $manufacturers);
//die();

$states = func_query("SELECT * FROM $sql_tbl[states] WHERE country_code='US' AND base_state_zipcode!=''");

if (!empty($manufacturers) && is_array($manufacturers) && !empty($states)){

  $start_time = time();
  $failed_requests = 0;
  $list_of_updated_suppliers = "";

  foreach ($manufacturers as $manufacturerid) {

    $manufacturer_info = func_query_first("SELECT manufacturer, m_city, m_country, m_state, m_zipcode FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");
    $config['Company']['location_city'] = $manufacturer_info['m_city'];
    $config['Company']['location_state'] = $manufacturer_info['m_state'];
    $config['Company']['location_country'] = $manufacturer_info['m_country'];
    $config['Company']['location_zipcode'] = $manufacturer_info['m_zipcode'];
    $config["Company"]["location_country_name"] = func_get_country($config["Company"]["location_country"]);
    $config["Company"]["location_state_name"] = func_get_state($config["Company"]["location_state"], $config["Company"]["location_country"]);

    $failed_states = "";


    if ($show_shippings == "Y"){
	    func_print_r($manufacturer_info);
    }

    foreach ($states as $state_info){

        $userinfo["s_country"] = $state_info["country_code"];
        $userinfo["s_state"] = $state_info["code"];
        $userinfo["s_zipcode"] = $state_info["base_state_zipcode"];

	$found_shippings = array();

	for ($i=0; $i<3; $i++){
		func_flush(".");

		if ($i == "0"){
			$weight = "1";
		}
		elseif ($i == "1"){
                        $weight = "75";
                }
                else {
                        $weight = "150";
                }

		$intershipper_rates = func_shipper($weight, $userinfo);

		if ($show_shippings == "Y"){
			func_print_r($intershipper_rates);
		}

		if (!empty($intershipper_rates) && is_array($intershipper_rates)){
			foreach ($intershipper_rates as $shipping){
				if ( 

					($shipping["methodid"] == "1" && $manufacturer_info['m_country'] == "US") // UPS Ground
					||
					($shipping["methodid"] == "65" && $manufacturer_info['m_country'] == "CA") // UPS Ground
				){
					$found_shippings[$i] = $shipping;
					$found_shippings[$i]["weight"] = $weight;
				}
			}
		}
	}


        $count_found_shippings = count($found_shippings);
        if ($count_found_shippings == "3"){

		db_query("DELETE FROM $sql_tbl[approximation_shipping_rates] WHERE manufacturerid='$manufacturerid' AND state='$state_info[code]'");
		db_query("INSERT INTO $sql_tbl[approximation_shipping_rates] (manufacturerid, state, bw_1, bw_75, bw_150, last_updated_date) VALUES ('$manufacturerid', '$state_info[code]', '".$found_shippings[0]["rate"]."', '".$found_shippings[1]["rate"]."', '".$found_shippings[2]["rate"]."', '".time()."')");

        } else {
		$failed_requests = $failed_requests + (3 - $count_found_shippings);

		$failed_states .= $state_info["code"] . " (".$state_info["base_state_zipcode"].") \n";
	}
	unset($found_shippings);

	db_query("UPDATE $sql_tbl[manufacturers] SET update_approximation_shipping_rates='N', shipping_rates_last_update_date='".time()."' WHERE manufacturerid='$manufacturerid'");

    }

    $list_of_updated_suppliers .= $manufacturer_info["manufacturer"]."\n";

    if (!empty($failed_states)){
	$list_of_updated_suppliers .= "Failed states: \n" . $failed_states . "\n\n";
    }

    unset($failed_states);
  }

  $current_time = time();
  $diff_time_in_mins = ($current_time - $start_time)/60;
  $count_manufacturer_info = count($manufacturers);

  $subj = "Shipping rates approximation process finished";
  $body = "
                Updated distributors: $count_manufacturer_info \n
                Failed requests: $failed_requests \n
                Working time:  $diff_time_in_mins minutes \n\n
		List of updated distributors: \n
		$list_of_updated_suppliers
		";

// func_send_simple_mail("xcartmaster@gmail.com", $subj, $body, "xcart@s3stores.com");
 func_send_simple_mail("feeds@s3stores.com", $subj, $body, "xcart@s3stores.com");

}

print"<br />DONE!";

?>
