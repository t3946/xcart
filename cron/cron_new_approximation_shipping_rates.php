<?php

use Modules\Product\Models\ProductModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Modules\Shipping\Models\ShippingProductModel;

define("CIDEV_CRON_START", "CRON");

set_time_limit(0);

require "../top.inc.php";
require "../init.php";
global $xcart_dir, $config;

require $xcart_dir . "/include/countries.php";
require $xcart_dir . "/include/states.php";
//
//include $xcart_dir . "/shipping/shipping.php";

$current_carrier = "UPS";

//if ($config["Shipping"]["use_intershipper"] == "Y")
//    include $xcart_dir . "/shipping/intershipper.php";
//else
//    include $xcart_dir . "/shipping/myshipper.php";

$tmp_manufacturers = func_query_param(/** @lang MySQL */
    "SELECT manufacturerid, shipping_rates_last_update_date, update_approximation_shipping_rates FROM xcart_manufacturers", []);
$max_manufacturerid = func_query_first_cell_param(/** @lang MySQL */
    "SELECT MAX(manufacturerid) FROM xcart_manufacturers", []);

$current_date = date("j", time());

if (!empty($tmp_manufacturers)) {
    $manufacturers = array();
    foreach ($tmp_manufacturers as $k => $v) {
        $manufacturerid = $v["manufacturerid"];
        $shipping_rates_last_update_date = $v["shipping_rates_last_update_date"];

//		$date_shipping_rates_last_update_date = date("j", $shipping_rates_last_update_date);

        $m_val = ($manufacturerid - 1) / ($max_manufacturerid - 1);
        $IDNorm = 27 * $m_val + 1;
        $IDNorm = intval($IDNorm);

//func_print_r($IDNorm, $date_shipping_rates_last_update_date, $manufacturerid);

        if ((($shipping_rates_last_update_date > 0 && $current_date == $IDNorm) || $shipping_rates_last_update_date == '0') || $v["update_approximation_shipping_rates"] == "Y") {
            $manufacturers[] = $manufacturerid;
        }
    }
}


//func_print_r($tmp_manufacturers, $manufacturers);
//die();

$states = func_query_param(/** @lang MySQL */
    "SELECT * FROM xcart_states WHERE country_code='US' AND base_state_zipcode!=''", []);

