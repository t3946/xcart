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
# $Id: auth.php,v 1.40 2006/02/10 14:27:30 svowl Exp $
#

define('AREA_TYPE', 'A');

@include_once "./top.inc.php";
@include_once "../top.inc.php";
@include_once "../../top.inc.php";
if (!defined('DIR_CUSTOMER')) die("ERROR: Can not initiate application! Please check configuration.");

require_once $xcart_dir."/init.php";

x_load("backoffice");

x_session_register("login");
x_session_register("login_type");

x_session_register("logged");

x_session_register("export_ranges");

$smarty->assign("js_enabled", "Y");

x_session_register("top_message");
if (!empty($top_message)) {
	$smarty->assign("top_message", $top_message);
	if($config['Adaptives']['is_first_start'] != 'Y')
		$top_message = "";
	x_session_save("top_message");
}

$current_area="A";

include $xcart_dir."/include/get_language.php";

if (!empty($active_modules['Multiple_Storefronts'])) {
	include $xcart_dir . '/modules/Multiple_Storefronts/init.php';
}

$location = array();
$location[] = array(func_get_langvar_by_name("lbl_main_page"), "home.php");

@include $xcart_dir."/modules/gold_auth.php";
include $xcart_dir."/include/check_useraccount.php";

x_session_save();

# Create the user types list for search form
$usertypes = array("A"=>func_get_langvar_by_name("lbl_administrator"), "P"=>func_get_langvar_by_name("lbl_provider"), "C"=>func_get_langvar_by_name("lbl_customer"));
$usertypes['B'] = func_get_langvar_by_name("lbl_partner");

if (!empty($active_modules["Simple_Mode"])) {
	unset($usertypes["A"]);
	$usertypes["P"] = func_get_langvar_by_name("lbl_administrator");
}

$smarty->assign("redirect","admin");

if (!empty($active_modules["News_Management"]))
	include $xcart_dir."/modules/News_Management/news_last.php";

$statuses = func_query_hash('SELECT code, name, type'
    . ' FROM ' . $sql_tbl['order_statuses'] . ' ORDER BY orderby', array('type', 'code'), false, true);
$smarty->assign('statuses', $statuses);


#
##
###
$membership_name = func_query_first_cell("SELECT $sql_tbl[memberships].membership FROM $sql_tbl[memberships] LEFT JOIN $sql_tbl[customers] ON $sql_tbl[customers].membershipid=$sql_tbl[memberships].membershipid WHERE $sql_tbl[customers].login='$login'");
$membership_name = strtoupper(trim($membership_name));

if ($membership_name == "CUSTOMER SERVICE"){
	$membership_code = "ADMIN_CUSTOMER_SERVICE";
} elseif ($membership_name == "CUSTOMER SERVICE & PRODUCT MANAGER"){
	$membership_code = "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER";
} elseif ($membership_name == "PRODUCT MANAGER"){
	$membership_code = "ADMIN_PRODUCT_MANAGER";
}

$smarty->assign('membership_code', $membership_code);
//func_print_r($membership_code);
###
##
#

#
##
###
if (!empty($login)){
	$cidev_firstname = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$login'");
	$smarty->assign('cidev_firstname', $cidev_firstname);
}
###
##
#

#
##
###
$fraud_statuses = func_query_hash('SELECT code, name FROM ' . $sql_tbl['order_fraud_statuses'] . ' ORDER BY order_by', 'code', false, true);
$smarty->assign('fraud_statuses', $fraud_statuses);


$attention_tags_values = func_query_hash('SELECT status_id, status FROM ' . $sql_tbl['attention_tags_values'] . ' ORDER BY orderby', 'status_id', false, true);
$smarty->assign('attention_tags_values', $attention_tags_values);
###
##
#

$additional_shipping_statuses = array (
	"G" => "Pending",
	"D" => "Declined",
	"P" => "Paid"
);
$smarty->assign('additional_shipping_statuses', $additional_shipping_statuses);

#
##
###
if (!empty($orderid)){
	$tmp_productid = func_query_first_cell("SELECT productid FROM $sql_tbl[order_details] WHERE orderid='$orderid'");
	$product_sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$tmp_productid'");
	
	$order_storefront_info = func_get_storefront_info($product_sfid);

	$smarty->assign('order_storefront_info', $order_storefront_info);
	$mail_smarty->assign('order_storefront_info', $order_storefront_info);
//func_print_r($order_storefront_info);
}
###
##
#

?>
