<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: shipping_rates.php,v 1.43.2.1 2006/04/24 11:13:31 svowl Exp $
#

require "./auth.php";
require $xcart_dir."/include/security.php";

if ($config["Shipping"]["disable_shipping"] == "Y") {
	func_header_location("error_message.php?shipping_disabled");
}

#
# This value is used as a default top range value
# for weight and order subtotal ranges (used in Smarty template)
#
$maxvalue = 999999.99;


#
# Shipping rates - D (defined rates)
# Shipping markups - R (for realtime methods only)
#
if ($type != "R") {
	$type = "D";
	$location[] = array(func_get_langvar_by_name("lbl_shipping_charges"), "");
}
else {
	if ($config["Shipping"]["realtime_shipping"] != "Y")
		func_header_location("error_message.php?realtime_shipping_disabled");

	$location[] = array(func_get_langvar_by_name("lbl_shipping_markups"), "");
}

$type_condition = " AND type='$type'";

$provider_condition=($single_mode?"":"AND provider='$login'");

if ($REQUEST_METHOD=="POST") {

	if ($mode == "delete") {
		#
		# Delete shipping option
		#
		if (is_array($posted_data)) {
			$deleted = false;
			foreach ($posted_data as $rateid=>$v) {
				if (empty($v["to_delete"]))
					continue;

				db_query("DELETE FROM $sql_tbl[shipping_rates] WHERE rateid='$rateid' $provider_condition $type_condition");
				$deleted = true;
			}

			if ($deleted)
				$top_message["content"] = func_get_langvar_by_name("msg_shipping_rates_del");
		}
	}
	
	if ($mode == "update") {
		#
		# Update shipping table
		#
		if (is_array($posted_data)) {
			foreach ($posted_data as $rateid=>$v) {
				func_array2update("shipping_rates", 
					array(
						"minweight" => func_convert_number($v['minweight']),
						"maxweight" => func_convert_number($v['maxweight']),
						"mintotal" => func_convert_number($v['mintotal']),
						"maxtotal" => func_convert_number($v['maxtotal']),
						"rate" => func_convert_number($v['rate']),
						"item_rate" => func_convert_number($v['item_rate']),
						"rate_p" => func_convert_number($v['rate_p']),
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
						"weight_rate" => func_convert_number($v['weight_rate']),
						"cost_marcup" => func_convert_number($v['cost_marcup'])
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
					),
					"rateid='$rateid' $provider_condition $type_condition"
				);
			}

			$top_message["content"] = func_get_langvar_by_name("msg_shipping_rates_upd");
		}
	}

	if ($mode == "add") {
		#
		# Add new shipping rate
		#
		if ($shippingid_new) {
			func_array2insert("shipping_rates", 
				array(
					"shippingid" => $shippingid_new,
					"minweight" => func_convert_number($minweight_new),
					"maxweight" => func_convert_number($maxweight_new),
					"maxamount" => func_convert_number($maxamount_new),
					"mintotal" => func_convert_number($mintotal_new),
					"maxtotal" => func_convert_number($maxtotal_new),
					"rate" => func_convert_number($rate_new),
					"item_rate" => func_convert_number($item_rate_new),
					"rate_p" => func_convert_number($rate_p_new),
					"weight_rate" => func_convert_number($weight_rate_new),
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
					"cost_marcup" => func_convert_number($cost_marcup_new),
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
					"provider" => $login,
					"zoneid" => $zoneid_new,
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
					"manufacturerid" => $manufacturerid_new,
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
					"type" => $type
				)
			);
			$top_message["content"] = func_get_langvar_by_name("msg_shipping_rate_add");
		}
	}

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	func_header_location("shipping_rates.php?zoneid=$zoneid&shippingid=$shippingid&type=$type&manufacturerid=$manufacturerid");
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
}

$zone_condition = ($zoneid!=""?"and $sql_tbl[shipping_rates].zoneid='$zoneid'":"");
$method_condition = ($shippingid!=""?"and $sql_tbl[shipping_rates].shippingid='$shippingid'":"");
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 

	if ($type=="R"){
		$manufacturerid = (!isset($manufacturerid)) ? '-1' : $manufacturerid;
	} else {
		$manufacturerid = (!isset($manufacturerid)) ? '0' : $manufacturerid;
	}

$manufacturer_condition = "and $sql_tbl[shipping_rates].manufacturerid='$manufacturerid'";
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

$realtime_condition = ($config["Shipping"]["realtime_shipping"]=="Y"?"and $sql_tbl[shipping].code=''":"");

if ($active_modules["UPS_OnLine_Tools"] && $config["Shipping"]["use_intershipper"] != "Y") {
	include $xcart_dir."/modules/UPS_OnLine_Tools/ups_shipping_methods.php";
	$ups_condition = $condition;
}
else {
	$ups_condition = "";
}

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
$shipping_rates = func_query($qqq="SELECT $sql_tbl[shipping_rates].*, $sql_tbl[shipping].shipping, $sql_tbl[shipping].shipping_time, $sql_tbl[shipping].destination FROM $sql_tbl[shipping], $sql_tbl[shipping_rates] WHERE $sql_tbl[shipping_rates].shippingid=$sql_tbl[shipping].shippingid AND $sql_tbl[shipping].active='Y' $provider_condition $type_condition $zone_condition $method_condition $manufacturer_condition ".($type=="R"?" AND code!='' ":$realtime_condition)." ORDER BY $sql_tbl[shipping].orderby, $sql_tbl[shipping_rates].maxweight");
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