if (!empty($manufacturers) && is_array($manufacturers) && !empty($states)) {

    $start_time = time();
    $failed_requests = 0;
    $list_of_updated_suppliers = "";

    foreach ($manufacturers as $manufacturerid) {

        $dx_fail_iterator = 0;


        $manufacturer_info = func_query_first_param(/** @lang MySQL */
            "SELECT manufacturer, m_city, m_country, m_state, m_zipcode 
				   FROM xcart_manufacturers 
				  WHERE manufacturerid=:manufacturerid", ['manufacturerid' => $manufacturerid]);
        $config['Company']['location_city'] = $manufacturer_info['m_city'];
        $config['Company']['location_state'] = $manufacturer_info['m_state'];
        $config['Company']['location_country'] = $manufacturer_info['m_country'];
        $config['Company']['location_zipcode'] = $manufacturer_info['m_zipcode'];
        $config["Company"]["location_country_name"] = func_get_country($config["Company"]["location_country"]);
        $config["Company"]["location_state_name"] = func_get_state($config["Company"]["location_state"], $config["Company"]["location_country"]);

        $failed_states = "";


        if ($show_shippings == "Y") {
            func_print_r($manufacturer_info);
        }

        foreach ($states as $state_info) {

            $dx_fail_mes = null;

            $userinfo["s_country"] = $state_info["country_code"];
            $userinfo["s_state"] = $state_info["code"];
            $userinfo["s_zipcode"] = $state_info["base_state_zipcode"];

            $found_shippings = array();

            for ($i = 0; $i < 3; $i++) {
                func_flush(".");

                if ($i == "0") {
                    $weight = 1;
                }
                else if ($i == "1") {
                    $weight = 75;
                }
                else {
                    $weight = 150;
                }

                $iterator = 0;

                do
                {
                    if($iterator > 0){
                        echo "+";
                        sleep(1);
                    }

                    $product = new ProductModel;

                    $product->productid = "9992252609";

                    $product->setPrice(1);

                    $product->cost_to_us = 10;

                    $product->setWeight($weight);
                    $product->dim_x = 1;
                    $product->dim_y = 1;
                    $product->dim_z = 1;

                    $product->manufacturerid = $manufacturerid;

                    if($shipping_rate = ShippingHelper::getTmpStateMinShipping($product, 1, $userinfo, 1, false)) {

                        if (!empty($shipping_rate) && is_array($shipping_rate)) {
                            foreach ($shipping_rate as $shipping) {

                                $ships_data = [];
                                $ships_data['methodid'] = $shipping->shippingid;
                                $ships_data['rate'] = $shipping->getShippingQuote();

                                if( ($ships_data['methodid'] === 1) || ($ships_data['methodid'] == 65) ) {
                                    echo "\n {$manufacturerid} --- {$state_info['code']} --- {$ships_data['methodid']} --- {$weight} --- {$ships_data['rate']} \n";
                                }

                                if (

                                    ($ships_data["methodid"] == "1" && $manufacturer_info['m_country'] == "US") // UPS Ground
                                    ||
                                    ($ships_data["methodid"] == "65" && $manufacturer_info['m_country'] == "CA") // UPS Ground
                                ) {
                                    $found_shippings[ $i ] = $ships_data;
                                    $found_shippings[ $i ]["weight"] = $weight;
                                }
                            }
                        }
                    }
                    $iterator++;
                } while ( !isset($found_shippings[$i]) && $iterator < 10 );

                if ( (!isset($found_shippings[$i])) ){
                    $dx_fail_iterator++;
                    $dx_fail_mes .= " State {$state_info["code"]} with weight = {$weight} \t ";
                }
            }

            $count_found_shippings = count($found_shippings);
            if ($count_found_shippings == "3") {
                $current_id = func_query_first_cell_param(/** @lang MySQL */
                    "SELECT manufacturerid FROM xcart_approximation_shipping_rates WHERE manufacturerid=:manufacturerid AND state=:state", ['manufacturerid' => $manufacturerid, 'state' => $state_info['code']]);
                if (empty($current_id)) {
                    db_query_param(/** @lang MySQL */
                        "INSERT INTO xcart_approximation_shipping_rates (manufacturerid, state, bw_1, bw_75, bw_150, last_updated_date) 
                                 VALUES (:manufacturerid, :state, :bw_1, :bw_75, :bw_150, :last_updated_date)",
                        [
                            'manufacturerid' => $manufacturerid,
                            'state' => $state_info['code'],
                            'bw_1' => $found_shippings[0]["rate"],
                            'bw_75' => $found_shippings[1]["rate"],
                            'bw_150' => $found_shippings[2]["rate"],
                            'last_updated_date' => time()
                        ]);
                } else {
                    db_query_param(/** @lang MySQL */
                        "UPDATE xcart_approximation_shipping_rates 
                              SET bw_1=:bw_1, bw_75=:bw_75, bw_150=:bw_150, last_updated_date=:last_updated_date
                            WHERE manufacturerid=:manufacturerid and state=:state ",
                        [
                            'bw_1' => $found_shippings[0]["rate"],
                            'bw_75' => $found_shippings[1]["rate"],
                            'bw_150' => $found_shippings[2]["rate"],
                            'last_updated_date' => time(),
                            'manufacturerid' => $manufacturerid,
                            'state' => $state_info['code'],
                        ]);
                }
            } else {
                $failed_requests = $failed_requests + (3 - $count_found_shippings);
                $failed_states .= $state_info["code"] . " (" . $state_info["base_state_zipcode"] . ") \n . dx_fail_message: \n{$dx_fail_mes}\n";
            }
            unset($found_shippings);
            db_query_param(/** @lang MySQL */
                "UPDATE xcart_manufacturers 
					  SET update_approximation_shipping_rates = 'N', shipping_rates_last_update_date = :shipping_rates_last_update_date 
					WHERE manufacturerid = :manufacturerid", ['manufacturerid' => $manufacturerid, 'shipping_rates_last_update_date' => time()]);

        }

        $list_of_updated_suppliers .= $manufacturer_info["manufacturer"] . "\n";

        if (!empty($failed_states)) {
            $list_of_updated_suppliers .= "Failed states: \n" . $failed_states . "\n\n" . "Failed requests count {$dx_fail_iterator} \n";
        }

        unset($failed_states);
    }

    $current_time = time();
    $diff_time_in_mins = ($current_time - $start_time) / 60;
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
