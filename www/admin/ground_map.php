<?php
//require "./auth.php";
require $xcart_dir."/include/security.php";

//x_load('order');

//require $xcart_dir."/include/history_order.php";
require $xcart_dir."/include/countries.php";
//func_print_r($countries);

//$order = $order_data["order"];
//$userinfo = $order_data["userinfo"];
//$userinfo["s_countryname_for_google"] = "+".str_replace(" ", "+", $userinfo["s_countryname"]);
//func_print_r($userinfo);
//$products = $order_data["products"];
//$giftcerts = $order_data["giftcerts"];

//$mnfs = func_get_order_manufacturers($orderid);

if (!empty($mnfs) && is_array($mnfs)){
    foreach ($mnfs as $k => $v){

	  foreach ($countries as $kc => $vc){
		if ($vc["country_code"] == $mnfs[$k]["m_country"]){
		  $mnfs[$k]["m_country_name"] = $vc["country"];
		  $mnfs[$k]["m_country_name_for_google"] = "+".str_replace(" ", "+", $mnfs[$k]["m_country_name"]);
		}
	  }

 	  $zip = $v["m_zipcode"];

	  if (empty($zip)){
		continue;
	  }

	  $map_url = func_query_first_cell("SELECT map_url FROM $sql_tbl[ground_map] WHERE zipcode='$zip'");

	  if (!empty($map_url)){
		$mnfs[$k]["map_url"] = $map_url;
		continue;
	  }

	  $full_server_url = "https://www.ups.com/maps/results";
	  $data = "loc=en_US&zip=$zip&stype=O";

          $curl_err = false;
          $ch = curl_init();
//          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//          curl_setopt($ch, CURLOPT_URL, $full_server_url);
//          curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);


		curl_setopt($ch, CURLOPT_URL, $full_server_url);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Content-Type: application/x-www-form-urlencoded"));
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//		curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $output = curl_exec($ch);

          if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                  $curl_err = true;
          }
          curl_close($ch);

//func_print_r($output);
//die();

          if (!$curl_err){

		$output = str_replace(array("\n", "\r"), ' ', $output);
		$output =  preg_replace('/ {2,}/',' ',$output);

	        $result = array();

        	preg_match_all('/src="\/using\/services\/servicemaps\/maps25\/map_(.*?)"/', $output, $result);

	        if (!empty($result[1])) {
        	        if (isset($result[1][0]) && !empty($result[1][0])){
                	        $map = trim($result[1][0]);
                        	if (!empty($map)){
					$map_url = "https://www.ups.com/using/services/servicemaps/maps25/map_".$map;
					db_query("INSERT INTO $sql_tbl[ground_map] (zipcode, map_url) VALUES ('$zip', '$map_url')");
					$mnfs[$k]["map_url"] = $map_url;
        	                }
                	}
	        }
	  }
    }
} 
//else {
//	func_header_location("order.php?orderid=$orderid");
//}

//func_print_r($mnfs);
//func_print_r($userinfo);

//$smarty->assign("order_manufacturers", $mnfs);
//$smarty->assign("products", $products);
//$smarty->assign("giftcerts", $giftcerts);
//$smarty->assign("userinfo", $userinfo);
//$smarty->assign("order", $order);

//$smarty->assign("main","ground_map");

//$location[2][1] = "order.php?orderid=".$orderid;
//$location[3][0] = "Ground map";

//$smarty->assign("location", $location);

//@include $xcart_dir."/modules/gold_display.php";
//func_display("admin/home.tpl",$smarty);
?>
