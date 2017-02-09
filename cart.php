<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (����� ��� �������� ����������� "��������������" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
<?php /* MODIFIED: random:17710_17631 [2009 Mar 26 09:25][Custom development ("Shipping quote" functionality and other modifications) + Other] */ ?>
<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
<?php
/*****************************************************************************\
 * +-----------------------------------------------------------------------------+
 * | X-Cart                                                                      |
 * | Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
 * | All rights reserved.                                                        |
 * +-----------------------------------------------------------------------------+
 * | PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
 * | FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
 * | AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
 * |                                                                             |
 * | THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
 * | THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
 * | FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
 * | AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
 * | PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
 * | CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
 * | COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
 * | (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
 * | LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
 * | AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
 * | OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
 * | AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
 * | THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
 * | THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
 * |                                                                             |
 * | The Initial Developer of the Original Code is Ruslan R. Fazliev             |
 * | Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
 * | Ruslan R. Fazliev. All Rights Reserved.                                     |
 * +-----------------------------------------------------------------------------+
 * \*****************************************************************************/

use Xcart\CidevSurfPath;
require "./auth.php";

if (!empty($active_modules['Wishlist'])) {
    if ($mode == 'add2wl' || $mode == "wishlist") {
        require $xcart_dir . "/include/remember_user.php";

    } elseif (!empty($login) && !empty($remember_data) && ($mode == 'add2wl' || $mode == "wishlist" || $mode == 'add')) {
        require $xcart_dir . "/include/remember_user.php";
    }
}

x_load('cart', 'user', 'order');
global $purchase_order_selected;
x_session_register('purchase_order_selected');
if (!empty($purchase_order_selected)) {
    $smarty->assign('purchase_order_selected', $purchase_order_selected);
}
if (!empty($orderids) && $_GET["mode"] == "order_message") {

    $orders = array();

    $_orderids = explode(",", $orderids);
    foreach ($_orderids as $orderid) {
        $order_data = func_order_data($orderid);
        $order_data["products"] = func_translate_products($order_data["products"], $shop_language);
        $orders[] = $order_data;
    }


    if ($order_data["order"]["quantity_decreased"] == "N") {
        func_decrease_quantity($order_data["products"]);
        $order_data["order"]["quantity_decreased"] = "Y";
        db_query("UPDATE $sql_tbl[orders] SET quantity_decreased='Y' WHERE orderid='$orderid'");
    }

###
//	if ($order_data["order"]["paymentid"] != "4"){
    # 4 - Phone Ordering
    AB_Goal_Hit(array("1", "2", "3", "4", "5", "6", "9", "10", "11"), $orders[0]["order"]["orderid"]);
//	}
###

    $tmp_current_storefront_info = func_get_storefront_info($current_storefront, 'ID', true);
    $smarty->assign("current_storefront_info", $tmp_current_storefront_info);

    $GTS_order_confirmation_module_code = "";
    if (!empty($config["Appearance"]["Google_Trusted_Store_ID"])) {
        $GTS_order_confirmation_module_code = str_replace('GTS_STORE_ID', $config["Appearance"]["Google_Trusted_Store_ID"], $config["Google_trusted_stores_options"]["GTS_order_confirmation_module_code"]);
        $ITEM_GOOGLE_SHOPPING_ACCOUNT_ID = func_query_first_cell("SELECT MerchantID FROM $sql_tbl[froogle_options] WHERE storefrontid='$current_storefront'");
        $GTS_order_confirmation_module_code = str_replace('MERCHANT_ORDER_ID', $orders[0]["order"]["order_prefix"] . $orders[0]["order"]["orderid"], $GTS_order_confirmation_module_code);
//		$tmp_current_storefront_info = func_get_storefront_info($current_storefront, 'ID');
        $GTS_order_confirmation_module_code = str_replace('MERCHANT_ORDER_DOMAIN', $tmp_current_storefront_info["domain"], $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('CUSTOMER_EMAIL', $orders[0]["order"]["email"], $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('CUSTOMER_COUNTRY', $orders[0]["order"]["b_country"], $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('CURRENCY', "USD", $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('ORDER_TOTAL', $orders[0]["order"]["total"] - $orders[0]["order"]["tax"], $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('ORDER_DISCOUNTS', "0.00", $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('ORDER_SHIPPING', $orders[0]["order"]["shipping_cost"], $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('ORDER_TAX', "0.00", $GTS_order_confirmation_module_code);

        $ORDER_EST_SHIP_DATE = date("Y-m-d", $orders[0]["order"]["date"] + 60 * 60 * 24 * 2);
        $GTS_order_confirmation_module_code = str_replace('ORDER_EST_SHIP_DATE', $ORDER_EST_SHIP_DATE, $GTS_order_confirmation_module_code);


        $ORDER_EST_DELIVERY_DATE = $orders[0]["order"]["date"] + 60 * 60 * 24 * 2;
        $max_day_in_shipping = 0;
        if (!empty($orders[0]["order"]["shipping_groups"]) && is_array($orders[0]["order"]["shipping_groups"])) {
            foreach ($orders[0]["order"]["shipping_groups"] as $k => $v) {
                $last_day_in_shipping_str = func_query_first_cell("SELECT shipping_time FROM $sql_tbl[shipping] WHERE shippingid='$v[shippingid]'");
                $last_day_in_shipping_str = str_replace('to', '-', $last_day_in_shipping_str);
                $last_day_in_shipping_str_arr = explode('-', $last_day_in_shipping_str);
                $k_index_last = count($last_day_in_shipping_str_arr) - 1;
                $last_day_in_shipping = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $last_day_in_shipping_str_arr[$k_index_last]);
                $last_day_in_shipping = intval($last_day_in_shipping);

                if ($max_day_in_shipping < $last_day_in_shipping) {
                    $max_day_in_shipping = $last_day_in_shipping;
                }
            }
        }
        $ORDER_EST_DELIVERY_DATE += 60 * 60 * 24 * $max_day_in_shipping;

        $tmp_number_of_day_of_week = date("w", $ORDER_EST_DELIVERY_DATE);// 0 (for Sunday) through 6 (for Saturday)
        if ($tmp_number_of_day_of_week == "0") {
            $ORDER_EST_DELIVERY_DATE += 60 * 60 * 24 * 1;
        } elseif ($tmp_number_of_day_of_week == "6") {
            $ORDER_EST_DELIVERY_DATE += 60 * 60 * 24 * 2;
        }

        $date_mm_dd_yyyy = date("m/d/Y", $ORDER_EST_DELIVERY_DATE);
        $is_date_mm_dd_yyyy = func_query_first_cell("SELECT date_mm_dd_yyyy FROM $sql_tbl[request_availability_options] WHERE date_mm_dd_yyyy='$date_mm_dd_yyyy'");
        if (!empty($is_date_mm_dd_yyyy)) {
            $ORDER_EST_DELIVERY_DATE += 60 * 60 * 24 * 1;
        }

        $tmp_number_of_day_of_week = date("w", $ORDER_EST_DELIVERY_DATE);// 0 (for Sunday) through 6 (for Saturday)
        if ($tmp_number_of_day_of_week == "0") {
            $ORDER_EST_DELIVERY_DATE += 60 * 60 * 24 * 1;
        } elseif ($tmp_number_of_day_of_week == "6") {
            $ORDER_EST_DELIVERY_DATE += 60 * 60 * 24 * 2;
        }

        $ORDER_EST_DELIVERY_DATE = date("Y-m-d", $ORDER_EST_DELIVERY_DATE);
        $GTS_order_confirmation_module_code = str_replace('ORDER_EST_DELIVERY_DATE', $ORDER_EST_DELIVERY_DATE, $GTS_order_confirmation_module_code);


        $GTS_order_confirmation_module_code = str_replace('HAS_BACKORDER_PREORDER', "N", $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code = str_replace('HAS_DIGITAL_GOODS', "N", $GTS_order_confirmation_module_code);


        $GTS_order_confirmation_module_code_arr = explode('<span class="gts-item">', $GTS_order_confirmation_module_code);
        $GTS_order_confirmation_module_code1 = $GTS_order_confirmation_module_code_arr[0];
        $GTS_order_confirmation_module_code_arr_2 = explode("</span>", $GTS_order_confirmation_module_code_arr[1]);
        array_pop($GTS_order_confirmation_module_code_arr_2);
        $GTS_order_confirmation_module_code2 = implode("</span>", $GTS_order_confirmation_module_code_arr_2);
        $GTS_order_confirmation_module_code2 = '<span class="gts-item">' . $GTS_order_confirmation_module_code2 . '</span>';
        $GTS_order_confirmation_module_code3 = '</div>';
    }

    // $tmp_subtotal = $orders[0]["order"]["total"] - $orders[0]["order"]["tax"] - $orders[0]["order"]["shipping_cost"];
    $tmp_total = $orders[0]["order"]["total"];
    $smarty->assign("order_subtotal", $tmp_total);

    $cidev_tracking_code_add = "";

    $cidev_tracking_code_add .= "_gaq.push(['_addTrans', \r\n";
    $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["order_prefix"] . $orders[0]["order"]["orderid"] . "',    // transaction ID \r\n";

    $cidev_tracking_code_add .= "\t'" . $config["Company"]["company_name"] . "',       // affiliation or store name \r\n";

    $tmp_subtotal = $orders[0]["order"]["total"] - $orders[0]["order"]["tax"] - $orders[0]["order"]["shipping_cost"];
    $tmp_subtotal = price_format($tmp_subtotal);

    $cidev_tracking_code_add .= "\t'" . $tmp_subtotal . "', // total - required. Does not include Tax and Shipping \r\n";
    $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["tax"] . "',    // tax \r\n";
    $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["shipping_cost"] . "',  // shipping \r\n";
    $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["s_city"] . "', // city \r\n";
    $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["s_state"] . "',        // state or province \r\n";
    $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["s_country"] . "'       // country \r\n";
    $cidev_tracking_code_add .= "]); \r\n";

    if (!empty($orders[0]["products"]) && is_array($orders[0]["products"])) {
        foreach ($orders[0]["products"] as $k => $v) {

            $GTS_order_confirmation_module_code2_arr[$k] = $GTS_order_confirmation_module_code2;
            $GTS_order_confirmation_module_code2_arr[$k] = str_replace('ITEM_NAME', $v["product"], $GTS_order_confirmation_module_code2_arr[$k]);
            $GTS_order_confirmation_module_code2_arr[$k] = str_replace('ITEM_PRICE', $v["price"], $GTS_order_confirmation_module_code2_arr[$k]);
            $GTS_order_confirmation_module_code2_arr[$k] = str_replace('ITEM_QUANTITY', $v["amount"], $GTS_order_confirmation_module_code2_arr[$k]);
            $GTS_order_confirmation_module_code2_arr[$k] = str_replace('ITEM_GOOGLE_SHOPPING_ID', $v["productid"], $GTS_order_confirmation_module_code2_arr[$k]);
            $GTS_order_confirmation_module_code2_arr[$k] = str_replace('ITEM_GOOGLE_SHOPPING_ACCOUNT_ID', $ITEM_GOOGLE_SHOPPING_ACCOUNT_ID, $GTS_order_confirmation_module_code2_arr[$k]);

            $cidev_tracking_code_add .= "_gaq.push(['_addItem',\r\n";
            $cidev_tracking_code_add .= "\t'" . $orders[0]["order"]["order_prefix"] . $orders[0]["order"]["orderid"] . "',    // transaction ID to associate item with transaction \r\n";
            $cidev_tracking_code_add .= "\t'" . $v["productcode"] . "',       // SKU - required \r\n";
            $cidev_tracking_code_add .= "\t'" . addslashes($v["product"]) . "',       // product name \r\n";
            $cidev_tracking_code_add .= "\t'',      // category or variation \r\n";
            $cidev_tracking_code_add .= "\t'" . $v["price"] . "',     // unit price - required \r\n";
            $cidev_tracking_code_add .= "\t'" . $v["amount"] . "'     // quantity - required \r\n";
            $cidev_tracking_code_add .= "]); \r\n";
        }
    }

    $GTS_order_confirmation_module_code = $GTS_order_confirmation_module_code1 . implode(" ", $GTS_order_confirmation_module_code2_arr) . $GTS_order_confirmation_module_code3;
    $smarty->assign("GTS_order_confirmation_module_code", $GTS_order_confirmation_module_code);

//func_print_r($GTS_order_confirmation_module_code);


    $cidev_tracking_code_add .= "_gaq.push(['_trackTrans']);";

    $smarty->assign("cidev_tracking_code_add", $cidev_tracking_code_add);


    $tmp_subtotal = $orders[0]["order"]["total"] - $orders[0]["order"]["tax"];
    $tmp_subtotal = price_format($tmp_subtotal);

    $cidev_tracking_code_add2 = '<script type="text/javascript">' . "\r\n";
    $cidev_tracking_code_add2 .= "var yaParams = { \r\n";
    $cidev_tracking_code_add2 .= 'order_id: "' . $orders[0]["order"]["order_prefix"] . $orders[0]["order"]["orderid"] . '",' . " \r\n";
    $cidev_tracking_code_add2 .= "order_price: " . $tmp_subtotal . ", \r\n";
    $cidev_tracking_code_add2 .= 'currency: "USD",' . " \r\n";
    $cidev_tracking_code_add2 .= "exchange_rate: 1, \r\n";
    $cidev_tracking_code_add2 .= "goods: \r\n";
    $cidev_tracking_code_add2 .= "[ \r\n";

    if (!empty($orders[0]["products"]) && is_array($orders[0]["products"])) {

        foreach ($orders[0]["products"] as $k => $v) {
            $cidev_tracking_code_add2 .= "\t{ \r\n";
            $cidev_tracking_code_add2 .= "\t\t" . 'id:"' . $v["productcode"] . '",' . " \r\n";
            $cidev_tracking_code_add2 .= "\t\t" . 'name:"' . addslashes($v["product"]) . '",' . " \r\n";
            $cidev_tracking_code_add2 .= "\t\tprice: " . $v["price"] . ", \r\n";
            $cidev_tracking_code_add2 .= "\t\tquantity: " . $v["amount"] . " \r\n";
            $cidev_tracking_code_add2 .= "\t} \r\n";

            if ($k != (count($orders[0]["products"]) - 1)) {
                $cidev_tracking_code_add2 .= "\t, \r\n";
            }
        }
    }

    $cidev_tracking_code_add2 .= "] \r\n";
    $cidev_tracking_code_add2 .= "}; \r\n";
    $cidev_tracking_code_add2 .= "</script>";

    $smarty->assign("cidev_tracking_code_add2", $cidev_tracking_code_add2);

    $cidev_tracking_code_modified = str_replace('var cidev_tracking_code_add;', $cidev_tracking_code_add, $config["Company"]["cidev_tracking_code"]);
    $cidev_tracking_code_modified = str_replace('var cidev_tracking_code_add2;', $cidev_tracking_code_add2, $cidev_tracking_code_modified);
    $smarty->assign("cidev_tracking_code_modified", $cidev_tracking_code_modified);
    x_session_unregister('customer_notes');
}

require $xcart_dir . "/include/cart_process.php";
include $xcart_dir . "/shipping/shipping.php";

x_session_register("cart");
x_session_register("intershipper_rates");
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
x_session_register("intershipper_rates_all");
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
x_session_register("intershipper_recalc");
x_session_unregister("secure_oid");
x_session_register("anonymous_checkout");
x_session_register("payment_cc_fields");
x_session_register("current_carrier", "UPS");
x_session_register("arb_account_used");
x_session_register("airborne_account");
x_session_register("order_secureid");
x_session_register("is_sns_action");
x_session_register("cart_locked");
x_session_register("dhl_ext_country_store");
x_session_register('last_categoryid');
x_session_register('catalog_checkboxes', array());
x_session_register('added_catalogs');
x_session_register('autologout');
x_session_register("customer_notes");

if (!empty($cart["products"]) && empty($cart["cart_number"])) {

    $current_user_cart_number_in_xcart_sessions_data = func_query_first_cell("SELECT cart_number FROM $sql_tbl[sessions_data] WHERE sessid='$XCARTSESSID'");
    if (!empty($current_user_cart_number_in_xcart_sessions_data)) {
        $cart["cart_number"] = $current_user_cart_number_in_xcart_sessions_data;
    } else {

        $max_cart_number_in_xcart_customers = func_query_first_cell("SELECT MAX(cart_number) FROM $sql_tbl[customers]");
        $max_cart_number_in_xcart_sessions_data = func_query_first_cell("SELECT MAX(cart_number) FROM $sql_tbl[sessions_data]");
        $max_cart_number_in_xcart_orders = func_query_first_cell("SELECT MAX(cart_number) FROM $sql_tbl[orders]");

        $cart_number = max($max_cart_number_in_xcart_customers, $max_cart_number_in_xcart_sessions_data, $max_cart_number_in_xcart_orders) + 1;
        $cart["cart_number"] = $cart_number;

        db_query("UPDATE $sql_tbl[sessions_data] SET cart_number='$cart_number' WHERE sessid='$XCARTSESSID'");
    }
}

x_session_register("e_search_data");
if ($top_btn == "Y") {
    $e_search_data["substring"] = "";
    x_session_save("e_search_data");
}

if ($REQUEST_METHOD == "POST" && $mode == "checkout" && $cart_operation == "cart_operation" && $action == "update" && isset($confirmation_of_responsibility)) {
    $cart["confirmation_of_responsibility"] = $confirmation_of_responsibility;
}


if ($REQUEST_METHOD == "POST" && !empty($shippingids) && !empty($cart["groups_delivery"])) {

    if (!empty($use_my_account) && is_array($use_my_account)) {
        $cart["use_my_account"] = $use_my_account;
    }

    if (!empty($use_my_account_number) && is_array($use_my_account_number)) {
        $cart["use_my_account_number"] = $use_my_account_number;
        foreach ($use_my_account_number as $k => $v) {
            $v = trim($v);
            if ((empty($v) || $v == "") && $cart["groups_delivery"][$k] == "_USE_MY_UPS_FEDEX_ACCOUNT_") {
                $top_message["content"] = 'Please fill in "account #" field.';
                $top_message["type"] = "E";
                func_header_location("cart.php?mode=checkout&shipping_error=Y");
            }
        }
    }

    if (!empty($ship_by_shipping_method) && is_array($ship_by_shipping_method)) {
        $cart["ship_by_shipping_method"] = $ship_by_shipping_method;
        foreach ($ship_by_shipping_method as $k => $v) {
            $v = trim($v);
            if ((empty($v) || $v == "") && $cart["groups_delivery"][$k] == "_USE_MY_UPS_FEDEX_ACCOUNT_") {
                $top_message["content"] = 'Please fill in "shipping method" field.';
                $top_message["type"] = "E";
                func_header_location("cart.php?mode=checkout&shipping_error=Y");
            }
        }
    }

    if (!empty($t_use_my_account_number) && is_array($t_use_my_account_number)) {
        $cart["t_use_my_account_number"] = $t_use_my_account_number;
        foreach ($t_use_my_account_number as $k => $v) {
            $v = trim($v);
            if ((empty($v) || $v == "") && $cart["groups_delivery"][$k] == "_USE_MY_TRUCKING_ACCOUNT_") {
                $top_message["content"] = 'Please fill in "account #" field.';
                $top_message["type"] = "E";
                func_header_location("cart.php?mode=checkout&shipping_error=Y");
            }
        }
    }

    if (!empty($t_ship_by_shipping_method) && is_array($t_ship_by_shipping_method)) {
        $cart["t_ship_by_shipping_method"] = $t_ship_by_shipping_method;
        foreach ($t_ship_by_shipping_method as $k => $v) {
            $v = trim($v);
            if ((empty($v) || $v == "") && $cart["groups_delivery"][$k] == "_USE_MY_TRUCKING_ACCOUNT_") {
                $top_message["content"] = 'Please fill in "trucking company" field.';
                $top_message["type"] = "E";
                func_header_location("cart.php?mode=checkout&shipping_error=Y");
            }
        }
    }
}

function cart_num($a, $b)
{
    if ($a['cartid'] == $b['cartid']) {
        return 0;
    }
    return ($a['cartid'] < $b['cartid']) ? -1 : 1;
}

$smarty->assign('last_categoryid', $last_categoryid);

$shopMoreUrl = '/';
$oCidevSurfPath = CidevSurfPath::getLastSurfPath([
    CidevSurfPath::SURFPATH_TYPE_SEARCH,
    CidevSurfPath::SURFPATH_TYPE_CATEGORY,
    CidevSurfPath::SURFPATH_TYPE_BRAND]);
if (empty($oCidevSurfPath)){
    $oCidevSurfPath = CidevSurfPath::getLastSurfPath([CidevSurfPath::SURFPATH_TYPE_PRODUCT]);
}
if (!empty($oCidevSurfPath)) {
    if ($oCidevSurfPath->getUrl()){
        $shopMoreUrl = $oCidevSurfPath->getUrl();
    }
}
$smarty->assign('shopMoreUrl', $shopMoreUrl);

if (isset($dhl_ext_country)) {
    $dhl_ext_country_store = $dhl_ext_country;
} else {
    $dhl_ext_country = $dhl_ext_country_store;
}

if ($cart_locked && !($mode == 'add2wl' || $mode == "wishlist")) {
    # User cannot operate with cart while processing order on Google Checkout
    db_query("DELETE FROM $sql_tbl[cc_pp3_data] WHERE sessionid='$XCARTSESSID'");
    $cart_locked = false;
}

$intershipper_recalc = "Y";

$smarty->assign("company_state", func_query_first_cell("SELECT $sql_tbl[states].state FROM $sql_tbl[states] WHERE $sql_tbl[states].country_code = '" . $config['Company']['location_country'] . "' AND $sql_tbl[states].code = '" . $config['Company']['location_state'] . "'"));
require $xcart_dir . "/include/countries.php";
if (!empty($countries))
    foreach ($countries as $country)
        if ($country['country_code'] == $config['Company']['location_country'])
            $smarty->assign("company_country", $country['country']);
#
# Check if the cart is empty
#
$func_is_cart_empty = func_is_cart_empty($cart);

#
# Stop list module: check transaction
#
if (!empty($active_modules["Stop_List"]) && !func_is_allowed_trans() && !$func_is_cart_empty) {
    if ($mode == "checkout" || $mode == "auth") {
        $top_message["content"] = func_get_langvar_by_name("txt_stop_list_customer_note");
        $top_message["type"] = "E";
        func_header_location("cart.php");
    }

    $smarty->assign("unallowed_transaction", "Y");
}


#
# Normalize cart content
#
if (!$func_is_cart_empty && $REQUEST_METHOD == "GET" && !in_array($mode, array("wishlist", "wl2cart"))) {
    $cart_changed = func_cart_normalize($cart);
}

if (($mode == "checkout" || $mode == "auth") && !$func_is_cart_empty) {
    #
    # Calculate total number of checkout process steps
    #
    $total_checkout_steps = 2;
    $checkout_step_modifier["anonymous"] = 0;
    $checkout_step_modifier["payment_methods"] = 0;
    if ($login == "" && $anonymous_checkout) {
        $total_checkout_steps++;
        $checkout_step_modifier["anonymous"] = 1;
    }

    $payment_methods = check_payment_methods(@$user_account["membershipid"]);
    if (empty($payment_methods)) {
        $top_message['content'] = func_get_langvar_by_name("txt_no_payment_methods");
        $top_message['type'] = 'E';
        func_header_location("cart.php");
    } elseif (count($payment_methods) == 1) {
        $total_checkout_steps--;
        $checkout_step_modifier["payment_methods"] = 1;
    }
} else {
    $anonymous_checkout = false;
}

if ($mode == "clear_cart") {
    #
    # Clear entire cart
    #
    if (!empty($active_modules["SnS_connector"]) && !empty($cart["products"])) {
        foreach ($cart["products"] as $p) {
            $is_sns_action['DeleteFromCart'][] = $p['productid'];
        }
    }

    $cart = "";

    x_session_unregister('added_catalogs');
    x_session_unregister('catalog_checkboxes');

    if (!empty($last_categoryid)) {
        $top_message["content"] = func_get_langvar_by_name("cidev_cart_is_empty");
        $top_message["type"] = "I";

        $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$last_categoryid'");
        if (empty($clean_url_link)) {
            $clean_url_link = "home.php?cat=" . $last_categoryid;
        }

        func_header_location($clean_url_link);
    } else {
        func_header_location("cart.php");
    }
}

if (!empty($active_modules['Gift_Certificates']) && $mode == "unset_gc" && $gcid) {
    #
    # Unset Gift Certificate
    #
    func_giftcert_unset($gcid);

    func_header_location("cart.php?mode=checkout" . ($paymentid ? "&paymentid=" . $paymentid : ""));
}

$smarty->assign("register_script_name", (($config["General"]["use_https_login"] == "Y") ? $xcart_catalogs_secure["customer"] . "/" : "") . "cart.php");

if ($mode == "gcheckout" && !empty($active_modules['Google_Checkout'])) {
    define('CHECKOUT_STARTED', 1);
    include $xcart_dir . "/modules/Google_Checkout/gcheckout.php";
}

#
# Register member if not registerred yet
# (not a newbie - do not show help messages)
#

if ($mode == "checkout") {


#
##
###
    if ($config["Appearance"]["Enable_surf_stats"] == "Y" && $l == "y") {
        Xcart\Surfing\SurfPath::create(['resource_type' => Xcart\Surfing\SurfPath::GOAL_TYPE_CHECKOUT])->logSurfPath();
    }
###
##
#


    $usertype = "C";
    $old_action = $action;
    $action = "cart";
    $smarty->assign("action", $action);
    $newbie = "Y";
    if (empty($login))
        include $xcart_dir . "/include/register.php";

    if (!empty($auto_login)) {
        func_header_location("cart.php?mode=checkout&registered=");
    }

    $saved_userinfo = $userinfo;
    $action = $old_action;
    $smarty->assign("newbie", $newbie);
}

if (!empty($login))
    $userinfo = func_userinfo($login, $current_area, false, false, "H");

if ($mode == 'add_catalog' && !empty($cc_manufacturerid)) {
    func_add_catalog_to_cart($cc_manufacturerid);

    # Recalculate cart totals after new item added
    $products = func_products_in_cart($cart, (!empty($user_account["membershipid"]) ? $user_account["membershipid"] : ""));
    $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));
}

#
##
###
if ($mode == "add_to_cart" && !empty($o)) {
    x_load('crypt');

    $orderid = text_decrypt($o);

    if (!empty($orderid)) {

        $order_data = func_order_data($orderid);

        if (!empty($order_data["products"]) && is_array($order_data["products"])) {
            foreach ($order_data["products"] as $k => $add_product) {

                if (!empty($add_product['product_options']) && is_array($add_product['product_options']) && !empty($add_product['extra_data']['product_options']) && is_array($add_product['extra_data']['product_options'])) {
                    $add_product['product_options'] = $add_product['extra_data']['product_options'];
                } else {
                    $add_product['product_options'] = false;
                }

                $result = func_add_to_cart($cart, $add_product);

                $intershipper_recalc = "Y";
                # Recalculate cart totals after new item added
                $products = func_products_in_cart($cart, (!empty($user_account["membershipid"]) ? $user_account["membershipid"] : ""));
                $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));
            }
        }

        func_header_location("cart.php");
    }
}
###
##
#

if ($mode == "add" && !empty($productid)) {

//	$catalog_price = func_query_first_cell('SELECT m.catalog_price FROM '.$sql_tbl['manufacturers'].' as m LEFT JOIN ' . $sql_tbl['products'] . ' as p ON m.catalog_sku=p.productcode WHERE p.productid="' . mysql_real_escape_string($productid) . '"');

    $tmp_info_product = func_query_first("SELECT p.min_amount, p.mult_order_quantity, m.catalog_price FROM $sql_tbl[products] as p LEFT JOIN  $sql_tbl[manufacturers] as m ON m.catalog_sku=p.productcode WHERE p.productid='$productid'");

    $min_amount = $tmp_info_product["min_amount"];
    $mult_order_quantity = $tmp_info_product["mult_order_quantity"];
    $catalog_price = $tmp_info_product["catalog_price"];

    #
    # Add product to the cart
    #
    $add_product = array();
    $add_product["productid"] = abs(intval($productid));
    $add_product["amount"] = abs(intval($amount));
    $add_product["product_options"] = $product_options;
    $add_product["price"] = abs(doubleval($price));
    $add_product['catalog_price'] = ($catalog_price) ? price_format($catalog_price) : null;

###
    if ($min_amount > 1 && $add_product['amount'] < $min_amount) {
        $add_product['amount'] = $min_amount;
    } elseif ($mult_order_quantity == "Y" && $min_amount > 1) {
        $ceil_amount = $add_product['amount'] / $min_amount;
        $ceil_amount = ceil($ceil_amount);
        $add_product['amount'] = $ceil_amount * $min_amount;
    }
###

    #
    # Add to cart
    #
    $result = func_add_to_cart($cart, $add_product);

    func_add_catalog_checkbox_to_cart($add_product['productid']);

    if (!empty($result["redirect_to"]))
        func_header_location($result["redirect_to"]);

    $intershipper_recalc = "Y";

    # Recalculate cart totals after new item added
    $products = func_products_in_cart($cart, (!empty($user_account["membershipid"]) ? $user_account["membershipid"] : ""));
    $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));

    #
    # Redirect
    #
    if ($config["General"]["redirect_to_cart"] == "Y") {
        if (!empty($active_modules["SnS_connector"]))
            $is_sns_action['AddToCart'][] = $productid;

        func_header_location("cart.php");

    } else {
        $products = func_products_in_cart($cart, (!empty($user_account["membershipid"]) ? $user_account["membershipid"] : ""));
        $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));

        if (!empty($active_modules["SnS_connector"]))
            func_generate_sns_action("AddToCart", $productid);

        func_save_customer_cart($login, $cart);
        if (!empty($HTTP_REFERER)) {
            $tmp = parse_url($HTTP_REFERER);
            if ($config["General"]["return_to_dynamic_part"] == "Y" && $is_hc == "Y" && (strpos($tmp["path"], ".html") !== false || substr($tmp["path"], -1) == "/")) {
                if (substr($tmp["path"], -1) == "/") {
                    func_header_location("home.php");
                } elseif (strpos($HTTP_REFERER, "-c-") !== false) {
                    $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$cat'");
                    if (empty($clean_url_link)) {
                        $clean_url_link = "home.php?cat=$cat&page=$page";
                    } else {
                        $clean_url_link .= "/&page=$page";
                    }

                    func_header_location($clean_url_link);
                } else {
                    $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='P' AND resource_id='$add_product[productid]'");
                    if (empty($clean_url_link)) {
                        $clean_url_link = "product.php?productid=" . $add_product["productid"];
                    }

                    func_header_location($clean_url_link);
//					func_header_location("product.php?productid=".$add_product["productid"]);
                }
            } else {
                func_header_location($HTTP_REFERER);
            }
        } else {
//			func_header_location("home.php?cat=$cat&page=$page");
            $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$cat'");
            if (empty($clean_url_link)) {
                $clean_url_link = "home.php?cat=$cat&page=$page";
            } else {
                $clean_url_link .= "/&page=$page";
            }

            func_header_location($clean_url_link);
        }
    }
}

