<?php
if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

function func_amazon_shippings (){





}

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
Where OG.cb_status IN ('IO','P','H','3','Q','N','O') 
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

	$all_FBA_products_flag = func_amazon_all_FBA_products_flag($cart);

	if ($all_FBA_products_flag){
		$need_amazon_shipping = true;
	}



#########################################################
//$need_amazon_shipping = true; //for test ONLY!!!!!!!
//func_print_r("TEST need_amazon_shipping: ".$need_amazon_shipping);
#########################################################

	$smarty->assign("need_amazon_shipping",$need_amazon_shipping);

	return $need_amazon_shipping;
}

function func_amazon_get_shipping_rates($packages, $userinfo){

	$amazon_rates = array();



#
##
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

	return $amazon_rates;
}

?>
