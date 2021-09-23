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
# $Id: func.php,v 1.1.2.10 2007/01/17 07:14:39 svowl Exp $
#
# Google checkout
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }


x_load("payment", "http", "xml");

#
# This function creates HMAC_SHA1 signature for $data by using $key
#
function hmac_sha1($data, $key) {
	$blocksize = 64;

	if (strlen($key) > $blocksize) {
		$key = pack('H*', sha1($key));
	}

	$key = str_pad($key, $blocksize, chr(0x00));
	$ipad = str_repeat(chr(0x36), $blocksize);
	$opad = str_repeat(chr(0x5c), $blocksize);
	$hmac = pack('H*', sha1(($key^$opad).pack('H*', sha1(($key^$ipad).$data))));

	return $hmac;
}

#
# This function prepares string $str for including into the XML request
#
function func_google_encode($str) {
	return str_replace(array("&", "<", ">"), array("&#x26;", "&#x3c;", "&#x3e;"), $str);
}

#
# This function checks if Google callback is valid
#
function func_gcheckout_is_valid_callback($ref) {
	global $sql_tbl;
	
	$refid = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[cc_pp3_data] WHERE ref = '$ref'");
	$goid = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[gcheckout_orders] WHERE goid = '$ref'");
	
	if ( $refid == 0 && $goid == 0) {
		x_log_flag('log_payment_processing_errors', 'PAYMENTS', "Google checkout payment module: Script called with a wrong Google order id: '$ref'", true);
		func_gcheckout_debug("\t+ [Error] Google checkout payment module: Script called with a wrong Google order id: '$ref'");
		exit;
	}

	return true;
}

#
# This function adds a message to log
#
function func_gcheckout_debug($message, $xml=false) {
	global $gcheckout_global_log, $gcheckout_global_xml_log;
	global $gcheckout_log_detailed_data;
	
	if (!defined('GCHECKOUT_DEBUG') || empty($message))
		return true;

	if (!$xml)
		$gcheckout_global_log .= $message . "\n";
	elseif ($gcheckout_log_detailed_data)
		$gcheckout_global_xml_log .= $message . "\n";

	return true;
}

#
# Save log to file (this function registered on 'script shutdown' event)
#
function func_gcheckout_save_log() {
	global $gcheckout_global_log, $gcheckout_global_xml_log;
	global $gcheckout_log_detailed_data;

	if (!defined('GCHECKOUT_DEBUG'))
		return true;

	if (!empty($gcheckout_global_log)) {

		list($_usec, $_sec) = explode(" ", constant("XCART_START_TIME"));
		list($_usec2, $_sec2) = explode(" ", microtime());

		$gcheckout_global_log .= "\t+ Running time (in seconds): " . (($_usec2 + $_sec2) - ($_usec + $_sec)) . "\n";

		if (GCHECKOUT_DEBUG != 1) {
			# Preparing for sending to e-mail
			$emails_array = explode(',', GCHECKOUT_DEBUG);
			x_log_add('gcheckout', $gcheckout_global_log, false, 0, $emails_array, true);
		}
		else
			x_log_add('gcheckout', $gcheckout_global_log);
	}

	if (!empty($gcheckout_global_xml_log)) {
		x_log_add('gcheckout_xml', $gcheckout_global_xml_log);
	}

	return true;
}

#
# This function is used by func_google_sort_tax_rates() for ordering tax rates
#
function func_google_sort_tax_rates($a, $b) {
	if ($a['zone_rating'] == $b['zone_rating'])
		return 0;

	return ($a['zone_rating'] < $b['zone_rating'] ? 1 : -1);
}

