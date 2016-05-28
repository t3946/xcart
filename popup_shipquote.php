<?php /* ADDED: random:17710_17631 [2009 Mar 26 09:25][Custom development ("Shipping quote" functionality and other modifications) + Other] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2009 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2009           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# popup_shipquote.php, random
#

require "./auth.php";
require $xcart_dir."/include/countries.php";
require $xcart_dir."/include/states.php";

x_load('user');

x_session_register("shipquote_userinfo");
x_session_register('short_phrase');


#
##
###
if ($REQUEST_METHOD == "POST" && !empty($shippingids)) {

        if (!empty($use_my_account) && is_array($use_my_account)){
                $cart["use_my_account"] = $use_my_account;
        }

        if (!empty($use_my_account_number) && is_array($use_my_account_number)){
                $cart["use_my_account_number"] = $use_my_account_number;
                foreach ($use_my_account_number as $k => $v){
                        if (empty($v)) {
				$tmp_ship_name = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE shippingid='".$shippingids[$k]."'");
				if ($tmp_ship_name == "_USE_MY_UPS_FEDEX_ACCOUNT_"){
	                                $top_message["content"] = 'Please fill in "account #" field.';
        	                        $top_message["type"] = "E";
                	                func_header_location("popup_shipquote.php?mode=shipping&shipping_error=Y");
				}
                        }
                }
        }

        if (!empty($ship_by_shipping_method) && is_array($ship_by_shipping_method)){
                $cart["ship_by_shipping_method"] = $ship_by_shipping_method;
                foreach ($ship_by_shipping_method as $k => $v){
                        if (empty($v)){
                                $tmp_ship_name = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE shippingid='".$shippingids[$k]."'");
                                if ($tmp_ship_name == "_USE_MY_UPS_FEDEX_ACCOUNT_"){
	                                $top_message["content"] = 'Please fill in "shipping method" field.';
        	                        $top_message["type"] = "E";
                	                func_header_location("popup_shipquote.php?mode=shipping&shipping_error=Y");
				}
                        }
                }
        }

        if (!empty($t_use_my_account_number) && is_array($t_use_my_account_number)){
                $cart["t_use_my_account_number"] = $t_use_my_account_number;
                foreach ($t_use_my_account_number as $k => $v){
                        if (empty($v)) {
                                $tmp_ship_name = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE shippingid='".$shippingids[$k]."'");
                                if ($tmp_ship_name == "_USE_MY_TRUCKING_ACCOUNT_"){
                                        $top_message["content"] = 'Please fill in "account #" field.';
                                        $top_message["type"] = "E";
                                        func_header_location("popup_shipquote.php?mode=shipping&shipping_error=Y");
                                }
                        }
                }
        }

        if (!empty($t_ship_by_shipping_method) && is_array($t_ship_by_shipping_method)){
                $cart["t_ship_by_shipping_method"] = $t_ship_by_shipping_method;
                foreach ($t_ship_by_shipping_method as $k => $v){
                        if (empty($v)){
                                $tmp_ship_name = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE shippingid='".$shippingids[$k]."'");
                                if ($tmp_ship_name == "_USE_MY_TRUCKING_ACCOUNT_"){
                                        $top_message["content"] = 'Please fill in "trucking company" field.';
                                        $top_message["type"] = "E";
                                        func_header_location("popup_shipquote.php?mode=shipping&shipping_error=Y");
                                }
                        }
                }
        }
}
###
##
#

if ($short && $short == 'Y') {
	$short_phrase = $short;
}

if (empty($shipquote_userinfo) && !isset($update) && empty($mode))
	$shipquote_userinfo = (!empty($login))?func_userinfo($login, $current_area, false, false, "H"):array('usertype' => 'C');

define('IS_SHIPPING_QUOTE', 1);

if ($REQUEST_METHOD == "POST" || $shipping_error == "Y") {

	if ($mode == 'checkout') {
?>
<script type="text/javascript">
<!--
if (window.opener)
	window.opener.location = 'cart.php?mode=checkout&toreg=1';
window.close();
-->
</script>
<?php
		exit;
	}

	x_session_register("cart");

	include $xcart_dir."/shipping/shipping.php";

	if (func_is_cart_empty($cart) || empty($cart["shipping_groups"]))	
		func_close_window();

			    
	if ($mode == 'shipping' || $mode == 'grandtotal') {
		
		if ($mode == 'shipping') {

			foreach (array('country','state','city','zipcode') as $key) {
		                if (isset($_POST['s_' . $key])) {
					$shipquote_userinfo['s_' . $key] = $_POST['s_' . $key];
                		}
	    	    }
	    	    /* added by Igor to help  Denis check shipping cost only with ZIP code & no state */
		    if (trim($shipquote_userinfo['s_state'])=='' && trim($shipquote_userinfo['s_zipcode']) != '' && trim($shipquote_userinfo['s_country']) == 'US')
			{
			    $tzip = $shipquote_userinfo['s_zipcode'];
			    $state_by_zip =  func_query_first_cell("SELECT Z.state FROM $sql_tbl[zip_code_info] Z WHERE Z.zip = '$tzip'");
			    $state_name = func_query_first_cell("SELECT S.state FROM $sql_tbl[states] S WHERE S.country_code = 'US' and S.code = '$state_by_zip'");
			    $shipquote_userinfo['s_state'] = $state_by_zip;
			    $shipquote_userinfo['s_statename'] = $state_name;
			    }
			    /*                  */

			$shipquote_userinfo['s_countryname'] = func_get_country($shipquote_userinfo['s_country']);
			$shipquote_userinfo['s_statename'] = func_get_state($shipquote_userinfo['s_state'], $shipquote_userinfo['s_country']);
		}

		$products = func_products_in_cart($cart, (!empty($shipquote_userinfo["membershipid"]) ? $shipquote_userinfo["membershipid"] : ""));

		$current_carrier = 'UPS';
		$_current_carrier = $current_carrier;

		$shipping_groups = $cart["shipping_groups"];
		$shippings = array();