if ($mode == "delete" && !empty($productindex)) {
    #
    # Delete product from the cart
    #
    if (!empty($cart['products']) && is_array($cart['products'])) {
        $productid = func_delete_from_cart($cart, $productindex);

        if (!empty($active_modules["SnS_connector"]))
            $is_sns_action['DeleteFromCart'][] = $productid;

        $intershipper_recalc = "Y";
    }

    # Recalculate cart totals after updating
    $products = func_products_in_cart($cart, (!empty($user_account["membershipid"]) ? $user_account["membershipid"] : ""));
    $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));


    if (empty($cart["products"])) {
        unset($cart["products"]);
        unset($cart["shipping_groups"]);
        x_session_save("cart");
    }

    if (empty($cart["products"])) {
        $top_message["content"] = func_get_langvar_by_name("cidev_cart_is_empty");
        $top_message["type"] = "I";
        func_header_location($shopMoreUrl);
    } else {
        func_header_location("cart.php");
    }
}

if (empty($action)) $action = "";
$return_url = "";

#
# Update the cart
#
if ($action == "update" && !$func_is_cart_empty) {
    if (!empty($productindexes)) {
        # Update the quantity of products in cart
        list($min_amount_warns, $mult_amount_warns) = func_update_quantity_in_cart($cart, $productindexes);

        if (!empty($min_amount_warns) && !empty($cart['products'])) {
            $top_message['content'] = '';
            $min_amount_ids = array();
            foreach ($cart['products'] as $k => $v) {
                if (!isset($min_amount_warns[$v['cartid']])
                    || !isset($productindexes[$k])
                    || isset($min_amount_ids[$v['productid']])
                ) {
                    continue;
                }

                $product_name = func_query_first_cell("SELECT IF($sql_tbl[products_lng].product IS NULL OR $sql_tbl[products_lng].product = '', $sql_tbl[products].product, $sql_tbl[products_lng].product) as product FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_lng] ON $sql_tbl[products].productid = $sql_tbl[products_lng].productid AND $sql_tbl[products_lng].code = '$shop_language' WHERE $sql_tbl[products].productid = '$v[productid]'");
                $top_message['content'] .= (empty($top_message['content']) ? "" : "<br />\n") . func_get_langvar_by_name("lbl_cannot_buy_less_X", array("quantity" => $min_amount_warns[$v['cartid']], "product" => $product_name));
                $min_amount_ids[$v['productid']] = true;
            }

            if (!empty($top_message['content']))
                $top_message['type'] = "W";
        }

        if (!empty($mult_amount_warns) && !empty($cart['products'])) {
            x_session_register('mult_amount_warns', $mult_amount_warns);
        }

        if (!empty($active_modules["SnS_connector"]))
            $is_sns_action['CartChanged'][] = false;

        $intershipper_recalc = "Y";
    }

    #
    # Update shipping method
    #
    if ($config["Shipping"]["realtime_shipping"] == "Y" && !empty($active_modules["UPS_OnLine_Tools"]) && $config["Shipping"]["use_intershipper"] != "Y")
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
        $current_carrier = 'UPS';
# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

# START: random:17710_17631 [2009 Mar 26 09:25] 
    if (!empty($shippingids))
        $cart["shippingids"] = $shippingids;
# END: random:17710_17631 [2009 Mar 26 09:25] 

    $airborne_account = $arb_account;

    if (!empty($mode))
        $url_args[] = "mode=" . $mode;

    if (!empty($paymentid))
        $url_args[] = "paymentid=" . $paymentid;

    $return_url = "cart.php" . (!empty($url_args) ? "?" . implode("&", $url_args) : "");
    $func_is_cart_empty = func_is_cart_empty($cart);
}