#
# This function gathers the taxes, rates and rate zones details
#
function func_gcheckout_get_taxes($cart) {
	global $sql_tbl, $single_mode, $config, $cart;
	
	static $_zones_cache = array();

	$products = $cart['products'];

	if (empty($products) || !is_array($products))
		return false;
	
	foreach ($products as $k => $p) {
		$productids[$p['productid']] = $p;
	}

	$_product_taxes = func_query_hash("SELECT $sql_tbl[taxes].*, $sql_tbl[product_taxes].productid FROM $sql_tbl[taxes], $sql_tbl[product_taxes], $sql_tbl[products] WHERE $sql_tbl[products].productid=$sql_tbl[product_taxes].productid AND $sql_tbl[taxes].taxid=$sql_tbl[product_taxes].taxid AND $sql_tbl[products].free_tax!='Y' AND $sql_tbl[product_taxes].productid IN ('".implode("','", array_keys($productids))."') AND $sql_tbl[taxes].active='Y' ORDER BY $sql_tbl[taxes].priority", "productid");
	
	if (empty($_product_taxes))
		return false;

	$taxes = array();
	$have_tax_rates = false;

	# This rating is used for ordering of the rates within tax-rules container
	$zone_element_rating = array(
		"C" => 1,
		"S" => 1000,
		"G" => 2000,
		"Z" => 3000,
		"A" => 4000
	);

	# Gather the tax rates details
	foreach ($_product_taxes as $productid => $_taxes) {

		if (isset($taxes[$_taxes[0]['tax_name']]))
			continue;
		
		$taxes[$_taxes[0]['tax_name']] = $_taxes[0];
		$rates = func_query("SELECT $sql_tbl[tax_rates].* FROM $sql_tbl[tax_rates] LEFT JOIN $sql_tbl[tax_rate_memberships] ON $sql_tbl[tax_rates].rateid=$sql_tbl[tax_rate_memberships].rateid WHERE $sql_tbl[tax_rates].taxid='{$_taxes[0]['taxid']}' AND ($sql_tbl[tax_rate_memberships].membershipid = '$membershipid' OR $sql_tbl[tax_rate_memberships].membershipid IS NULL)");

		$_tax_rates = array();

		if (is_array($rates)) {

			$have_tax_rates = true;
			$_tax_rate_tmp = array();

			# Gather the rate zones details
			$_total_rates = count($rates);

			for ($i = 0; $i < $_total_rates; $i++) {

				$_zone = array();
				$_zones = array();

				if (isset($_zones_cache[$rates[$i]['zoneid']])) {
					# Get zone details from cache
					$_zones = $_zones_cache[$rates[$i]['zoneid']];
				}
				else {
					# Gather zone details for tax rate
					$_zones_result = func_query("SELECT $sql_tbl[zone_element].* FROM $sql_tbl[zone_element] WHERE zoneid='{$rates[$i]['zoneid']}'");

					if (!empty($_zones_result))
						foreach ($_zones_result as $_current_zone)
							$_zone['zone'][$_current_zone['field_type']][] = $_current_zone['field'];

					$multiple_rate = array();

					if (!empty($_zone['zone']['Z']) && count($_zone['zone']['Z']) > 1)
						$multiple_rate['Z'] = $_zone['zone']['Z'];
					elseif (!empty($_zone['zone']['S']) && count($_zone['zone']['S']) > 1)
						$multiple_rate['S'] = $_zone['zone']['S'];

					if (!empty($multiple_rate)) {
						foreach ($multiple_rate as $k=>$_mzones) {
							$_zone_tmp = $_zone;
							foreach ($_mzones as $_mzone) {
								$_zone_tmp['zone'][$k] = array($_mzone);
								$_zones[] = $_zone_tmp;
							}
						}
					}
					else
						$_zones[] = $_zone;


					foreach ($_zones as $k=>$_zone) {
						if (empty($_zone)) continue;
						$_zones[$k]['zone_rating'] = 0;
						foreach ($_zone['zone'] as $_zone_type=>$_zone_arr)
							$_zones[$k]['zone_rating'] += $zone_element_rating[$_zone_type] * count($_zone_arr);
					}

					$_zones_cache[$_rate['zoneid']] = $_zones;

				}

				$_tmp_rate = array();
				$_tmp_rate = $rates[$i];

				foreach ($_zones as $_zone) {
					$_tmp_rate['zone'] = $_zone['zone'];
					$_tmp_rate['zone_rating'] = $_zone['zone_rating'];
					$taxes[$_taxes[0]['tax_name']]['rates'][] = $_tmp_rate;
				}

			} // for ($i = 0; $i < $_total_rates; $i++)

			usort($taxes[$_taxes[0]['tax_name']]['rates'], 'func_google_sort_tax_rates');
		}
	}

	if (!$have_tax_rates)
		return false;

	if (!$single_mode) {
		$taxes_pro = array();
		foreach ($taxes as $_tax_name => $_tax) {
			$_rates_tmp = array();
			foreach ($_tax['rates'] as $_rate)
				$_rates_tmp[$_tax_name.'_'.$_rate['provider']][] = $_rate;
			$taxes_pro[$_tax_name.'_'.$_rate['provider']] = $_tax;
			$taxes_pro[$_tax_name.'_'.$_rate['provider']]['rates'] = $_rates_tmp;
		}
		$taxes = $taxes_pro;
	}

	return $taxes;

}