//func_print_r($shipping_rates, $qqq);


#
# Prepare zones list
#
$zones = array(array("zoneid"=>0,"zone"=>func_get_langvar_by_name("lbl_zone_default")));
$_tmp = func_query("SELECT zoneid, zone_name as zone FROM $sql_tbl[zones] WHERE 1 $provider_condition ORDER BY zoneid");
if (!empty($_tmp))
	$zones = func_array_merge($zones,$_tmp);

if (is_array($zones) && is_array($shipping_rates)) {
	foreach ($zones as $zone) {
		$shipping_rates_list = array();
		foreach ($shipping_rates as $shipping_rate) {
			if ($shipping_rate["zoneid"] != $zone["zoneid"])
				continue;

			$shipping_rates_list[$shipping_rate["shippingid"]]["shipping"] = $shipping_rate["shipping"];
			$shipping_rates_list[$shipping_rate["shippingid"]]["destination"] = $shipping_rate["destination"];
			$shipping_rates_list[$shipping_rate["shippingid"]]["rates"][] = $shipping_rate;

		}

		$_zones_list = array();
		$_zones_list["zone"] = $zone;
		$_zones_list["shipping_methods"] = $shipping_rates_list;
		$zones_list[] = $_zones_list;
	}
}

if ($type == "R") {
	$markup_condition .= " AND code!=''";

	$shipping = func_query("SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $markup_condition ORDER BY orderby");
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
	$manufacturers = func_query("SELECT * FROM $sql_tbl[manufacturers] WHERE avail = 'Y' ORDER BY orderby, manufacturer");

	$smarty->assign("manufacturers", $manufacturers);


# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 


#
##
###
	$cidev_marckup_nums = '5';
	$cidev_marckups_less_than_n = func_query("SELECT COUNT(*) as count, manufacturerid FROM $sql_tbl[shipping_rates] WHERE provider='$login' $type_condition GROUP BY manufacturerid HAVING count < '$cidev_marckup_nums' ORDER BY count DESC");

	if (!empty($cidev_marckups_less_than_n) && is_array($cidev_marckups_less_than_n)){

		foreach ($cidev_marckups_less_than_n as $k => $v){

			$tmp_manufacturer = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$v[manufacturerid]'");

			if (empty($tmp_manufacturer)){
				unset($cidev_marckups_less_than_n[$k]);
				continue;
			}

			$cidev_marckups_less_than_n[$k]["manufacturer"] = $tmp_manufacturer;
		}

		$cidev_marckups_less_than_n = array_values($cidev_marckups_less_than_n);

//x_load("debug");
//func_print_r($cidev_marckups_less_than_n);
	}


	$all_marckups = func_query("SELECT DISTINCT(manufacturerid) FROM $sql_tbl[shipping_rates] WHERE provider='$login' $type_condition");

	$all_marckups_arr = array();
	if (!empty($all_marckups) && is_array($all_marckups)){
		foreach ($all_marckups as $k => $v){
			$all_marckups_arr[] = $v["manufacturerid"];
		}
	}

	$cidev_manufacturers = func_query("SELECT manufacturer, manufacturerid FROM $sql_tbl[manufacturers] WHERE manufacturerid NOT IN ('".implode("','", $all_marckups_arr)."') AND avail='Y'");

	if (!empty($cidev_manufacturers) && is_array($cidev_manufacturers)){
		$cidev_count = count($cidev_marckups_less_than_n);
		foreach($cidev_manufacturers as $k => $v){
			$cidev_marckups_less_than_n[$cidev_count]["manufacturer"] = $v["manufacturer"];
			$cidev_marckups_less_than_n[$cidev_count]["manufacturerid"] = $v["manufacturerid"];
			$cidev_marckups_less_than_n[$cidev_count]["count"] = 0;
			$cidev_count++;
		}
	}

        $smarty->assign("cidev_marckups_less_than_n", $cidev_marckups_less_than_n);
        $smarty->assign("cidev_marckup_nums", $cidev_marckup_nums);

//x_load("debug");
//func_print_r($cidev_manufacturers, $all_marckups_arr);

###
##
#
}
else {
        $shipping = func_query("SELECT * FROM $sql_tbl[shipping] WHERE active='Y' $realtime_condition $ups_condition ORDER BY orderby");
}


//func_print_r($shipping);

$smarty->assign("shipping", $shipping);

$smarty->assign("zones", $zones);
$smarty->assign("shipping_rates", $shipping_rates);
$smarty->assign("shipping_rates_avail", (is_array($shipping_rates) ? count($shipping_rates) : 0));
$smarty->assign("zones_list", $zones_list);
$smarty->assign("type", $type);
$smarty->assign("zoneid", $zoneid);
$smarty->assign("shippingid", $shippingid);
$smarty->assign("maxvalue", $maxvalue);
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
$smarty->assign("manufacturerid", $manufacturerid);
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
$smarty->assign("main","shipping_rates");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("provider/home.tpl",$smarty);
?>