if (!$func_is_cart_empty) {
    #
    # Prepare cart for calculation
    #
    $products = func_products_in_cart($cart, (!empty($userinfo["membershipid"]) ? $userinfo["membershipid"] : ""));
    if (!empty($cart["products"]) && is_array($products) && count($products) != count($cart["products"])) {
        #
        # The products array in the cart isn't accords to the store
        #
        foreach ($products as $k => $v)
            $prodids[] = $v["cartid"];

        if (is_array($prodids)) {
            foreach ($cart["products"] as $k => $v) {
                if (in_array($v["cartid"], $prodids))
                    $cart_prods[$k] = $v;
            }

            $cart["products"] = $cart_prods;
        } else {
            $cart = "";
        }

        func_header_location("cart.php?$QUERY_STRING");
    }

    if (!empty($active_modules["Subscriptions"])) {
        $in_cart = true;
        include $xcart_dir . "/modules/Subscriptions/subscription.php";
    }

    if (empty($login) && $config["General"]["apply_default_country"] == "Y") {
        # Use the default address
        $userinfo["s_country"] = $config["General"]["default_country"];
        $userinfo["s_state"] = $config["General"]["default_state"];
        $userinfo["s_zipcode"] = $config["General"]["default_zipcode"];
        $userinfo["s_city"] = $config["General"]["default_city"];
        $userinfo["s_countryname"] = func_get_country($userinfo["s_country"]);
        $userinfo["s_statename"] = func_get_state($userinfo["s_state"], $userinfo["s_country"]);
    }

    if (!empty($purchase_order_selected) && is_numeric($purchase_order_selected)) {
        $smarty->assign('selectedPO', Xcart\POPipeline::model(['po_id' => $purchase_order_selected]));
    }

    #
    # Check if shipping cost is need to be calculated
    #
    $need_shipping = false;
    if ($config["Shipping"]["disable_shipping"] != "Y" && is_array($products)) {
        foreach ($products as $product) {
            if (!empty($active_modules["Egoods"]) && !empty($product["distribution"]))
                continue;

            $need_shipping = true;
            break;
        }
    }

    $no_shipping_in_group = false;
    if ($need_shipping) {
        $shippings = array();
        if (is_array($cart["shipping_groups"])) {

            foreach ($cart["shipping_groups"] as $k => $v) {
                $_products = array();

                foreach ($products as $v2) {
                    if ($k == $v2['manufacturerid']) {
                        $_products[] = $v2;
                    }
                }
                if (!empty($config['Shipping']['new_shipping_calculation']) && $config['Shipping']['new_shipping_calculation'] == 'Y') {
                    if (!empty($_products)) {
                        $oManufacturer = Xcart\Manufacturer::model(['manufacturerid' => $k]);
                        $oCart = new Xcart\Cart();
                        foreach ($_products as $_product) {
                            $oProduct = Xcart\Product::model(['productid' => $_product['productid']]);
                            $oProduct->setPrice($_product['price']); //calculate regarding cart product price
                            if ($oProduct->getManufacturerId() == $k) {
                                $oCart->addObjectToCart(new \Xcart\CartElement($oProduct, $_product['amount']));
                            }
                        }
                        $oCustomer = (new Xcart\Customer())->fill($userinfo);
                        try {
                            $aShippingZones = Xcart\Shipping::model()->getShippingRates($oCustomer, $oManufacturer, $oCart);
                        }
                        catch(\Exception $e){
                            $aShippingZones = [];
                        }
                        $shipping = [];
                        if (!empty($aShippingZones)) {
                            /** @var \Xcart\ShippingRate[] $aShippingRates */
                            foreach ($aShippingZones as $aShippingRates) {
                                if (!empty($aShippingRates)) {
                                    foreach ($aShippingRates as $oShippingRate) {
                                        $shipping[$oShippingRate->getShippingId()] = $oShippingRate->getShippingEntity()->getFields();
                                        $shipping[$oShippingRate->getShippingId()]['rate'] = $oShippingRate->getShippingCharge();
                                        $shipping[$oShippingRate->getShippingId()]['allowed'] = true;
                                        if ($oShippingRate->getCart()->getExtraMarginValue() > 0) {
                                            $shipping[$oShippingRate->getShippingId()]['shipping_extra_margin_value'] = $oShippingRate->getShippingChargeBeforeMap() - $oShippingRate->getShippingCharge();
                                        }
                                        $aCartElements = $oShippingRate->getCart()->getElements();
                                        if (!empty($aCartElements)) {
                                            /** @var \Xcart\CartElement $oCartElement */
                                            foreach ($aCartElements as $oCartElement) {
                                                $shipping[$oShippingRate->getShippingId()]['products'][] = $oCartElement->getProduct()->getSKU();
                                            }
                                        }
                                        $aAddedShippingRates = $oShippingRate->getAddedShippingRates();
                                        if (!empty($aAddedShippingRates)) {
                                            foreach ($aAddedShippingRates as $oAddedShippingRate) {
                                                $aShipping = $oAddedShippingRate->getFields();
                                                $aShipping['shipping_charge'] = $oAddedShippingRate->getShippingCharge();
                                                $aShipping['shipping_extra_margin_value'] = $oAddedShippingRate->getCart()->getExtraMarginValue();
                                                $aProducts = $oAddedShippingRate->getCart()->getElements();
                                                if (!empty($aProducts)) {
                                                    /** @var \Xcart\CartElement $oCartElement */
                                                    foreach ($aProducts as $oCartElement) {
                                                        $aShipping['products'][] = $oCartElement->getProduct()->getSKU();
                                                    }
                                                }
                                                $shipping[$oShippingRate->getShippingId()]['added_shipping'][] = $aShipping;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $shippings[$k] = $shipping;
                        if ((!empty($cart["shippingids"][$k]) && !in_array($cart["shippingids"][$k], array_keys($shipping))) || empty($cart["shippingids"][$k])) {
                                $aShippingFirst = reset($shipping);
                                $cart["shippingids"][$k] = $aShippingFirst["shippingid"];
                        }

                        if (!empty($cart["shippingids"]) && is_array($cart["shippingids"])) {
                            $cart['shipping_cost'] = 0;
                            foreach ($cart["shippingids"] as $m_id => $sh_id) {
                                $cart['shipping_costs'][$m_id] = $shippings[$m_id][$sh_id]['rate'];
                                $cart['display_shipping_costs'][$m_id] = $cart['shipping_costs'][$m_id];
                                $cart['shipping_cost'] += $shippings[$m_id][$sh_id]['rate'];
                                $cart['display_shipping_cost'] = $cart['shipping_cost'];
                            }
                        }
                    }
                } else {
                    $current_carrier = 'UPS';
                    $intershipper_recalc = 'Y';
                    $shipping = func_get_shipping_methods_list($cart, $_products, $userinfo, false, $k);
                    $shippings[$k] = $shipping;
                    if (!$shipping) {
                        $no_shipping_in_group = true;
                    } elseif (is_array($shipping)) {
                        $shipping_matched = false;
                        foreach ($shipping as $shipping_method) {
                            if (@$cart["shippingids"][$k] == $shipping_method["shippingid"])
                                $shipping_matched = true;
                        }

                    }
                    if (!$shipping_matched)
                        $cart["shippingids"][$k] = $shipping[0]["shippingid"];
                }
            }
        }
        $cidev_redirect = false;

        if ($review == "y") {

            if (empty($cart["all_shippings"])) {
                $cidev_redirect = true;
            }

            if (!empty($cart["all_shippings"]) && is_array($cart["all_shippings"]) && is_array($cart["shipping_groups"]) && !empty($cart["shipping_groups"]) && !$cidev_redirect) {

                $count_cart_all_shippings = count($cart["all_shippings"]);
                $count_shippings = count($shippings);

                if (!empty($shippings) && is_array($shippings) && $count_cart_all_shippings == $count_shippings) {
                    $k_values_of_shippings = array();
                    $sid_values_of_shippings = array();
                    foreach ($shippings as $k => $v) {
                        $k_values_of_shippings[] = $k;
                        if (!empty($v) && is_array($v)) {
                            foreach ($v as $kk => $vv) {
                                $sid_values_of_shippings[] = $vv["shippingid"];
                            }
                        }
                    }

                    sort($k_values_of_shippings);
                    sort($sid_values_of_shippings);

                    $k_values_of_cart_all_shippings = array();
                    $sid_values_of_cart_all_shippings = array();
                    foreach ($cart["all_shippings"] as $k => $v) {
                        $k_values_of_cart_all_shippings[] = $k;
                        if (!empty($v) && is_array($v)) {
                            foreach ($v as $kk => $vv) {
                                $sid_values_of_cart_all_shippings[] = $vv["shippingid"];
                            }
                        }
                    }

                    sort($k_values_of_cart_all_shippings);
                    sort($sid_values_of_cart_all_shippings);

                    $array_diff_k = array_diff($k_values_of_shippings, $k_values_of_cart_all_shippings);
                    $array_diff_sid = array_diff($sid_values_of_shippings, $sid_values_of_cart_all_shippings);


                    if (!empty($array_diff_k) || !empty($array_diff_sid)) {
                        $cidev_redirect = true;
                    }
                } else {
                    $cidev_redirect = true;
                }
            }

            if (!$cidev_redirect && $cart["saved_shipping_cost"] != $cart["shipping_cost"]) {
                $cidev_redirect = true;
            }

            if ($cidev_redirect) {
                $script = "cart.php?mode=checkout";
                func_header_location($script);
            }
        }

        if ($mode == "checkout") {
            $cart["all_shippings"] = $shippings;
            $cart["saved_shipping_cost"] = $cart["shipping_cost"];
        }

        $cart['groups_delivery'] = array();
        if (!empty($cart['shippingids']))
            foreach ($cart['shippingids'] as $m_id => $sh_id) {
                $oShipping = \Xcart\Shipping::model(['shippingid' => $sh_id]);
                $cart['groups_delivery'][$m_id] = $oShipping->getFrontendName();
                $cart['groups_delivery_time'][$m_id] = $oShipping->getField('shipping_time');
            }
        $smarty->assign("shipping_groups", $cart["shipping_groups"]);
        $smarty->assign("shippings", $shippings);
        $smarty->assign("current_carrier", $current_carrier);
        if ($mode == "checkout" && empty($cart["shippingid"]) && !empty($shipping[0]["shippingid"])) {
            $cart["shippingid"] = $shipping[0]["shippingid"];
        }

        $tmp_payment_methods = check_payment_methods(@$user_account["membershipid"]);
        if ($mode == "checkout" && empty($cart["paymentid"]) && !empty($tmp_payment_methods[0]["paymentid"])) {
            $cart["paymentid"] = $tmp_payment_methods[0]["paymentid"];
        }
    } else {
        $cart["delivery"] = "";
        $cart["shippingid"] = 0;
        $cart['groups_delivery'] = array();
        $cart['shippingids'] = array();
    }

    $smarty->assign("need_shipping", $need_shipping);

    #
    # Discount coupons
    #
    if ($active_modules["Discount_Coupons"])
        include $xcart_dir . "/modules/Discount_Coupons/discount_coupons.php";

    if (!empty($active_modules['Multiple_Storefronts'])) {
        $sf_condition = "AND storefrontid=$current_storefront";
    } else {
        $sf_condition = '';
    }

    $active_discount_coupons = func_query_first_cell('SELECT COUNT(*) FROM ' . $sql_tbl['discount_coupons'] . ' WHERE status="A" ' . $sf_condition);
    if ($active_discount_coupons && $active_discount_coupons > 0) {
        $smarty->assign('show_discount_coupons', 'Y');
    }

    #
    # Calculate all prices
    #
    $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, (!empty($paymentid) ? intval($paymentid) : 0)));

    if (func_is_cart_empty($cart)) {
        if (!empty($active_modules["SnS_connector"]))
            func_sns_exec_actions($is_sns_action);

        $cart = "";
        func_header_location($xcart_web_dir . DIR_CUSTOMER . "/error_message.php?product_in_cart_expired");
    } else {
        $products = func_products_in_cart($cart, (!empty($userinfo["membershipid"]) ? $userinfo["membershipid"] : 0));
    }

    $allow_to_checkout = "Y";

    if (!empty($cart["shipping_groups"])) {

        foreach ($cart["shipping_groups"] as $mid => $v) {
            $cart["shipping_groups"][$mid]["count_shipping_rates_for_canada"] = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping_rates] WHERE manufacturerid='$mid' AND (type='R' OR type='D') AND zoneid='12'");

            if (!empty($v["need_add_more"])) {
                $allow_to_checkout = "N";
            }
        }
    }

    if ($allow_to_checkout == "N" & $mode == "checkout") {
        func_header_location("cart.php#warehouse");
    }

    $smarty->assign("allow_to_checkout", $allow_to_checkout);
    $smarty->assign("cart", $cart);
}

if (!empty($active_modules["SnS_connector"]))
    func_sns_exec_actions($is_sns_action);

if ($return_url)
    func_header_location($return_url);

$smarty->assign("main", "cart");

x_session_register('mult_amount_warns', array());
if (!empty($mult_amount_warns)) {
    $smarty->assign('mult_amount_warns', $mult_amount_warns);
    $mult_amount_warns = array();
}

#
# Wishlist facility
#
if (!empty($active_modules["Wishlist"]) && $mode != "checkout") {
    @include $xcart_dir . "/modules/Wishlist/wishlist.php";
}

if ($mode != "wishlist" || empty($active_modules['Wishlist'])) {
    if ($mode == "checkout")
        $location[] = array(func_get_langvar_by_name("lbl_checkout"), "");
    else
        $location[] = array(func_get_langvar_by_name("lbl_your_shopping_cart"), "");
}

#
# SHOPPING CART FEATURE
#

if ($mode == "checkout" && !empty($cart["products"]) && $no_shipping_in_group && !empty($login) && $need_shipping && $config["Shipping"]["disable_shipping"] != "Y") {
    #
    # ERROR: No shipping methods selected
    #
    $smarty->assign("no_shipping_in_group", $no_shipping_in_group);
    if (!empty($active_modules["Fast_Lane_Checkout"]))
        $no_shipping = true;
    else
        func_header_location("error_message.php?error_no_shipping");
}

if ($mode == "checkout" && !$func_is_cart_empty && $cart["display_subtotal"] < $config["General"]["minimal_order_amount"] && $config["General"]["minimal_order_amount"] > 0) {
    #
    # ERROR: Cart total must exceeds the minimum order total amount (defined in General settings)
    #
    func_header_location("error_message.php?error_min_order");
}

if ($mode == "checkout" && !$func_is_cart_empty && $config["General"]["maximum_order_amount"] > 0 && $cart["display_subtotal"] > $config["General"]["maximum_order_amount"]) {
    #
    # ERROR: Cart total must not exceeds the maximum order total amount (defined in General settings)
    #
    func_header_location("error_message.php?error_max_order");
}

if ($mode == "checkout" && !$func_is_cart_empty && $config["General"]["maximum_order_items"] > 0 && func_cart_count_items($cart) > $config["General"]["maximum_order_items"]) {
    #
    # ERROR: Cart total must not exceeds the maximum total quantity of products in an order (defined in General settings)
    #
    func_header_location("error_message.php?error_max_items");
}

if ($mode == "checkout" && empty($login) && !$func_is_cart_empty) {
    #
    # Start the anonymous checkout
    #
    $smarty->assign("main", "anonymous_checkout");
    $smarty->assign("anonymous", "Y");
    if (empty($userinfo) && !empty($saved_userinfo)) {
        $userinfo = $saved_userinfo;
    }

    x_session_register("shipquote_userinfo");
    if (!empty($shipquote_userinfo)) {
        if (empty($userinfo))
            $userinfo = array();
        $userinfo = array_merge($userinfo, $shipquote_userinfo);
        $smarty->assign("userinfo", $userinfo);
    }

    $checkout_step = 1;
    $anonymous_checkout = true;

    $location[] = array(func_get_langvar_by_name("lbl_your_order"), "");

    #
    # For PayPal ExpressCheckout
    #
    if (test_active_bouncer() && $config['General']['disable_anonymous_checkout'] != 'Y') {
        # detect active PayPal Pro
        $tmp = func_query_first_cell("SELECT $sql_tbl[payment_methods].paymentid FROM $sql_tbl[ccprocessors], $sql_tbl[payment_methods] WHERE $sql_tbl[ccprocessors].processor='ps_paypal_pro.php' AND $sql_tbl[ccprocessors].paymentid=$sql_tbl[payment_methods].paymentid AND $sql_tbl[payment_methods].active='Y' ORDER BY $sql_tbl[payment_methods].protocol DESC LIMIT 1");
        $smarty->assign("paypal_express_active", $tmp);
        x_session_unregister('paypal_begin_express');
    }
} elseif ($mode == "checkout" && empty($paymentid) && !$func_is_cart_empty && $cart["total_cost"] == 0) {
    #
    # Skip payment routine if cart total is 0
    #
    x_session_unregister('paypal_begin_express');
    func_header_location($current_location . "/payment/payment_offline.php");
} elseif ($mode == "checkout" && !empty($paymentid) && !$func_is_cart_empty) {
    #
    # Prepare the last step of checkout
    #

    # Check if paymentid isn't fake
    $is_egoods = ($config["Egoods"]["egoods_manual_cc_processing"] == "Y" ? func_esd_in_cart($cart) : false);
    $membershipid = $user_account["membershipid"];
    $paypal_pro_condition = "";

    $is_valid_paymentid = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[payment_methods] LEFT JOIN $sql_tbl[pmethod_memberships] ON $sql_tbl[pmethod_memberships].paymentid = $sql_tbl[payment_methods].paymentid WHERE $sql_tbl[payment_methods].paymentid='$paymentid'" . (($is_egoods && $paymentid == 1) ? "" : " AND $sql_tbl[payment_methods].active='Y'") . " AND ($sql_tbl[pmethod_memberships].membershipid IS NULL OR $sql_tbl[pmethod_memberships].membershipid = '$membershipid') " . $paypal_pro_condition);
    if (!$is_valid_paymentid)
        func_header_location("cart.php?mode=checkout&err=paymentid");

    $paypal_expressid = func_query_first_cell("SELECT $sql_tbl[payment_methods].paymentid FROM $sql_tbl[payment_methods], $sql_tbl[ccprocessors] WHERE $sql_tbl[payment_methods].processor_file='ps_paypal_pro.php' AND $sql_tbl[payment_methods].processor_file=$sql_tbl[ccprocessors].processor AND $sql_tbl[payment_methods].paymentid=$sql_tbl[ccprocessors].paymentid AND $sql_tbl[payment_methods].active='Y'");

    if (!empty($paypal_expressid) && $paypal_expressid == $paymentid) {

        if (!empty($active_modules['Fast_Lane_Checkout']) && (@$no_shippings_in_group) && $config["Shipping"]["disable_shipping"] != "Y") {
            $top_message["content"] = func_get_langvar_by_name("msg_flc_select_shipping_err");
            $top_message["type"] = "E";
            func_header_location("cart.php?mode=checkout");
        }

        x_session_register('paypal_begin_express');
        if ($paypal_begin_express !== false) {
            $paypal_begin_express = true;
            func_header_location($current_location . '/payment/ps_paypal_pro.php?payment_id=' . $paymentid . '&mode=express');
        }
    }

    # Generate uniq orderid which will identify order session
    $order_secureid = md5(uniqid(rand()));

    # Show payment details checkout page
    $payment_cc_data = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE paymentid='$paymentid'");
    if ($is_egoods && $paymentid != 1 && !empty($payment_cc_data)) {
        $paymentid = 1;
        $payment_cc_data = array();
    }

    # Generate payment script URL depending on HTTP/HTTPS settings
    if (empty($cart['shippingid'])) {
        $payment_data = func_query_first("SELECT $sql_tbl[payment_methods].*, $sql_tbl[payment_methods].payment_method as payment_method_orig, IFNULL(l1.value, $sql_tbl[payment_methods].payment_method) as payment_method, IFNULL(l2.value, $sql_tbl[payment_methods].payment_details) as payment_details FROM $sql_tbl[payment_methods] LEFT JOIN $sql_tbl[languages_alt] as l1 ON l1.name = CONCAT('payment_method_', $sql_tbl[payment_methods].paymentid) AND l1.code = '$shop_language' LEFT JOIN $sql_tbl[languages_alt] as l2 ON l2.name = CONCAT('payment_details_', $sql_tbl[payment_methods].paymentid) AND l2.code = '$shop_language' WHERE $sql_tbl[payment_methods].paymentid='$paymentid'");

    } else {
        $payment_data = func_query_first("SELECT $sql_tbl[payment_methods].*, $sql_tbl[payment_methods].payment_method as payment_method_orig, IFNULL(l1.value, $sql_tbl[payment_methods].payment_method) as payment_method, IFNULL(l2.value, $sql_tbl[payment_methods].payment_details) as payment_details FROM $sql_tbl[payment_methods] LEFT JOIN $sql_tbl[languages_alt] as l1 ON l1.name = CONCAT('payment_method_', $sql_tbl[payment_methods].paymentid) AND l1.code = '$shop_language' LEFT JOIN $sql_tbl[languages_alt] as l2 ON l2.name = CONCAT('payment_details_', $sql_tbl[payment_methods].paymentid) AND l2.code = '$shop_language' LEFT JOIN $sql_tbl[shipping] ON $sql_tbl[shipping].shippingid = '$cart[shippingid]' WHERE $sql_tbl[payment_methods].paymentid='$paymentid' AND ($sql_tbl[payment_methods].is_cod != 'Y' || $sql_tbl[shipping].is_cod = 'Y')");
    }
    if (empty($payment_data)) {
        func_header_location("cart.php?mode=checkout");
    }

    $cart["paymentid"] = $paymentid;

    $payment_data["payment_script_url"] = ($payment_data["protocol"] == "https" ? $https_location : $http_location) . "/payment/" . $payment_data["payment_script"];

    if (!empty($payment_cc_fields)) {
        $userinfo = func_array_merge($userinfo, $payment_cc_fields);
    }

    if ($checkout_step_modifier["payment_methods"] == 1)
        $smarty->assign("ignore_payment_method_selection", 1);

    $checkout_step = 2 + $checkout_step_modifier["anonymous"] - $checkout_step_modifier["payment_methods"];

    if (x_session_is_registered('paypal_begin_express')) {
        $tmp = func_query_first_cell("SELECT $sql_tbl[payment_methods].paymentid FROM $sql_tbl[ccprocessors], $sql_tbl[payment_methods] WHERE $sql_tbl[ccprocessors].processor='ps_paypal_pro.php' AND $sql_tbl[ccprocessors].paymentid=$sql_tbl[payment_methods].paymentid AND $sql_tbl[payment_methods].paymentid='$paymentid' AND $sql_tbl[payment_methods].active='Y' ORDER BY $sql_tbl[payment_methods].protocol DESC LIMIT 1");
        $smarty->assign('paypal_express_active', $tmp);
    }

    if ($payment_data["processor_file"] == "ps_paypal_pro.php") {
        $payment_cc_data = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE processor='ps_paypal_pro.php'");
    }

    $payment_data['module_params'] = func_query_first("SELECT * FROM $sql_tbl[ccprocessors] WHERE paymentid = '$payment_data[paymentid]'");
    $smarty->assign("payment_cc_data", $payment_cc_data);
    $smarty->assign("payment_data", $payment_data);
    $smarty->assign("userinfo", $userinfo);
    $smarty->assign("main", "checkout");

    x_session_register("customer_notes");
    $smarty->assign("customer_notes", $customer_notes);

    $location[] = array(func_get_langvar_by_name("lbl_payment_details"), "");
} elseif ($mode == "checkout" && !$func_is_cart_empty) {
    #
    # Prepare the page for payment method selection
    #
    $payment_methods = check_payment_methods(@$user_account["membershipid"]);
    $force_change_shipping = (!empty($active_modules["Fast_Lane_Checkout"]) && (count($shipping) > 1 || ($need_shipping && (@$no_shippings_in_group))));

    if (count($payment_methods) == 1 && !$force_change_shipping) {
        # Skip payment method selection if only one method is available
        func_header_location("cart.php?paymentid=" . $payment_methods[0]["paymentid"] . "&mode=checkout");
    }

    if (!empty($payment_methods))
        $payment_methods[0]["is_default"] = 1;

    $checkout_step = 1 + $checkout_step_modifier["anonymous"] - $checkout_step_modifier["payment_methods"];

    $smarty->assign("payment_methods", $payment_methods);
    $smarty->assign("main", "checkout");

    $location[] = array(func_get_langvar_by_name("lbl_payment_details"), "");

    x_session_unregister('paypal_begin_express');
} elseif ($mode == "order_message") {
    #
    # Display the invoice page (order confirmation page)
    #
    $orders = array();

    x_session_unregister('added_catalogs');
    x_session_unregister('catalog_checkboxes');

    $order_sf = $current_storefront;

    if (!empty($orderids)) {
        if (empty($login) && $autologout != 'Y')
            func_header_location("error_message.php?access_denied&id=32");

        $_orderids = explode(",", $orderids);

        foreach ($_orderids as $orderid) {
            $order_data = func_order_data($orderid);

            # Security check if current customer is not order's owner
            if (empty($order_data) || $order_data["order"]["login"] != $login) {
                unset($order_data);
                continue;
            } else {
                $order_data["products"] = func_translate_products($order_data["products"], $shop_language);
            }

            if (is_numeric($order_data['order']['storefrontid'])) {
                $order_sf = $order_data['order']['storefrontid'];
            }

            $orders[] = $order_data;
        }
    }

    if ($order_sf != $current_storefront) {
        $sf_location = func_get_http_location_sf($order_sf);
        func_header_location('http://' . $sf_location . $xcart_web_dir . '/cart.php?mode=order_message&orderids=' . $orderids
            . '&' . $XCART_SESSION_NAME . '=' . $XCARTSESSID);
    }

    if (empty($orders))
        func_header_location("error_message.php?access_denied&id=59");

    $smarty->assign("orders", $orders);

    if ($action == "print") {
        $smarty->assign("template", "customer/main/order_message.tpl");
        func_display("customer/preview.tpl", $smarty);
        exit;
    }
    $smarty->assign("orderids", $orderids);
    $smarty->assign("main", "order_message");

    $location[] = array(func_get_langvar_by_name("lbl_order_processed"), "");
} elseif ($mode == "auth" && !$func_is_cart_empty) {

    func_header_location($xcart_web_dir . '/cart.php?mode=checkout');
    #
    # Display the authentication page
    #
    /*$smarty->assign("main","checkout");
    $checkout_step = 1;*/
}

//require $xcart_dir . "/include/categories.php";

if ($mode == "order_message") {
    if ($active_modules["Brands"])
        include $xcart_dir . "/modules/Brands/customer_brands.php";
    else
        if ($active_modules["Manufacturers"])
            include $xcart_dir . "/modules/Manufacturers/customer_manufacturers.php";
}
$giftcerts = (!empty($cart["giftcerts"]) ? $cart["giftcerts"] : array());

#
# Updare minicart
#
include "./minicart.php";

if (!empty($payment_cc_fields)) {
    $userinfo = func_array_merge($userinfo, $payment_cc_fields);
}

if (!empty($login) || $mode != "checkout") {
    $smarty->assign("userinfo", @$userinfo);
}

if (@$products)
    usort($products, "cart_num");

$smarty->assign("products", @$products);
$smarty->assign("giftcerts", $giftcerts);

if ($mode == "checkout" || $mode == "auth") {
    $smarty->assign("checkout_step", $checkout_step);
    $smarty->assign("total_checkout_steps", $total_checkout_steps);
}

func_save_customer_cart($login, $cart);

if (func_use_arb_account()) {
    $smarty->assign("use_airborne_account", true);
    $smarty->assign("airborne_account", $airborne_account);
}

$allow_cod = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[payment_methods] WHERE active = 'Y' AND is_cod = 'Y'") > 0;
$smarty->assign("allow_cod", $allow_cod);

if (!empty($cart["shippingid"]) && $cart["shippingid"] > 0) {
    $tmp_for_display_cod = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE active = 'Y' AND is_cod = 'Y' AND shippingid = '$cart[shippingid]'");
} else {
    $tmp_for_display_cod = 0;
}
$display_cod = $tmp_for_display_cod > 0;

$smarty->assign("display_cod", $display_cod);

$smarty->assign('catalog_checkboxes', $catalog_checkboxes);
$smarty->assign('added_catalogs', $added_catalogs);

x_session_save();
$smarty->assign("dhl_ext_country", $dhl_ext_country);
$smarty->assign("dhl_ext_countries", $dhl_ext_countries);


$show_only_phone_method = "N";
if (!empty($shippings) && is_array($shippings)) {
    foreach ($shippings as $k => $v) {
        if (empty($v) || !is_array($v)) {
            $show_only_phone_method = "Y";
            break;
        }
    }
}
$smarty->assign("show_only_phone_method", $show_only_phone_method);

if (!empty($cart["products"]) && is_array($cart["products"])) {
    $productids_in_cart = array();
    foreach ($cart["products"] as $k => $v) {
        $productids_in_cart[] = $v["productid"];
    }
    $productids_in_cart_imploded = implode("','", $productids_in_cart);
    $productids_in_cart_imploded = "['" . $productids_in_cart_imploded . "']";
    $smarty->assign('productids_in_cart_imploded', $productids_in_cart_imploded);
}

if (!empty($order_data["products"]) && is_array($order_data["products"])) {
    $productids_in_cart = array();
    foreach ($order_data["products"] as $k => $v) {
        $productids_in_cart[] = $v["productid"];
    }
    $productids_in_cart_imploded = implode("','", $productids_in_cart);
    $productids_in_cart_imploded = "['" . $productids_in_cart_imploded . "']";
    $smarty->assign('productids_in_cart_imploded', $productids_in_cart_imploded);
    $smarty->assign('order_data_subtotal', $order_data['order']['subtotal']);
}

if (!empty($active_modules["Fast_Lane_Checkout"]))
    include $xcart_dir . "/modules/Fast_Lane_Checkout/cart.php";

if ($mode == 'order_message') {
    x_session_register('perform_autologout');
    $perform_autologout = 'Y';
}

if (!empty($orderid) && is_numeric($orderid)) {
    $oOrder = new Xcart\Order(['orderid' => $orderid]);
    $smarty->assign('oOrder', $oOrder);
}


$smarty->assign("partner", $partner);
# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl", $smarty);

?>
