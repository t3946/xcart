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
if (!empty($login)){
	$membership_name = func_query_first_cell("SELECT $sql_tbl[memberships].membership FROM $sql_tbl[memberships] LEFT JOIN $sql_tbl[customers] ON $sql_tbl[customers].membershipid=$sql_tbl[memberships].membershipid WHERE $sql_tbl[customers].login='$login'");

	$membership_name = strtoupper(trim($membership_name));

	if ($membership_name == "CUSTOMER SERVICE"){
		$membership_code = "ADMIN_CUSTOMER_SERVICE";
	} elseif ($membership_name == "CUSTOMER SERVICE & PRODUCT MANAGER"){
		$membership_code = "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER";
	} elseif ($membership_name == "PRODUCT MANAGER"){
		$membership_code = "ADMIN_PRODUCT_MANAGER";
	} elseif ($membership_name == "TRACKING NUMBER ENTRY OPERATOR"){
        	$membership_code = "ADMIN_TRACKING_NUMBER_ENTRY_OPERATOR";
	}

	$smarty->assign('membership_code', $membership_code);
//func_print_r($membership_code);



// New customization
	$all_memberships = func_query("SELECT * FROM $sql_tbl[memberships] WHERE area IN ('A','P') ORDER BY area, membership");
	$operator_info = func_query_first("SELECT allow_operate_as_membership, membershipid FROM $sql_tbl[customers] WHERE $sql_tbl[customers].login='$login'");

	if (!empty($operator_info["allow_operate_as_membership"])){
		$allow_operate_as_membership_arr = explode(",", $operator_info["allow_operate_as_membership"]);
		foreach ($allow_operate_as_membership_arr as $k => $v){
			$allow_operate_as_membership_arr[$k] = trim($v);
		}
	} else {
		$allow_operate_as_membership_arr = array();
	}

	$operator_info["allow_operate_as_membership_arr"] = $allow_operate_as_membership_arr;



	$order_page_permissions = func_query("SELECT * FROM $sql_tbl[order_page_permissions]");
	$allowed_elements = array();
	if (!empty($order_page_permissions)){
        	foreach ($order_page_permissions as $k => $v){
                	$membership_ids_arr = explode(",", $v["membership_ids"]);

	                $membership_ids_arr[] = 0; // for super admin

        	        if (in_array($operator_info["membershipid"], $membership_ids_arr)){
                	        $allowed_to_use = "Y";
	                } else {

				$allowed_to_use = "N";

				if (!empty($allow_operate_as_membership_arr)){
					foreach ($allow_operate_as_membership_arr as $kk => $vv){
						if (in_array($vv, $membership_ids_arr)){
							$allowed_to_use = "Y";
							break;
						}
					}
				}
                	}

	                $allowed_elements[$v["element_id"]] = $allowed_to_use;
        	}
	}
	$smarty->assign("allowed_elements", $allowed_elements);


//func_print_r($all_memberships, $operator_info, $allowed_elements);

	$smarty->assign('all_memberships', $all_memberships);
	$smarty->assign('operator_info', $operator_info);
}
###
##
#

#
##
###
if (!empty($login)){
	$userfullname = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$login'");
	$smarty->assign('cidev_firstname', $userfullname);

	$userfirstname_arr = explode(" ", $userfullname);
	$userfirstname = array_shift($userfirstname_arr);
	$smarty->assign('userfirstname', $userfirstname);

//func_print_r($userfirstname);

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
	"A" => "Authorized",
//	"P" => "Paid",
	"W" => "Waive",
	"U" => "Unwaive"
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

$product_question_statuses = array(
        "question_received_from_cust" => "Question received from customer",
        "question_sent_to_distr_brand" => "Question sent to distributor/brand",
        "call_distributor_brand" => "Call distributor/brand",
        "answer_sent_to_cust" => "Answer sent to customer",
        "order_pending" => "Order pending",
        "closed" => "Closed"
);
$smarty->assign('product_question_statuses', $product_question_statuses);

$publication_statuses = array(
        "U" => "Unpublished",
        "N" => "Not suitable for publication",
        "T" => "Transferred to product page"
);

$smarty->assign('publication_statuses', $publication_statuses);

###   
    $all_storefronts = $storefronts;
    $storefronts_0[0] = func_get_storefront_info(0, 'ID');
    $all_storefronts = array_merge($storefronts_0, $all_storefronts);
    $smarty->assign('all_storefronts', $all_storefronts);

//func_print_r($all_storefronts);
###

$invoice_memo_statuses = array(
	"N" => "Not received",
	"A" => "Added",
	"U" => "Updated",
	"R" => "Reconciled"
);
$smarty->assign('invoice_memo_statuses', $invoice_memo_statuses);

#
##
###
if (empty($_SERVER["HTTPS"]) && strpos($xcart_http_host, '.test.') === false){
        $redirect_https_link = "https://".$xcart_http_host.$PHP_SELF . (($QUERY_STRING) ? ('?' . $QUERY_STRING) : '');
        func_header_location($redirect_https_link);
}
###
##
#


?>