#
##
###
$point_id = "1";
$variant_id = Get_AB_Variant($point_id);
$smarty->assign("variant_id", $variant_id);

//func_print_r($variant_id);
//AB_Goal_Hit(array($point_id));
###
##
#


		foreach ($shipping_groups as $k => $v) {
			$_products = array();
			foreach ($products as $v2) {	
//				if (($v2['shipping_freight'] != 0 && $v2['manufacturerid'] == $k) || ($k == $artss_manufacturerid && $v2['shipping_freight'] == 0)) {
				if ($k == $v2['manufacturerid']) {
					$_products[] = $v2;
				}
			}

			$intershipper_recalc = 'Y';
//			if ($k == $artss_manufacturerid) {
//				$current_carrier = '';
//				$_current_carrier ='';
//				$intershipper_recalc = '';
//			 } else {
				 $current_carrier = 'UPS';
				 $_current_carrier ='UPS';
				 $intershipper_recalc = 'Y';
//			 }
			 
			$shipping = func_get_shipping_methods_list($cart, $_products, $shipquote_userinfo, false, $k);
			
			$shippings[$k] = $shipping;

		} 

//func_print_r($shippings);

		if ($mode == 'grandtotal') {

			x_load('cart');
			$sq_cart = $cart;
			if (!empty($shippingids) && is_array($shippingids)) {
				$sq_cart["shippingids"] = $shippingids;
				foreach ($shippingids as $m_id => $sh_id) {
					$sq_cart['groups_delivery'][$m_id] = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE shippingid = '$sh_id'");
				}
			}	

		    $sq_cart = func_array_merge($sq_cart, func_calculate($sq_cart, $products, $login, $current_area, 0));
			$sq_products = func_products_in_cart($sq_cart, (!empty($shipquote_userinfo["membershipid"]) ? $shipquote_userinfo["membershipid"] : ""));

			$cart = $sq_cart;
//			$smarty->assign("cart", $sq_cart);
			$smarty->assign("products", $sq_products);

		}

		$smarty->assign("shipping_groups", $shipping_groups);
		$smarty->assign("shippings", $shippings);
		$smarty->assign("need_shipping", 'Y');
	}
}

#
##
###
if (func_is_cart_empty($cart) || empty($cart["shipping_groups"])){
        func_header_location("/");
}
###
##
#

$smarty->assign("cart", $cart);

$smarty->assign('short', $short_phrase);
$smarty->assign("mode", @$mode);
$smarty->assign("userinfo", $shipquote_userinfo);

$geo_litecity_location = func_get_geoip_locations($CLIENT_IP);
if (!empty($geo_litecity_location)) {
	$smarty->assign('geo_litecity_location', $geo_litecity_location);
}

func_display("customer/main/popup_shipquote.tpl",$smarty);
?>
