<?php
if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

/*
function func_amazon_shippings (){





}
*/

function func_amazon_all_FBA_products_flag($cart){

	if (!$cart){
		return false;
	}

	$productids = array();

	foreach ($cart["products"] as $k => $v){

		if (!isset($productid_amount[$v["productid"]])){
			$productid_amount[$v["productid"]] = 0;
		}

		$productid_amount[$v["productid"]] += $v["amount"];

		if (!in_array($v["productid"], $productids)){
			$productids[] = $v["productid"];
		}
	}

	$query = "Select TRUNCATE(cidev_get_amazon_FBA_cloned_stock(P.productid) * 0.8,0) - COALESCE(SUM(OD.amount- OD.back),0) As AvailOnFBA, P.manufacturerid,  P.productcode, P.productid
From xcart_order_groups OG
        left join xcart_orders O ON O.orderid = OG.orderid
        inner join xcart_products P ON P.productid  in ('".implode("','",$productids)."')
        left join xcart_order_details OD ON OD.productid = P.productid and OD.orderid = O.orderid
Where OG.cb_status IN ('IO','P','H','3','Q','N','O','AP') 
            and OG.dc_status IN ('B','M','T','K','DP','E','G')
            and FROM_UNIXTIME(O.date) > DATE_ADD(NOW(),INTERVAL -4 WEEK)
Group By P.productid";

	$result = func_query($query);

	if (!empty($result)){
		$all_FBA_products_flag = true;
		foreach ($result as $k => $v){
			if ($v["AvailOnFBA"] < $productid_amount[$v["productid"]]){
				$all_FBA_products_flag = false;
				break;
			}
		}
	} else {
		$all_FBA_products_flag = false;
	}

	return $all_FBA_products_flag;
}

function func_need_amazon_shipping_flag($cart, $userinfo){

	global $smarty, $sql_tbl;

	$need_amazon_shipping = false;

	$count_shipping_groups = count($cart["shipping_groups"]);

	if ($count_shipping_groups == "1"){

		$manufacturerid = $cart["products"][0]["manufacturerid"];

		$customer_zone = func_get_customer_zone_ship($userinfo, "master", "R", $manufacturerid);

		$count_rates = func_query_first_cell("SELECT COUNT($sql_tbl[shipping_rates].rateid) FROM $sql_tbl[shipping_rates] LEFT JOIN $sql_tbl[shipping] ON $sql_tbl[shipping].shippingid = $sql_tbl[shipping_rates].shippingid WHERE $sql_tbl[shipping].code='Amazon' AND $sql_tbl[shipping].active='Y' AND $sql_tbl[shipping_rates].manufacturerid='$manufacturerid' AND zoneid='$customer_zone'");

//func_print_r("count_rates:  ".$count_rates);
//die();

		if ($count_rates >= 1){
			$all_FBA_products_flag = func_amazon_all_FBA_products_flag($cart);

			if ($all_FBA_products_flag){
				$need_amazon_shipping = true;
			}

#########################################################
# $need_amazon_shipping = true; //for test ONLY!!!!!!!
# func_print_r("TEST need_amazon_shipping: ".$need_amazon_shipping);
#########################################################

		}
	}


#########################################################
//$need_amazon_shipping = true; //for test ONLY!!!!!!!
//func_print_r("TEST need_amazon_shipping: ".$need_amazon_shipping);
#########################################################

	$smarty->assign("need_amazon_shipping",$need_amazon_shipping);

	return $need_amazon_shipping;
}

