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
if ($REQUEST_METHOD == "POST" && $mode == "requestaquote" && !empty($cart)) {

	$sf_info = func_get_storefront_info($cart['source_sf'], 'ID', true);
	$order_prefix = $sf_info['prefix'];

                $insert_data = array (
                        'order_prefix' => $order_prefix,
                        'login' => "rq".time(),
                        'total' => $cart["orders"][0]["subtotal"],
                        'subtotal' => $cart["orders"][0]["subtotal"],
                        'date' => time(),
                        'cb_status' => 'Q',
                        'dc_status' => 'T',
                        'bd_status' => 'W',
//                        'notes' => $email,
                        'customer_notes' => $email,
                        'firstname' => $s_firstname,
                        'company' => $company,
                        'b_firstname' => $s_firstname,
                        'b_address' => $s_address."\r\n".$s_address_2,
                        'b_city' => $s_city,
                        'b_state' => $s_state,
                        'b_country' => $s_country,
                        'b_zipcode' => $s_zipcode,
                        's_firstname' => $s_firstname,
                        's_address' => $s_address."\r\n".$s_address_2,
                        's_city' => $s_city,
                        's_state' => $s_state,
                        's_country' => $s_country,
                        's_zipcode' => $s_zipcode,
                        'phone' => $phone,
                        'phone_ext' => $phone_ext,
                        'email' => "helpdesk@s3stores.com",
                        'storefrontid' => $cart['source_sf'],
                );

                $new_orderid = func_array2insert('orders', $insert_data);

	        $shipping_groups_arr = array();
                foreach ($cart["products"] as $k => $v){
                	if (!isset($shipping_groups_arr[$v["manufacturerid"]]["sub_total"])){
                        	$shipping_groups_arr[$v["manufacturerid"]]["sub_total"] = 0;
                        }

                        $sub_total = $v["amount"]*$v["price"];
                        $shipping_groups_arr[$v["manufacturerid"]]["sub_total"] += $sub_total;
                }

                foreach ($shipping_groups_arr as $k => $v){

                                $insert_data2 = array (
                                        'orderid' => $new_orderid,
                                        'manufacturerid' => $k,
                                        'cb_status' => 'Q',
                                        'dc_status' => 'T',
                                        'bd_status' => 'W',
                                        'total_net' => $v['sub_total'],
                                        'total_gst' => $v['sub_total'],
                                        'total_pst' => $v['sub_total'],
                                        'total_gross' => $v['sub_total'],
                                );

                                func_array2insert('order_groups', $insert_data2);
                                unset($insert_data2);
                }

                foreach ($cart["products"] as $k => $v){

                                $insert_data3 = array (
                                        'orderid' => $new_orderid,
                                        'productid' => $v['productid'],
                                        'price' => $v['price'],
                                        'amount' => $v['amount'],
                                        'provider' => $v['provider'],
                                        'productcode' => $v['productcode'],
                                        'product' => addslashes($v['product'])
                                );

                                if (!empty($v['item_cost_to_us'])){
                                        $insert_data3['item_cost_to_us'] = $v['item_cost_to_us'];
                                }

                                func_array2insert('order_details', $insert_data3);
                                unset($insert_data3);
                }

	func_header_location("popup_requestaquote.php?mode=requested");
}
###
##
#

if ($short && $short == 'Y') {
	$short_phrase = $short;
}

if (empty($shipquote_userinfo) && !isset($update) && empty($mode))
	$shipquote_userinfo = (!empty($login))?func_userinfo($login, $current_area, false, false, "H"):array('usertype' => 'C');

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

func_display("customer/main/popup_requestaquote.tpl",$smarty);
?>
