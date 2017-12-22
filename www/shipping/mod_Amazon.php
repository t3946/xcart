<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

function func_shipper_Amazon($weight, $userinfo, $debug, $cart) {
        global $config, $sql_tbl, $smarty, $active_modules, $products, $allowed_shipping_methods, $intershipper_rates;

//        global $ship_manufacturerid;
//        global $ship_manufacturerid_products;
//        $for_manufacturerid = $ship_manufacturerid;


        $AMAZON_FOUND = false;
        if (is_array($allowed_shipping_methods)) {
                foreach ($allowed_shipping_methods as $key=>$value) {
                        if ($value["code"] == "Amazon") {
                                $AMAZON_FOUND = true;
                                break;
                        }
                }
        }

        if (!$AMAZON_FOUND)
                return false;

//func_print_r($allowed_shipping_methods);

	$packages = array(
			'0' => array(
					'width' => '10',
					'height' => '10',
					'length' => '10',
					'price' => '10',
					'weight' => $weight,
				),
			);

	$_amazon_rates = func_amazon_get_shipping_rates($packages, $userinfo);

	$amazon_rates = array();
	if (!empty($_amazon_rates) && is_array($_amazon_rates)){
		foreach ($_amazon_rates as $k => $v){
                        foreach ($allowed_shipping_methods as $key=>$value) {
                        	if ($value['code'] == 'Amazon' && $value['subcode'] == $v['subcode']){

					$amazon_rate = array();
					$amazon_rate["methodid"] = $value['subcode'];
					$amazon_rate["rate"] = $v["rate"];
					$amazon_rate["shipping_time"] = $value["shipping_time"];

					$amazon_rates[] = $amazon_rate;
				}
                        }
		}
	}

        if (!empty($amazon_rates) && is_array($amazon_rates)){
                $tmp = array();
                foreach ($amazon_rates as $amazon_rate) {
                        if (!in_array($amazon_rate['methodid'], $tmp)) {
                                $tmp[] = $amazon_rate['methodid'];
                                $intershipper_rates[] = $amazon_rate;
                        }
                }
        }




//func_print_r($intershipper_rates);
//die("	qw");

}

?>
