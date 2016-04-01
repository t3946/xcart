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

	$query = "Select TRUNCATE(P.amazon_fba_avail * 0.8,0) - COALESCE(SUM(OD.amount- OD.back),0) As AvailOnFBA, P.manufacturerid,  P.productcode, P.productid, cidev_get_amazon_size_tier(P.productid) As SizeTier
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
//			if ($v["AvailOnFBA"] < $productid_amount[$v["productid"]] || strtolower($v["SizeTier"]) != "standart-size")
			if ($v["AvailOnFBA"] < $productid_amount[$v["productid"]] || strpos(strtolower($v["SizeTier"]),"standart-size")===false){
				$all_FBA_products_flag = false;
				break;
			}
		}
	} else {
		$all_FBA_products_flag = false;
	}

	return $all_FBA_products_flag;
}

function func_need_amazon_shipping_flag($cart){

	global $smarty;

	$need_amazon_shipping = false;

	$count_shipping_groups = count($cart["shipping_groups"]);

	if ($count_shipping_groups == "1"){

		$all_FBA_products_flag = func_amazon_all_FBA_products_flag($cart);

		if ($all_FBA_products_flag){
			$need_amazon_shipping = true;
		}
	}


#########################################################
//$need_amazon_shipping = true; //for test ONLY!!!!!!!
//func_print_r("TEST need_amazon_shipping: ".$need_amazon_shipping);
#########################################################

	$smarty->assign("need_amazon_shipping",$need_amazon_shipping);

	return $need_amazon_shipping;
}

function func_amazon_get_shipping_rates($packages, $userinfo){
	global $site_domain, $cart, $sql_tbl;


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

			if (!empty($EstimatedFees_xml_arr["EstimatedFees"]) && is_array($EstimatedFees_xml_arr["EstimatedFees"])){
				foreach ($EstimatedFees_xml_arr["EstimatedFees"] as $member => $v_member){
					$amazon_shipping_rate += $v_member["Amount"]["Value"];
				}
			}

			$amazon_shippings[$amazon_shipping] = $amazon_shipping_rate;
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

?>