function func_amazon_get_shipping_rates($packages, $userinfo, $data=''){
	global $site_domain, $cart, $sql_tbl;

	if (empty($data)){
		$url = "http://".$site_domain."/GetShippings.php";

		if($ch = curl_init($url)) 
		{ 

		        $data_arr["sid"] = "2376dthjdcbsjct67et23dfxafdgbhsdj08r67fija";
		        $data_arr["cart"] = $cart;
		        $data_arr["userinfo"] = $userinfo;

			$fields = http_build_query($data_arr);

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields))); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); 
			$data = curl_exec($ch);

			curl_close($ch); 

		} 
		else 
		{ 
			return false; 
		} 
	}

	if (!empty($data)){

		x_load("xml");

		$dom_xml = $data;


		$find = "<ShippingSpeedCategory>";
		$pos = strpos($dom_xml, $find);
		if ($pos === false) {
			return false;
		}

		$dom_xml_arr = explode($find, $dom_xml);
		unset($dom_xml_arr[0]);
		unset($find);

                $findme_arr = array("member");

		$amazon_shippings = array();

		foreach ($dom_xml_arr as $k_dom_xml_arr => $xml_str){

			$xml_str_arr = explode("</ShippingSpeedCategory>", $xml_str);
			$amazon_shipping = strtolower($xml_str_arr[0]);

			$amazon_shipping_rate = 0;


			$EstimatedFees_xml_match = preg_match('/<EstimatedFees>(.*?)<\/EstimatedFees>/is', $xml_str, $matches);
			$EstimatedFees_xml = $matches[1];


        	        foreach ($findme_arr as $findme){
                	        $pos = strpos($EstimatedFees_xml, "<$findme>");
                        	if ($pos !== "false"){
                                	$EstimatedFees_xml_arr = explode("<$findme>",$EstimatedFees_xml);
	                                $count_EstimatedFees_xml_arr = count($EstimatedFees_xml_arr);
        	                        $EstimatedFees_xml = "";
                	                foreach ($EstimatedFees_xml_arr as $k => $v){
                        	                $k_n = $k-1;
                                	        $v = str_replace("</$findme>","</$findme$k_n>",$v);
	                                        $EstimatedFees_xml .= $v.($k != ($count_EstimatedFees_xml_arr-1)?"<$findme$k>":"");
        	                        }
                	        }
	                }
			$EstimatedFees_xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?><EstimatedFees>$EstimatedFees_xml</EstimatedFees>
XML;

			$EstimatedFees_xml_arr = func_xml2hash($EstimatedFees_xml, "UTF-8");

			if (isset($EstimatedFees_xml_arr["EstimatedFees"])){
			    if (!empty($EstimatedFees_xml_arr["EstimatedFees"]) && is_array($EstimatedFees_xml_arr["EstimatedFees"])){
				foreach ($EstimatedFees_xml_arr["EstimatedFees"] as $member => $v_member){
					$amazon_shipping_rate += $v_member["Amount"]["Value"];
				}
			    }

			    $amazon_shippings[$amazon_shipping] = $amazon_shipping_rate;
			}
		}
	}


	if (!empty($amazon_shippings) && is_array($amazon_shippings)){

		$tmp_counter = 0;
		$amazon_rates = array();
		
		foreach ($amazon_shippings as $shipping => $rate){
			$subcode = func_query_first_cell("SELECT subcode FROM $sql_tbl[shipping] WHERE subcode IN (20001, 20003, 20005) AND shipping LIKE '%$shipping%'");
			if (!empty($subcode)){
				$amazon_rates[$tmp_counter]["subcode"] = $subcode;
				$amazon_rates[$tmp_counter]["rate"] = $rate;

				$tmp_counter++;
			}
		}
	}

//func_print_r($amazon_rates, $amazon_shippings);


/*
#
## for test purpose
###
	$amazon_rates[0]["subcode"] = 20001; // Standart L
	$amazon_rates[0]["rate"] = 1001.00;

        $amazon_rates[1]["subcode"] = 20002; // Standart I
        $amazon_rates[1]["rate"] = 1011.00;

###

        $amazon_rates[2]["subcode"] = 20003; // Expedited L
        $amazon_rates[2]["rate"] = 2002.00;

###

        $amazon_rates[3]["subcode"] = 20005; // Priority L
        $amazon_rates[3]["rate"] = 3003.00;
###
##
#
*/

	return $amazon_rates;
}

