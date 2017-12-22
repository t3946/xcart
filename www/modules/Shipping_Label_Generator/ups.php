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
# $Id: ups.php,v 1.6.2.4 2007/01/15 08:18:26 twice Exp $
#
# UPS - WordShip/TrueShip module
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

if(empty($order) || empty($order['products']))
	return false;

$stype = func_ups_check_shippingid($order['order']['shippingid']);
if(!$stype)
	return false;

$delimiter = ',';

$hash = array();
$p_head = array();
$strs = array();

$hash['orderID'] = $order['order']['orderid'];
$hash['s_company'] = $order['userinfo']['company'];
$hash['name'] = $order['userinfo']['s_firstname']." ".$order['userinfo']['s_lastname'];
$hash['s_email'] = $order['userinfo']['email'];
$hash['s_phone'] = $order['userinfo']['phone'];
$hash['s_address'] = $order['userinfo']['s_address'];
$hash['s_city'] = $order['userinfo']['s_city'];
$hash['s_state'] = $order['userinfo']['s_state'];
$hash['s_zip'] = $order['userinfo']['s_zipcode'];
$hash['s_country'] = $order['userinfo']['s_country'];
$hash['shipmethod'] = $stype;
$hash['insuredvalue'] = $order['order']['total'];
$hash['weight'] = 0;
if (in_array($hash['s_country'], array("DO","PR","US"))) {
	$UPS_wunit = "LBS";
} else {
	$UPS_wunit = "KGS";
}
if(!empty($order['products'])) {
	foreach($order['products'] as $p) {
		$hash['weight'] += $p['weight']*$p['amount'];
	}
	 $hash['weight'] = max(0.1,round(func_weight_in_grams($hash['weight'])/($UPS_wunit=="LBS"?453.6:1000),1));
}
$strs[] = implode($delimiter,$hash);

# Create header
$header = implode($delimiter,func_array_merge(array_keys($hash), $p_head));

# Create response
$response = array(
	"result" => 'ok',
	"image" => $header."\n".implode("\n", $strs),
	"image_type" => "text/csv"
);

if ($is_first_ups_label) {
	$all_ups_shipping_labels["result"] = 'ok';
	$all_ups_shipping_labels["image"] = $header."\n".implode("\n", $strs);
	$all_ups_shipping_labels["image_type"] = 'text/csv';
	$is_first_ups_label = false;
} else {
	$all_ups_shipping_labels["image"] .= "\n".implode("\n", $strs);
}
?>
