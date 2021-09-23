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
# $Id: func.php,v 1.5.2.7 2007/01/15 08:18:26 twice Exp $
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

function func_usps_parce_result($result, $xml_head, $type) {
	
	$response['type'] = $type;
	$response['error'] = false;
	$res = func_xml2hash($result);
	
	if ($res['Error']) {
		$response['error'] = true;
		$response['type'] = "txt";
		$response['data'] = $res['Error']['Description'];
		$response['error_code'] = $res['Error']['Number'];
		
		return $response;
	} 
	if ($res[$xml_head.'Response']) {
		if (!empty($res[$xml_head.'Response']['DeliveryConfirmationLabel'])) {
			$response['data'] = base64_decode(str_replace(array("\n"), array(""), $res[$xml_head.'Response']['DeliveryConfirmationLabel']));
		} elseif (!empty($res[$xml_head.'Response']['LabelImage'])) {
			$response['data'] = base64_decode(str_replace(array("\n"), array(""), $res[$xml_head.'Response']['LabelImage']));
		} elseif (!empty($res[$xml_head.'Response']['EMConfirmationNumber']) && $type == 'txt') {
			$response['data'] = $res[$xml_head.'Response']['EMConfirmationNumber'];
		}
	}

	return $response;
}

function func_usps_save_response($data, $method, $num) {
	global $xcart_dir;
	
	if (!is_dir("$xcart_dir/var/tmp/usps_test_labels/")) {
		if (!func_mkdir("$xcart_dir/var/tmp/usps_test_labels/")) {
			return false;
		}
	}
	
	$fp = fopen("$xcart_dir/var/tmp/usps_test_labels/usps_$method($num).".$data['type'], "w");
	if (!$fp) {
		return false;
	}
	fputs($fp, $data['data']);
	fclose($fp);

	return true;
}

#
# Check shippingid:
#	1. Is it U.S.P.S shippingid?
#	2. Is it valid shippingid?
#
function func_usps_check_shippingid($shippingid) {
	global $sql_tbl;

	$shipping = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE code = 'USPS' AND shippingid = '".$shippingid."'");
	if(empty($shipping))
		return false;

	$service_type = false;
	switch ($shipping['shipping']) {
		case 'USPS Express Mail':
			$service_type = 'ExpressMail';
			break;
		case 'USPS Global Express Mail (EMS)':
		case 'USPS Global Express Guaranteed Non-Document Service':
		case 'USPS Global Express Guaranteed Document Service':
			$service_type = 'GlobalExpress';
			break;
		case 'USPS Global Priority Mail - Flat-rate Envelope (Small)':
		case 'USPS Global Priority Small service':
		case 'USPS Global Priority Mail - Variable Weight (Single)':
			$service_type = 'GlobalPriority';
			break;
		case 'USPS Global AirMail Parcel':
		case 'USPS Airmail Letter Post':
			$service_type = 'GlobalAir';
		break;
		case 'USPS Priority Mail':
			$service_type = 'Priority';
			break;
		case 'USPS First Class':
		case 'USPS First-Class Mail':
			$service_type = 'First Class';
			break;
		case 'USPS Parcel Post':
		case 'USPS Economy (Surface) Parcel Post':
		case 'USPS Airmail Parcel Post':
			$service_type = 'Parcel Post';
			break;

		case 'USPS BPM':
			$service_type = 'Bound Printed Matter';
			break;

		case 'USPS Bound Printed Matter':
			$service_type = 'Bound Printed Matter';
			break;
		case 'USPS Media':
			$service_type = 'Media Mail';
			break;

		case 'USPS Library':
			$service_type = 'Library Mail';
			break;
		case 'USPS Library Mail':
			$service_type = 'Library Mail';
			break;
		default: 
			$service_type = "Error";
			break;
	}

	return $service_type;
}

#
# Check shippingid:
#	1. Is it UPS shippingid?
#	2. Is it valid shippingid?
#
function func_ups_check_shippingid($shippingid) {
	global $sql_tbl;

	$shipping = func_query_first("SELECT * FROM $sql_tbl[shipping] WHERE code = 'UPS' AND shippingid = '".$shippingid."'");
	if(empty($shipping))
		return false;

	$service_type = false;
	switch ($shipping['shipping']) {
		case 'UPS Ground':
			$service_type = 'Ground';
			break;
		case 'UPS 3 Day Select##SM##':
			$service_type = '3 Day Select';
			break;
		case 'UPS 2nd Day Air##R##':
			$service_type = '2nd Day Air';
			break;
		case 'UPS 2nd Day Air A.M.##R##':
			$service_type = '2nd Day Air AM';
			break;
		case 'UPS Next Day Air Saver##R##':
			$service_type = 'Next Day Air Saver';
			break;
		case 'UPS Next Day Air##R##':
			$service_type = 'Next Day Air';
			break;
		case 'UPS Next Day Air##R## Early A.M.##R##':
			$service_type = 'Next Day Air Early AM';
			break;
		case 'UPS Worldwide Express Plus##SM##':
			$service_type = 'Worlwide Express Plus';
			break;
		case 'UPS Worldwide Express##SM##':
			$service_type = 'Worlwide Express';
			break;
		case 'UPS Worldwide Expedited##SM##':
			$service_type = 'Worlwide Expedited';
			break;
	}

	return $service_type;
}

#
# Get module name by shippingid
#
function func_get_shipping_module($shippingid) {
	global $sql_tbl, $slg_modules;

	if(empty($shippingid))
		return false;

	$code = func_query_first_cell("SELECT code FROM $sql_tbl[shipping] WHERE shippingid = '$shippingid'");
	if(!empty($slg_modules[$code])) {
		return $slg_modules[$code];
	}
	return false;
}

#
# Detect UPS sevice type
#
function func_ups_service_type($order) {
	global $sql_tbl;

	if(empty($order['order']['shippingid']))
		return false;
	$shipping = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE code = 'UPS' AND shippingid = '".$order['order']['shippingid']."'");
	if(empty($shipping))
		return false;

	$str = false;
	if(strpos($shipping, "UPS Next Day Air A.M.") === 0 || (strpos($shipping, "UPS Next Day Air") === 0 && strpos($shipping, "Early A.M.") !== false)) {
		$str = "Next Day Air Early AM";
	} elseif(strpos($shipping, "UPS Next Day Air Saver") === 0) {
		$str = "Next Day Air Saver";
	} elseif(strpos($shipping, "UPS Next Day Air") === 0) {
		$str = "Next Day Air";
	} elseif(strpos($shipping, "UPS 2nd Day Air A.M.") === 0) {
		$str = "2nd Day Air AM";
	} elseif(strpos($shipping, "UPS 2nd Day Air") === 0) {
		$str = "2nd Day Air";
	} elseif(strpos($shipping, "UPS 3 Day Select") === 0) {
		$str = "3 Day Select";
	} elseif(strpos($shipping, "UPS Ground") === 0) {
		$str = "Ground";
	}
	return $str;
}
?>
