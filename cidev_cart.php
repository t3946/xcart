<?php
require "./auth.php";
x_load('cart', 'user', 'order');

require $xcart_dir . "/include/cart_process.php";
include $xcart_dir . "/shipping/shipping.php";

x_session_register("cart");
x_session_register("intershipper_rates");
x_session_register("intershipper_rates_all");
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


#
# Check if the cart is empty
#
$func_is_cart_empty = func_is_cart_empty($cart);

#
# Normalize cart content
#
if (!$func_is_cart_empty && $REQUEST_METHOD == "GET" && !in_array($mode, array("wishlist", "wl2cart"))) {
    $cart_changed = func_cart_normalize($cart);
}


if (!empty($login))
    $userinfo = func_userinfo($login, $current_area, false, false, "H");


#
# Update the cart
#
if ($action == "update" && !$func_is_cart_empty && !empty($amount)) {

    $productindexes[$cartid] = $amount;

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

    $func_is_cart_empty = func_is_cart_empty($cart);


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
        }


        #
        # Calculate all prices
        #
        $cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, (!empty($paymentid) ? intval($paymentid) : 0)));

        if (func_is_cart_empty($cart)) {
            $cart = "";
        } else {
            $products = func_products_in_cart($cart, (!empty($userinfo["membershipid"]) ? $userinfo["membershipid"] : 0));
        }

        $smarty->assign("cart", $cart);
    }


    $smarty->assign("main", "cart");

    /*
    x_session_register('mult_amount_warns', array());
    if (!empty($mult_amount_warns)) {
        $smarty->assign('mult_amount_warns', $mult_amount_warns);
        $mult_amount_warns = array();
    }
    */

    $giftcerts = (!empty($cart["giftcerts"]) ? $cart["giftcerts"] : array());

#
# Updare minicart
#
    include "./minicart.php";

    $smarty->assign("products", @$products);
    $smarty->assign("giftcerts", $giftcerts);
}

if ($cidev_filter_mode == "save_paymentid" && !empty($paymentid)) {
    $cart["paymentid"] = $paymentid;
    $smarty->assign("saved_paymentid", "Y");
    $smarty->assign("checkout_step", $checkout_step);
}

#
##
###
if ($cidev_filter_mode == "save_use_my_account") {
    $use_my_account = trim($use_my_account);

    if (!empty($use_my_account) && !empty($manufacturerid)) {
        $cart["use_my_account"][$manufacturerid] = $use_my_account;
    }
}

if ($cidev_filter_mode == "save_use_my_account_number") {

    $use_my_account_number = trim($use_my_account_number);

    if (!empty($use_my_account_number) && !empty($use_my_account_number)) {
        $cart["use_my_account_number"][$manufacturerid] = $use_my_account_number;
    }
}

if ($cidev_filter_mode == "save_ship_by_shipping_method" && !empty($ship_by_shipping_method) && !empty($ship_by_shipping_method)) {
    $cart["ship_by_shipping_method"][$manufacturerid] = $ship_by_shipping_method;
}

if ($cidev_filter_mode == "save_t_use_my_account_number") {

    $t_use_my_account_number = trim($t_use_my_account_number);

    if (!empty($t_use_my_account_number) && !empty($t_use_my_account_number)) {
        $cart["t_use_my_account_number"][$manufacturerid] = $t_use_my_account_number;
    }
}

if ($cidev_filter_mode == "save_t_ship_by_shipping_method" && !empty($t_ship_by_shipping_method) && !empty($t_ship_by_shipping_method)) {
    $cart["t_ship_by_shipping_method"][$manufacturerid] = $t_ship_by_shipping_method;
}
###
##
#

if ((empty($config['Shipping']['new_shipping_calculation']) || $config['Shipping']['new_shipping_calculation'] != 'Y') && $cidev_filter_mode == "save_shippingid" && !empty($shippingids) && is_array($shippingids)) {

    if (!$func_is_cart_empty) {

        $cart["shippingids"] = $shippingids;

        $products = func_products_in_cart($cart, (!empty($userinfo["membershipid"]) ? $userinfo["membershipid"] : ""));

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

            $cart['groups_delivery'] = array();
            if (!empty($cart['shippingids']))
                foreach ($cart['shippingids'] as $m_id => $sh_id) {
                    $cart['groups_delivery'][$m_id] = func_query_first_cell("SELECT shipping FROM $sql_tbl[shipping] WHERE shippingid = '$sh_id'");
                }
        }
    }
}


func_save_customer_cart($login, $cart);
$smarty->assign("cart", $cart);


x_session_save("cart");
x_session_save("products");

if (!empty($manufacturerid) && is_array($cart["shipping_groups"][$manufacturerid]) && !empty($products) && is_array($products) && !empty($cartid)) {
    $cidev_hidden_deliv_subt = 0;
    foreach ($products as $k => $v) {
        if ($v["manufacturerid"] == $manufacturerid) {
            $cidev_hidden_deliv_subt += $v["display_subtotal"];

            if ($cartid == $v["cartid"]) {
                $smarty->assign("cidev_hidden_display_price", $v["display_price"]);

                $cidev_hidden_price_on_amount = $v["display_price"] * $v["amount"];
                $cidev_hidden_price_on_amount = price_format($cidev_hidden_price_on_amount);
                $smarty->assign("cidev_hidden_price_on_amount", $cidev_hidden_price_on_amount);

                if ($v["amount"] != $amount) {
                    $smarty->assign("cidev_hidden_set_new_amount", $v["amount"]);
                }
            }
        }
    }


###
    if (!empty($cart["shipping_groups"]) && is_array($cart["shipping_groups"])) {
        foreach ($cart["shipping_groups"] as $mid => $v) {
            if (!empty($v["need_add_more"])) {
                $hidden_allow_to_checkout = "N";
            } else {

                $hidden_allow_to_checkout = "Y";
            }
        }
    }

    if (!empty($cart["shipping_groups"][$manufacturerid]["need_add_more"])) {
        $hidden_need_add_more = "Y";
    } else {
        $hidden_need_add_more = "N";
    }

    $smarty->assign("hidden_allow_to_checkout", $hidden_allow_to_checkout);
    $smarty->assign("hidden_need_add_more", $hidden_need_add_more);
###


    $smarty->assign("cidev_hidden_deliv_subt", $cidev_hidden_deliv_subt);
    $smarty->assign("hidden_manufacturerid", $manufacturerid);
    $smarty->assign("hidden_cartid", $cartid);
}

if ($cidev_filter_mode == "save_paymentid") {
    func_display("modules/Fast_Lane_Checkout/tabs_menu.tpl", $smarty);
} elseif ($cidev_filter_mode == "save_shippingid") {
} else {
    func_display("modules/Fast_Lane_Checkout/cart_subtotal.tpl", $smarty);
}
?>
