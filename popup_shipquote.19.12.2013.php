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
if ($REQUEST_METHOD == "POST") {

        if (!empty($use_my_account) && is_array($use_my_account)){
                $cart["use_my_account"] = $use_my_account;
        }

        if (!empty($use_my_account_number) && is_array($use_my_account_number)){
                $cart["use_my_account_number"] = $use_my_account_number;
        }

        if (!empty($ship_by_shipping_method) && is_array($ship_by_shipping_method)){
                $cart["ship_by_shipping_method"] = $ship_by_shipping_method;
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

if ($REQUEST_METHOD == "POST") {

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

			$shipquote_userinfo['s_countryname'] = func_get_country($shipquote_userinfo['s_country']);
			$shipquote_userinfo['s_statename'] = func_get_state($shipquote_userinfo['s_state'], $shipquote_userinfo['s_country']);
		}

		$products = func_products_in_cart($cart, (!empty($shipquote_userinfo["membershipid"]) ? $shipquote_userinfo["membershipid"] : ""));

		$current_carrier = 'UPS';
		$_current_carrier = $current_carrier;

		$shipping_groups = $cart["shipping_groups"];
		$shippings = array();

		foreach ($shipping_groups as $k => $v) {
			$_products = array();
			foreach ($products as $v2) {	
				if (($v2['shipping_freight'] != 0 && $v2['manufacturerid'] == $k) || ($k == $artss_manufacturerid && $v2['shipping_freight'] == 0)) {
					$_products[] = $v2;
				}
			}

			$intershipper_recalc = 'Y';
			if ($k == $artss_manufacturerid) {
				$current_carrier = '';
				$_current_carrier ='';
				$intershipper_recalc = '';
			 } else {
				 $current_carrier = 'UPS';
				 $_current_carrier ='UPS';
				 $intershipper_recalc = 'Y';
			 }
			 
			$shipping = func_get_shipping_methods_list($cart, $_products, $shipquote_userinfo, false, $k);
			$shippings[$k] = $shipping;

		} 

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

$smarty->assign("cart", $cart);

$smarty->assign('short', $short_phrase);
$smarty->assign("mode", @$mode);
$smarty->assign("userinfo", $shipquote_userinfo);



#
##
###
//if (empty($login)){
  if (!empty($CLIENT_IP)){
        $CLIENT_IP_arr = explode(".", $CLIENT_IP);
        if (!empty($CLIENT_IP_arr) && is_array($CLIENT_IP_arr)){
                $CLIENT_IP_INTEGER = $CLIENT_IP_arr[0]*16777216 + $CLIENT_IP_arr[1]*65536 + $CLIENT_IP_arr[2]*256 + $CLIENT_IP_arr[3];
        }

        if (!empty($CLIENT_IP_INTEGER)){
                $locId = func_query_first_cell("SELECT locId FROM $sql_tbl[geo_litecity_blocks] WHERE $CLIENT_IP_INTEGER BETWEEN startIpNum AND endIpNum LIMIT 1");

//$locId = "1087";

                if (!empty($locId)){
                        $geo_litecity_location = func_query_first("SELECT * FROM $sql_tbl[geo_litecity_location] WHERE locId='".addslashes($locId)."'");

                        if (!empty($geo_litecity_location)){
                                $smarty->assign('geo_litecity_location', $geo_litecity_location);
                        }
                }
        }
  }
//}  
###
##
#

func_display("customer/main/popup_shipquote.tpl",$smarty);
?>