#
# This function checks if Google Checkout button must be enabled or disabled
#
function func_is_gcheckout_button_enabled () {
	global $sql_tbl, $cart;

	$_restriction_found = 0;
		
	if (doubleval($cart['total_cost']) == 0)
		return false;

	if (!empty($cart['products']) && is_array($cart['products'])) {
		$_pid = array();
		foreach ($cart['products'] as $_product) {
			$_pid[] = $_product['productid'];
		}
		$_restriction_found = func_query_hash("SELECT productid, COUNT(*) as counter FROM $sql_tbl[gcheckout_restrictions] WHERE productid IN ('" . implode("','", $_pid) . "') GROUP BY productid", "productid", false);
		if (!empty($_restriction_found) && is_array($_restriction_found)) {
			foreach ($_restriction_found as $pid => $counter) {
				foreach ($cart['products'] as $k => $_product) {
					if ($_product['productid'] == $pid)
						$cart['products'][$k]['valid_for_gcheckout'] = 'N';
				}
			}
		}
	}

	return empty($_restriction_found);

}

#
# This function prepares and displays the 'notification-acknowledgment' XML code
#
function func_gcheckout_send_notification_acknowledgment() {
	$notification_acknowledgment_xml = <<<OUT
<?xml version="1.0" encoding="UTF-8"?>
<notification-acknowledgment xmlns="http://checkout.google.com/schema/2"/>
OUT;

	func_gcheckout_debug($notification_acknowledgment_xml, true);

	echo $notification_acknowledgment_xml;
}

#
# This function sends XML code to the Google Checkout server and parsed an answer
#
function func_gcheckout_send_xml($xml) {
	global $config, $gcheckout_xml_url;

	func_gcheckout_debug("*** URL:\n\n" . $gcheckout_xml_url . "\n\n", true);
	func_gcheckout_debug("*** XML REQUEST:\n\n" . $xml . "\n\n", true);

	$h = array( 
		"Authorization" => "Basic ".base64_encode($config['Google_Checkout']['gcheckout_mid'].":".$config['Google_Checkout']['gcheckout_mkey']),
		"Accept" => "application/xml"
	);  

	x_load("http", "xml");

	# Send XML request and parse result

	list($a, $return) = func_https_request("POST", $gcheckout_xml_url, array($xml), "", "", "application/xml", "", "", "", $h);

	func_gcheckout_debug("*** RESPONSE HEADERS:\n\n" . $a, true);
	func_gcheckout_debug("*** RESPONSE:\n\n" . $return, true);

	$parse_error = false;
	$options = array(
		'XML_OPTION_CASE_FOLDING' => 1,
		'XML_OPTION_TARGET_ENCODING' => 'ISO-8859-1'
	);

	$parsed = func_xml_parse($return, $parse_error, $options);

	return $parsed;
}

?>
