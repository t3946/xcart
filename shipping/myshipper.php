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
# $Id: myshipper.php,v 1.38.2.5 2006/12/21 08:04:43 svowl Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

include_once $xcart_dir."/shipping/shipping_cache.php";

#
# This function calculates shipping rates from my own shipper module
#
function func_shipper ($weight, $userinfo, $debug="N", $cart=false) {
	global $allowed_shipping_methods,$intershipper_rates;
	global $shipping_calc_service, $intershipper_error;
	global $sql_tbl;
	global $config;
	global $active_modules;
	global $xcart_dir;
	global $current_carrier;
	global $empty_other_carriers;

	$empty_other_carriers = "N";
	if (empty($userinfo) && ($config["General"]["apply_default_country"]=="Y" || $debug=="Y")) {
		$userinfo["s_country"] = $config["General"]["default_country"];
		$userinfo["s_state"] = $config["General"]["default_state"];
		$userinfo["s_zipcode"] = $config["General"]["default_zipcode"];
		$userinfo["s_city"] = $config["General"]["default_city"];

	} elseif (empty($userinfo)) {
		return array();
	}

	$allowed_shipping_methods = func_query("SELECT * FROM $sql_tbl[shipping] WHERE active = 'Y'");

	$intershipper_rates = array();

	$ups_rates_only = (!empty($active_modules["UPS_OnLine_Tools"]) && $current_carrier == "UPS");
	$ship_mods = array();
	$alt_ship_mods = array();
	
	$fedex_mod = 'FEDEX';

	if (!$ups_rates_only) {
		$ship_mods[] = $fedex_mod;
		$ship_mods[] = "AP";
	} else {
		$alt_ship_mods[] = $fedex_mod;
		$alt_ship_mods[] = "AP";
	}

	x_load('tests');

#
##
###
	$need_amazon_shipping_flag = func_need_amazon_shipping_flag($cart, $userinfo);
###
##
#

	#
	# Shipping modules depend on XML parser (EXPAT extension)
	#
	if (test_expat() != "") {
		if ($ups_rates_only) {
			$ship_mods[] = "UPS";

			$alt_ship_mods[] = "USPS";
			$alt_ship_mods[] = "CPC";
			$alt_ship_mods[] = "ARB";
			$alt_ship_mods[] = "DHL";
			
			if ($need_amazon_shipping_flag){
				$ship_mods[] = "Amazon";
				#$alt_ship_mods[] = "Amazon";
			}

		} else {
			$ship_mods[] = "USPS";
			$ship_mods[] = "CPC";
			$ship_mods[] = "ARB";
			$ship_mods[] = "DHL";

			if ($need_amazon_shipping_flag){
				$ship_mods[] = "Amazon";
			}
		}
	}

	foreach ($ship_mods as $ship_mod) {
		if (file_exists($xcart_dir."/shipping/mod_".$ship_mod.".php"))
			include_once $xcart_dir."/shipping/mod_".$ship_mod.".php";

		$func_ship = "func_shipper_".$ship_mod;
		if (function_exists($func_ship))
			$func_ship($weight, $userinfo, $debug, $cart);
	}

	if ($ups_rates_only) {
		$tmp_rates = $intershipper_rates;
		$intershipper_rates = array();
		foreach ($alt_ship_mods as $alt_ship_mod) {
			if (file_exists($xcart_dir."/shipping/mod_".$alt_ship_mod.".php"))
				include_once $xcart_dir."/shipping/mod_".$alt_ship_mod.".php";

			$func_ship = "func_shipper_".$alt_ship_mod;
			if (function_exists($func_ship))
				$func_ship($weight, $userinfo, $debug, $cart);
		}
		if (empty($intershipper_rates)) {
			$empty_other_carriers = "Y"; 
		}
		$intershipper_rates = $tmp_rates;
	}
	
	
	if ($debug == "Y") {
		func_shipper_show_rates($intershipper_rates);
	}

	return $intershipper_rates;
}

?>