function func_get_amazon_shippings_for_all_states($product){
	global $xcart_states_US, $b_service, $sql_tbl, $config;

	$amazon_shippings_arr = array();

	if (empty($xcart_states_US)){
		return $amazon_shippings_arr;
	}


//func_print_r($product);

	$product["amount"] = $product["min_amount"];
        $cart["products"][0] = $product;
        $manufacturerid = $product["manufacturerid"];
	$total_shipping = price_format($product["amount"]*$product["price"]);
	$total_weight_shipping = price_format($product["amount"]*$product["weight"]);
	$total_ship_items = $product["amount"];


	$all_FBA_products_flag = func_amazon_all_FBA_products_flag($cart);




############################## FOR TEST PURPOSE ##############################
# func_print_r("---- all_FBA_products_flag = true ---");
# $all_FBA_products_flag = true;
############################## FOR TEST PURPOSE ##############################



	if (!$all_FBA_products_flag){
		return $amazon_shippings_arr;
	}


	if ($all_FBA_products_flag) {

		$avail_amazon_rates = array();
		$not_found_rates_for_state = array();

		$userinfo["s_firstname"] = "test";
		$userinfo["s_address"] = "test";

		foreach ($xcart_states_US as $k => $v) {

			$userinfo["s_country"] = $v["country_code"];
			$userinfo["s_state"] = $v["code"];
			$userinfo["s_zipcode"] = $v["base_state_zipcode"];
			$userinfo["s_city"] = $v["city"];

			$customer_zone = func_get_customer_zone_ship($userinfo, "master", "R", $manufacturerid);

			$shippingid_in_rates = func_query("SELECT $sql_tbl[shipping_rates].*, $sql_tbl[shipping].subcode, $sql_tbl[shipping].shipping FROM $sql_tbl[shipping_rates] LEFT JOIN $sql_tbl[shipping] ON $sql_tbl[shipping].shippingid = $sql_tbl[shipping_rates].shippingid WHERE $sql_tbl[shipping].code='Amazon' AND $sql_tbl[shipping].active='Y' AND $sql_tbl[shipping_rates].manufacturerid='$manufacturerid' AND zoneid='$customer_zone' AND mintotal<='$total_shipping' AND maxtotal>='$total_shipping' AND minweight<='$total_weight_shipping' AND maxweight>='$total_weight_shipping' AND type='R' ORDER BY maxtotal, maxweight");

			if (!empty($shippingid_in_rates)) {
				$count_rates = count($shippingid_in_rates);
			} else {
				$count_rates = 0;

				$not_found_rates_for_state[] = $v["code"];
			}

			if ($count_rates >= 1) {
				$amazon_rates = [];
				$iProductId = $cart["products"][0]["productid"];
				foreach ($shippingid_in_rates as $aShipping) {
					$aShippingRate = func_query_first("SELECT * FROM ".$sql_tbl['products_amazon_rates']." WHERE product_id = $iProductId AND shipping_id = ".$aShipping['shippingid']." AND state_id = ".$v['stateid']);
					if (!empty($aShippingRate)) {
						$oDate = new DateTime();
						$oDate->setTimestamp(strtotime($aShippingRate['last_update']));
						$iDaysInterval = $oDate->diff(new DateTime('now'))->days;
						if ($iDaysInterval <= $config["Froogle"]["froogle_days_cache_rates"]) {
							$amazon_rates[] = ['subcode'=>$aShippingRate['shipping_id'],'rate'=>$aShippingRate['rate']];
						}
					}
				}
				if (empty($amazon_rates)) {
					$request = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest();
					$request->setSellerId(MERCHANT_ID);

					$address = new FBAOutboundServiceMWS_Model_Address();
					$address->setName($userinfo["s_firstname"]);
					$address->setLine1($userinfo["s_address"]);
					$address->setCity($userinfo["s_city"]);
					$address->setCountryCode($userinfo["s_country"]);
					$address->setStateOrProvinceCode($userinfo["s_state"]);
					$address->setPostalCode($userinfo["s_zipcode"]);
					$request->setAddress($address);

					$items = array();
					foreach ($cart["products"] as $kp => $vp) {
						$item = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem();
						$item->setSellerSKU($vp["productcode"]);
						$item->setQuantity($vp["amount"]);
						$item->setSellerFulfillmentOrderItemId($vp["productcode"]);
						$items[] = $item;
					}

					$itemList = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList();
					$itemList->setmember($items);
					$request->setItems($itemList);

					$shippingArray = new FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList();
					$shippingArray->setmember(array("Standard", "Expedited", "Priority"));
					$request->setShippingSpeedCategories($shippingArray);

					$dom_xml = invokeGetFulfillmentPreview($b_service, $request);

					while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503") {

						func_flush("sleeping...");
						func_flush();
						sleep('123');
						func_flush("Unsleeped");
						func_flush();

						print("..invokeGetFulfillmentPreview throttle cycle\r\n");

						$request = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest();
						$request->setSellerId(MERCHANT_ID);

						$address = new FBAOutboundServiceMWS_Model_Address();
						$address->setName($userinfo["s_firstname"]);
						$address->setLine1($userinfo["s_address"]);
						$address->setCity($userinfo["s_city"]);
						$address->setCountryCode($userinfo["s_country"]);
						$address->setStateOrProvinceCode($userinfo["s_state"]);
						$address->setPostalCode($userinfo["s_zipcode"]);
						$request->setAddress($address);

						$items = array();
						foreach ($cart["products"] as $kp => $vp) {
							$item = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem();
							$item->setSellerSKU($vp["productcode"]);
							$item->setQuantity($vp["amount"]);
							$item->setSellerFulfillmentOrderItemId($vp["productcode"]);
							$items[] = $item;
						}

						$itemList = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList();
						$itemList->setmember($items);
						$request->setItems($itemList);

						$shippingArray = new FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList();
						$shippingArray->setmember(array("Standard", "Expedited", "Priority"));
						$request->setShippingSpeedCategories($shippingArray);

						$dom_xml = invokeGetFulfillmentPreview($b_service, $request);
					}

					if (empty($dom_xml["saveXML"])) {
						continue;
					}

					$amazon_rates = func_amazon_get_shipping_rates(false, false, $dom_xml["saveXML"]);
					foreach ($amazon_rates as $aRates) {
						$aInsertArray = ['product_id'=>$vp['productid'], 'shipping_id'=>$aRates['subcode'], 'state_id'=>$v['stateid'], 'rate'=>$aRates['rate']];
						func_array2insert('products_amazon_rates',$aInsertArray, true);
					}
				}

				if (empty($amazon_rates)) {
					continue;
				}

				$tmp_amazon_rates_counter = 0;
				foreach ($amazon_rates as $k_a => $v_a) {
					foreach ($shippingid_in_rates as $kk_a => $vv_a) {
						if ($v_a["subcode"] == $vv_a["subcode"]) {
							$shipping_cost = $v_a['rate'];

							if ($shipping_cost > 0) {

								if ($vv_a['cost_marcup'] > 0) {
									$shipping_cost *= $vv_a['cost_marcup'];
								}

								$shipping_cost += $vv_a['rate']
										+ $total_weight_shipping * $vv_a['weight_rate']
										+ $total_ship_items * $vv_a['item_rate']
										+ $total_shipping * $vv_a['rate_p'] / 100;
							}

							$shipping_cost += $product['shipping_freight'];

							$avail_amazon_rates[$k][$tmp_amazon_rates_counter] = $v_a;
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["s_country"] = $userinfo["s_country"];
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["s_state"] = $userinfo["s_state"];
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["s_zipcode"] = $userinfo["s_zipcode"];
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["s_city"] = $userinfo["s_city"];
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["shipping"] = $vv_a["shipping"];
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["shippingid"] = $vv_a["shippingid"];
							$avail_amazon_rates[$k][$tmp_amazon_rates_counter]["shipping_cost"] = price_format($shipping_cost);

							$tmp_amazon_rates_counter++;
						}
					}
				}

				func_flush(".");
				func_flush();

			} // if ($count_rates >= 1)
		} // foreach ($states_info as $k => $v)
	} // $all_FBA_products_flag



	if (!empty($avail_amazon_rates)){

		$shippings_str_arr = array();
		$shippings_google_arr = array();

                $key_counter = 0;

                foreach ($avail_amazon_rates as $k => $v){
                  if (!empty($v) && is_array($v)){
                    foreach ($v as $kk => $vv){
                      if (strpos(strtolower($vv["shipping"]),"standard")!==false){
                        $shippings_str_arr[$key_counter] = "US:".$vv["s_state"].":".$vv["shipping"].":".$vv["shipping_cost"]."USD";

                        $shippings_google_arr[$key_counter]["price"]["value"] = $vv["shipping_cost"];
                        $shippings_google_arr[$key_counter]["price"]["currency"] = "USD";
                        $shippings_google_arr[$key_counter]["country"] = "US";
                        $shippings_google_arr[$key_counter]["region"] = $vv["s_state"];
                        $shippings_google_arr[$key_counter]["service"] = $vv["shipping"];

                        $key_counter++;

                      }
                    }
                  }
                }

		if (!empty($shippings_str_arr) && !empty($shippings_google_arr)){

			$shippings_str = implode(",", $shippings_str_arr);

			$amazon_shippings_arr["shippings_str"] = $shippings_str;
			$amazon_shippings_arr["shippings_google_arr"] = $shippings_google_arr;

			if (!empty($not_found_rates_for_state)){
				$amazon_shippings_arr["not_found_rates_for_state"] = $not_found_rates_for_state;
			}
		}
	}

//func_print_r($avail_amazon_rates, $shippings_str_arr, $shippings_google_arr);

//func_print_r($amazon_shippings_arr);

	return $amazon_shippings_arr;
}

?>
