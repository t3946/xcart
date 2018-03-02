<?php


use Modules\Core\Components\Profiler;
use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Distributor\Models\DistributorModel;

define('OFFERS_DONT_SHOW_NEW', 1);
require "./auth.php";

Profiler::getInstance()->addPoint();

if (
    isset($productid)
    && !empty($productid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
) {
    func_clean_url_permanent_redirect('P', intval($productid));
}


if (isset($sku)) {
    $sku = trim($sku);

    if ($mode == 'check' || $mode == 'check_all') {
        if ($mode == 'check') {
            $productid = func_query_first_cell("SELECT $sql_tbl[products].productid FROM $sql_tbl[products]"
                . " INNER JOIN $sql_tbl[products_sf] ON $sql_tbl[products].productid = $sql_tbl[products_sf].productid"
                . " WHERE ($sql_tbl[products].productcode LIKE '$sku%') AND ($sql_tbl[products_sf].sfid = $current_storefront)");
        } else {
            $productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode LIKE '$sku%'");
        }
        echo(empty($productid) ? 0 : 1);
        exit;
    }

    $productid = $sku;
}

Profiler::getInstance()->addPoint();
$product_info = func_select_product($productid, @$user_account['membershipid'], !isset($sku));
Profiler::getInstance()->addPoint();
/** @var ProductModel $oProduct */
/** @var \Modules\Goods\Models\CategoryModel $oCategory */
$oProduct = $product_info['oProduct'];
$oCategory = $product_info['oCategory'];

$current_forsale = $oProduct->forsale;

if ($current_forsale == "N"){

    $categoryid_path = func_query_first_cell("SELECT $sql_tbl[categories].categoryid_path FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[categories].categoryid = $sql_tbl[products_categories].categoryid WHERE $sql_tbl[products_categories].productid='$productid' AND $sql_tbl[products_categories].main='Y' and $sql_tbl[categories].storefrontid = $current_storefront");

    $categoryid_path_arr = explode('/', $categoryid_path);
    krsort($categoryid_path_arr);

    if (!empty($categoryid_path_arr) && is_array($categoryid_path_arr)) {
        foreach ($categoryid_path_arr as $k => $categoryid) {
            $avail = func_query_first_cell("SELECT avail FROM $sql_tbl[categories] WHERE categoryid='$categoryid'");
            if ($avail == "Y") {
                $redirect_url = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$categoryid'");
                if (!empty($redirect_url)) {
                    $redirect_url = $xcart_web_dir . "/" . $redirect_url . "/";
                    func_header_location($redirect_url, true, 301);
                }
            }
        }
    }

    $brandid = func_query_first_cell("SELECT brandid FROM $sql_tbl[products] WHERE productid='$productid'");
    if (!empty($brandid)) {
        $redirect_url = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$brandid'");
        if (!empty($redirect_url)) {
            $redirect_url = $xcart_web_dir . "/" . $redirect_url . "/";
        }
    } else {
        $redirect_url = $xcart_web_dir . "/";
    }

    func_header_location($redirect_url, true);
}

Profiler::getInstance()->addPoint();
//$smarty->assign("company_state", func_query_first_cell("SELECT $sql_tbl[states].state FROM $sql_tbl[states] WHERE $sql_tbl[states].country_code = '".$config['Company']['location_country']."' AND $sql_tbl[states].code = '".$config['Company']['location_state']."'"));
//require $xcart_dir."/include/countries.php";

/*if(!empty($countries))
	foreach($countries as $country)
	if($country['country_code']==$config['Company']['location_country'])
	$smarty->assign("company_country", $country['country']);*/

Profiler::getInstance()->addPoint();

if (empty($product_info)) {
    func_header_location("search.php?substring=" . urlencode($sku) . "&by_sku=1&mode=search&from=fast_search");
}
Profiler::getInstance()->addPoint();

$oManufacturer = $oProduct->distributor;

$reverse_sku = $oManufacturer->reverse_sku;
$remove_dashes = $oManufacturer->remove_dashes;

if ($remove_dashes == "Y") {
    $product_info["productcode"] = str_replace("-", ".", $product_info["productcode"]);
}
Profiler::getInstance()->addPoint();

if ($reverse_sku == "Y") {
    $cidev_strlen = strlen($product_info["productcode"]) - 1;

    $new_sku = "";
    for ($i = 0; $i < strlen($product_info["productcode"]); $i++) {
        $new_sku .= substr($product_info["productcode"], $cidev_strlen, 1);
        $cidev_strlen--;
    }
    $product_info["productcode"] = $new_sku;
}
Profiler::getInstance()->addPoint();
if ($config["Product_Page"]["cidev_show_products_image"] != "Y") {
    $product_info["tmbn_url"] = $product_info["tmbn_url_T"];
    $product_info["image_x"] = $product_info["image_x_T"];
    $product_info["image_y"] = $product_info["image_y_T"];
}

if (intval($cat) == 0) {
    $cat = $product_info["categoryid"];
}

$main = "product";
$smarty->assign("main", $main);

if (!empty($product_info["productid"])) {
    if (empty($product_info['descr'])) {
        $product_info['meta_descr'] = trim(strip_tags(func_get_product_descr($product_info['fulldescr'])));
    } else {
        $product_info['meta_descr'] = trim(strip_tags($product_info['descr']));
    }

    if (trim(strtoupper(substr($product_info['meta_descr'], 0, 10))) == 'FEATURES:.') {
        $product_info['meta_descr'] = trim(substr_replace($product_info['meta_descr'], '', 0, 10));
    }

    $product_info['meta_keywords'] = '';
    if ($product_info["free_ship_zone"] < 0) {
        $product_info["free_ship_text"] = "";
    }
}

/*include $xcart_dir.DIR_CUSTOMER."/send_to_friend.php";

if (!empty($send_to_friend_info)) {
	$smarty->assign("send_to_friend_info", $send_to_friend_info);
	if (!empty($active_modules['Image_Verification'])) {
		$smarty->assign("antibot_err", $send_to_friend_info['antibot_err']);
	}
	x_session_unregister("send_to_friend_info");
}
*/
if (!empty($active_modules["Detailed_Product_Images"]))
    include $xcart_dir . "/modules/Detailed_Product_Images/product_images.php";

if (!empty($active_modules["Magnifier"]))
    include $xcart_dir . "/modules/Magnifier/product_magnifier.php";

if (!empty($active_modules["Product_Options"]))
    include $xcart_dir . "/modules/Product_Options/customer_options.php";

if (!empty($active_modules["Advanced_Statistics"]) && !defined("IS_ROBOT"))
    include $xcart_dir . "/modules/Advanced_Statistics/prod_viewed.php";

if ($active_modules["Brands"])
    include $xcart_dir . "/modules/Brands/customer_brands.php";
else
    if ($active_modules["Manufacturers"])
        include $xcart_dir . "/modules/Manufacturers/customer_manufacturers.php";

$product_info["customer_service_email"] = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");

Profiler::getInstance()->addPoint();

if ($product_info["product_type"] != "C") {
    #
    # If this product is not configurable
    #
    if ($config["General"]["disable_outofstock_products"] == "Y" && empty($product_info['distribution'])) {
        $is_avail = true;
        if ($product_info['avail'] <= 0 && empty($variants)) {
            $is_avail = false;
        } elseif (!empty($variants)) {
            $is_avail = false;
            foreach ($variants as $v) {
                if ($v['avail'] > 0) {
                    $is_avail = true;
                    break;
                }
            }
        }

        if (!empty($cart['products']) && !$is_avail) {
            foreach ($cart['products'] as $v) {
                if ($product_info['productid'] == $v['productid']) {
                    $is_avail = true;
                    break;
                }
            }
        }

        if (!$is_avail) {
            func_header_location("error_message.php?access_denied&id=44");
        }
    }

    if (!empty($active_modules["Extra_Fields"])) {
        $extra_fields_provider = $product_info["provider"];
        include $xcart_dir . "/modules/Extra_Fields/extra_fields.php";
    }

    if (!empty($active_modules["Subscriptions"])) {
        $_products = $products;
        $products = array($product_info);
        include_once $xcart_dir . "/modules/Subscriptions/subscription.php";
        $products = $_products;
    }

    if (!empty($active_modules["Feature_Comparison"]))
        include $xcart_dir . "/modules/Feature_Comparison/product.php";


    if ($product_info["new_map_price"] == "0") {

        if (!empty($active_modules["Wholesale_Trading"]) && empty($product_info['variantid'])) {
            include $xcart_dir . "/modules/Wholesale_Trading/product.php";

            if (!empty($wresult) && is_array($wresult) && $product_info["min_amount"] > 0 && $wresult[0]["quantity"] == $product_info["min_amount"]) {
                $product_subtotal_value = $wresult[0]["price"] * $wresult[0]["quantity"];
                $smarty->assign("product_subtotal_value", $product_subtotal_value);
            }
        }
    }


    if (!empty($active_modules['Product_Configurator']) && !empty($_GET['pconf'])) {
        include $xcart_dir . "/modules/Product_Configurator/slot_product.php";
    }

}

if (!empty($active_modules["Recommended_Products"]))
    include "./recommends.php";

if (!empty($active_modules["SnS_connector"]))
    include $xcart_dir . "/modules/SnS_connector/product.php";

//include "./vote.php";

//require $xcart_dir."/include/categories.php";
Profiler::getInstance()->addPoint();
if ($oProduct && $oCategory) {
    if (!$oCategory->isRoot()) {
        foreach ($oCategory->getObjects()->parents() as $model) {
            $location[] = [
                $model->getFrontendName(),
                $model->getAbsoluteUrl(),
            ];
        }
    }
    $location[] = [
        $oCategory->getFrontendName(),
        $oCategory->getAbsoluteUrl(),
    ];

}
Profiler::getInstance()->addPoint();
$location[] = [$oProduct->getFrontendName()];

if (!empty($product_info)) {
    if (is_array($location) && !empty($location)) {
        if (is_array($location)) {
            foreach (array_reverse($location) as $l) {
                $product_info['meta_keywords'] .= $l[0] . ', ';
            }
            $product_info['meta_keywords'] = trim(strip_tags(substr($product_info['meta_keywords'], 0, strlen($product_info['meta_keywords']) - 2)));
        }
    }
}

if (!empty($active_modules["Special_Offers"])) {
    include $xcart_dir . "/modules/Special_Offers/product_offers.php";
}

$show_dimensions = false;
foreach (array('dim_x', 'dim_y', 'dim_z') as $k) {
    $show_dimensions = !empty($product_info[$k]);
    if ($show_dimensions) {
        break;
    }
}

if ($show_dimensions) {
    $show_dimensions_orderby = array();
    foreach (array('dim_x', 'dim_y', 'dim_z') as $k) {
        if (!empty($product_info[$k])) {
            $show_dimensions_orderby[] = $product_info[$k];
        }
    }

    if (!empty($show_dimensions_orderby)) {
        arsort($show_dimensions_orderby);
        foreach ($show_dimensions_orderby as $k => $v) {
            $show_dimensions_orderby[$k] = $v . '"';
        }

        $show_dimensions_orderby_str = implode(" x ", $show_dimensions_orderby);
        $smarty->assign('show_dimensions_orderby_str', $show_dimensions_orderby_str);
    }
}
Profiler::getInstance()->addPoint();

$smarty->assign('show_dimensions', $show_dimensions);

$show_shipping_dimensions = false;
foreach (array('shipping_dim_x', 'shipping_dim_y', 'shipping_dim_z') as $k) {
    $show_shipping_dimensions = !empty($product_info[$k]);
    if ($show_shipping_dimensions) {
        break;
    }
}

if ($show_shipping_dimensions) {
    $show_shipping_dimensions_orderby = array();
    foreach (array('shipping_dim_x', 'shipping_dim_y', 'shipping_dim_z') as $k) {
        if (!empty($product_info[$k])) {
            $show_shipping_dimensions_orderby[] = $product_info[$k];
        }
    }

    if (!empty($show_shipping_dimensions_orderby)) {
        arsort($show_shipping_dimensions_orderby);
        foreach ($show_shipping_dimensions_orderby as $k => $v) {
            $show_shipping_dimensions_orderby[$k] = $v . '"';
        }

        $show_shipping_dimensions_orderby = implode(" x ", $show_shipping_dimensions_orderby);
        $smarty->assign('show_shipping_dimensions_orderby', $show_shipping_dimensions_orderby);
    }
}

$smarty->assign('show_shipping_dimensions', $show_shipping_dimensions);


if (!empty($product_info['manufacturerid'])) {
    $product_info['manufact_text_displayed'] = $oManufacturer->manufact_text_displayed;
    $product_info['cart_manufact_text_displayed'] = $oManufacturer->cart_manufact_text_displayed;
}


$cidev_pos = strpos($product_info["cart_manufact_text_displayed"], "<s3-tab>");
if (!empty($product_info["cart_manufact_text_displayed"]) && $cidev_pos !== false) {

    $cidev_cart_manufact_text_displayed_arr = explode("<s3-tab>", $product_info["cart_manufact_text_displayed"]);

    $cidev_make_array_values = false;
    if (!empty($cidev_cart_manufact_text_displayed_arr) && is_array($cidev_cart_manufact_text_displayed_arr)) {
        foreach ($cidev_cart_manufact_text_displayed_arr as $k => $v) {
            if (empty($v) || trim($v) == "") {
                unset($cidev_cart_manufact_text_displayed_arr[$k]);
                $cidev_make_array_values = true;
            }
        }
    }

    if ($cidev_make_array_values) {
        $cidev_cart_manufact_text_displayed_arr = array_values($cidev_cart_manufact_text_displayed_arr);
    }

    $cart_manufact_text_displayed_tabs = array();
    $cart_manufact_text_displayed_tabs_index = 0;
    if (!empty($cidev_cart_manufact_text_displayed_arr) && is_array($cidev_cart_manufact_text_displayed_arr)) {
        foreach ($cidev_cart_manufact_text_displayed_arr as $k => $v) {
            $cidev_pos2 = strpos($v, "</s3-tab>");
            if ($cidev_pos2 !== false) {
                $cart_manufact_text_displayed_tabs[$cart_manufact_text_displayed_tabs_index] = explode("</s3-tab>", $v);
                $cart_manufact_text_displayed_tabs_index++;
            }
        }
    }
}

if (empty($cart_manufact_text_displayed_tabs) && !empty($product_info["cart_manufact_text_displayed"])) {
    $cart_manufact_text_displayed_tabs[0][0] = "Shipping information";
    $cart_manufact_text_displayed_tabs[0][1] = $product_info["cart_manufact_text_displayed"];
}

$product_tabs[0]["title"] = "Product description";
$product_tabs[0]["tpl"] = "_product_description_";
$product_tabs[0]["anchor"] = 0;

if (!empty($brandid_brands_info[$product_info["brandid"]]["descr"])) {

    $brand_image = func_image_properties("B", $product_info["brandid"]);
    if (!empty($brand_image["filename"])) {
        $smarty->assign("brand_image", $brand_image);
    }

    $product_tabs[1]["title"] = "Brand";
    $product_tabs[1]["tpl"] = "_Brand_";
    $product_tabs[1]["anchor"] = 1;
}

Profiler::getInstance()->addPoint();

if (!empty($cart_manufact_text_displayed_tabs) && is_array($cart_manufact_text_displayed_tabs)) {
    $count_product_tabs = count($product_tabs);
    foreach ($cart_manufact_text_displayed_tabs as $k => $v) {
        $product_tabs[$k + $count_product_tabs]["title"] = $v[0];
        $product_tabs[$k + $count_product_tabs]["tpl"] = $v[1];
        $product_tabs[$k + $count_product_tabs]["anchor"] = $k + $count_product_tabs;
    }
}

$product_info["product_questions"] = func_query_param(/** @lang MySQL */
    "SELECT * FROM xcart_product_question WHERE question_published_on_page='Y' AND productid=:productid ORDER BY order_by", ['productid' => $productid]);

if (!empty($product_info["product_questions"]) && is_array($product_info["product_questions"])) {

    foreach ($product_info["product_questions"] as $k => $v) {

        if (!empty($v["login"])) {
            $operator_name = func_query_first_cell_param(/** @lang MySQL */
                "SELECT firstname FROM xcart_customers WHERE login=:login", ['login' => $v["login"]]);
            $operator_name = trim($operator_name);
            $operator_first_name_arr = explode(" ", $operator_name);
            $operator_first_name = $operator_first_name_arr[0];
            $product_info["product_questions"][$k]["operator_name"] = $operator_name;
            $product_info["product_questions"][$k]["operator_first_name"] = $operator_first_name;
        }

        if (empty($v["answered_date"])) {
            $answered_date = $v["date"];
            $answered_date_str = date("N", $answered_date);
            if ($answered_date_str <= 4 || $answered_date_str == "7") {
                $answered_date += 60 * 60 * 24;
            } elseif ($answered_date_str == "6") {
                $answered_date += 60 * 60 * 24 * 2;
            }
            $product_info["product_questions"][$k]["answered_date"] = $answered_date;
            db_query_param(/** @lang MySQL */
                "UPDATE xcart_product_question SET answered_date=:answered_date WHERE id=:id", ['answered_date' => $answered_date, 'id' => $v['id']]);
        }
    }
}
if ($config['product_question_email']['product_question_enable'] == 'Y') {
    $count_product_tabs = count($product_tabs);
    $product_tabs[$count_product_tabs]["title"] = "Product questions " . (($product_info["product_questions"] && count($product_info["product_questions"]) > 0) ? "(" . count($product_info["product_questions"]) . ")" : '');
    $product_tabs[$count_product_tabs]["tpl"] = "_product_question_tpl_";
    $product_tabs[$count_product_tabs]["anchor"] = $count_product_tabs;
}

Profiler::getInstance()->addPoint();

if (!empty($product_tabs) && is_array($product_tabs)) {

    if (!$oProduct->distributor->hasDefaultShippingZone()) {
        if ($ca = DistributorHelper::getShippingCountries($oProduct->manufacturerid)) {

            $c_str = implode(array_map(function ($a) {
                return func_get_langvar_by_name('country_' . $a->code);
            }, $ca), ' or ');

            foreach ($product_tabs as $k => $v) {
                if ($v["title"] == "Shipping") {
                    $product_tabs[$k]["tpl"] .= "<span class='ErrorMessage'>This product can only be shipped to a {$c_str} address.</span>";
                }
            }
        }
    }

    $smarty->assign('product_tabs', $product_tabs);
    $smarty->assign('count_product_tabs', $count_product_tabs);
}

if (!empty($product_info["brandid"])) {
    $product_info["cidev_brand_name"] = func_query_first_cell("SELECT brand FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");
}


$cidev_warning_code = 0;

if ($product_info["list_price"] > 0) {
    if (($product_info["price"] / $product_info["list_price"]) < 0.1) {
        $cidev_warning_code = "101";
    }
}

if ($product_info["cost_to_us"] > $product_info["price"]) {
    $cidev_warning_code = "102";
}

if ($cidev_warning_code > 0) {
    if ($product_info["warning_code"] != $cidev_warning_code) {
//	        db_query("UPDATE $sql_tbl[products] SET warning_code='$cidev_warning_code' WHERE productid='$product_info[productid]'");
        $product_info["warning_code"] = $cidev_warning_code;
    }

    /*	$product_info["avail"] = 0;*/
}
###
##
#

if (empty($product_info["lead_time_message"])) {
    $lead_time_message = $oManufacturer->lead_time_message;

    $lead_time_message = str_replace("'", "\'", $lead_time_message);
    $lead_time_message = str_replace('"', "\'", $lead_time_message);

    $product_info["lead_time_message"] = $lead_time_message;
}


Profiler::getInstance()->addPoint();
if (!empty($cart["shipping_groups"][$product_info["manufacturerid"]])) {
    if (!empty($cart["shipping_groups"][$product_info["manufacturerid"]]["need_add_more"]) && !empty($cart["shipping_groups"][$product_info["manufacturerid"]]["d_minimum_order_amount_in_us"]) && $cart["shipping_groups"][$product_info["manufacturerid"]]["d_minimum_order_amount_in_us"] > $product_info["taxed_price"]) {

        $product_info["lbl_minimum_order_amount_message_product"] = "Y";
        $product_info["d_minimum_order_amount_in_us"] = $cart["shipping_groups"][$product_info["manufacturerid"]]["d_minimum_order_amount_in_us"];
    }
} else {

    $d_minimum_order_amount_in_us = $oManufacturer->d_minimum_order_amount_in_us;
    $d_minimum_order_amount = $oManufacturer->d_minimum_order_amount;
    $d_for_orders_below_min_order_amount = $oManufacturer->d_for_orders_below_min_order_amount;

    if ($d_minimum_order_amount_in_us != "0.00" && $d_minimum_order_amount == "applies_to_all_orders" && $d_for_orders_below_min_order_amount == "are_rejected") {

        if ($product_info["taxed_price"] < $d_minimum_order_amount_in_us) {
            $product_info["lbl_minimum_order_amount_message_product"] = "Y";
            $product_info["d_minimum_order_amount_in_us"] = $d_minimum_order_amount_in_us;
        }
    }
}

if ($current_storefront_info["storefrontid"] == "50") {

    $br_str = array("<br>", "<br/>", "</br>", "</ br>", "<Br>", "<Br/>", "<Br />", "</Br>", "</ Br>", "<BR>", "<BR/>", "<BR />", "</BR>", "</ BR>");
    $fulldescr = str_replace($br_str, "<br />", $product_info["fulldescr"]);

    $pos_fulldescr_1 = strpos($fulldescr, '*');

    if ($pos_fulldescr_1 !== false) {
        $pos_fulldescr_2 = strpos($fulldescr, '<br />', $pos_fulldescr_1);

        if ($pos_fulldescr_2 !== false) {

            $fulldescr = substr_replace($fulldescr, '<ul><li>', $pos_fulldescr_1, 1);
            $fulldescr = str_replace("*", "</li><li>", $fulldescr);

            $fulldescr_arr = explode("<br />", $fulldescr);
            $count_fulldescr_arr = count($fulldescr_arr) - 1;
            $fulldescr_arr[$count_fulldescr_arr] = $fulldescr_arr[$count_fulldescr_arr] . "</li></ul>";
            $fulldescr = implode("<br />", $fulldescr_arr);

            $product_info["fulldescr"] = $fulldescr;
        }
    }
}
$smarty->assign("product", $product_info);

$smarty->assign("product_feed_enabled", $product_info["supplier_feeds_enabled"]);

if ($active_modules["Bestsellers"])
    include $xcart_dir . "/modules/Bestsellers/bestsellers.php";


global $xcart_dir;
$smarty->assign("cidev_mpn", $oProduct->getMPN());
$smarty->assign("oProduct", $oProduct);

if (!empty($location) && is_array($location)) {
    $tmp_count_location = count($location);
    $cat_for_itemscope1 = $tmp_count_location - 2;
    $cat_for_itemscope2 = $tmp_count_location - 3;

    if (!empty($location[$cat_for_itemscope1])) {
        $cat_for_itemscope[$cat_for_itemscope1] = $location[$cat_for_itemscope1][0];
        $smarty->assign("cat_name_for_itemprop", $location[$cat_for_itemscope1][0]);
    }

    if (!empty($location[$cat_for_itemscope2])) {
        $cat_for_itemscope[$cat_for_itemscope2] = $location[$cat_for_itemscope2][0];
    }

    if (!empty($cat_for_itemscope)) {
        $smarty->assign("cat_for_itemscope", $cat_for_itemscope);
    }
}

if (!empty($product_info["supplier_internal_id_last_parsed_update"])) {
    $count_days = (time() - $product_info["supplier_internal_id_last_parsed_update"]) / (60 * 60 * 24);
}

Profiler::getInstance()->addPoint();

if ($product_info["manufacturerid"] == "32" && !empty($product_info["supplier_internal_id"]) && !empty($product_info["supplier_internal_option"]) && $count_days > 10) {


    $url = "http://www.aajewelry.com/quickshop/product/view/id/" . $product_info["supplier_internal_id"] . "/?keepThis=true&width=650&height=500&modal=false";
    $error_found = false;
    $make_redirect = false;


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
    $output = curl_exec($ch);


    if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
        $error_found = true;
    }

    if (curl_errno($ch) == 0 && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404) {
        db_query("UPDATE $sql_tbl[products] SET forsale='N' WHERE productid='$productid'");
        $make_redirect = true;
        $error_found = true;
    }

    if (!$error_found) {

        $output = str_replace(array("\n", "\r"), '', $output);

        $new_eta_date_mm_dd_yyyy = $product_info["eta_date_mm_dd_yyyy"];
        $new_r_avail = $product_info["r_avail"];
        $new_cost_to_us = $product_info["cost_to_us"];
        $new_list_price = $product_info["list_price"];
        $new_min_amount = $product_info["min_amount"];
        $new_new_map_price = $product_info["new_map_price"];
        $new_discount_table = $product_info["discount_table"];


        $loadproduct = func_GetAAJ_product_info($product_info["supplier_internal_id"], $product_info["supplier_internal_option"]);

        if (empty($loadproduct["min_amount"]) || $loadproduct["instock"] == "N") {
            $new_r_avail = 0;
            $new_eta_date_mm_dd_yyyy = time() + 60 * 60 * 24 * 10;
        } elseif ($loadproduct["instock"] == "Y" && $loadproduct["min_amount"] > 0) {
            $new_r_avail = 10000;
            $new_min_amount = $loadproduct["min_amount"];
            $new_mult = $loadproduct["mult_order_quantity"];
            $new_eta_date_mm_dd_yyyy = "";

            if (!empty($loadproduct["discount_table"])) {
                $new_discount_table = $loadproduct["discount_table"];
            }

            if (isset($loadproduct["list_price"])) {
                $new_list_price = $loadproduct["list_price"];
            }

            if (isset($loadproduct["cost_to_us"])) {
                $new_cost_to_us = $loadproduct["cost_to_us"];
            }
        }


        if ($new_mult != $product_info["mult_order_quantity"] || $new_eta_date_mm_dd_yyyy != $product_info["eta_date_mm_dd_yyyy"] || $product_info["r_avail"] != $new_r_avail || $product_info["cost_to_us"] != $new_cost_to_us || $new_discount_table != $product_info["discount_table"] || $new_new_map_price != $product_info["new_map_price"] || $new_min_amount != $product_info["min_amount"] || $new_list_price != $product_info["list_price"]) {

            db_query("UPDATE $sql_tbl[products] SET mult_order_quantity ='$new_mult', r_avail='$new_r_avail', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy', supplier_internal_id_last_parsed_update='" . time() . "', supplier_internal_id_last_parsed='" . time() . "', cost_to_us='$new_cost_to_us', discount_table='$new_discount_table', new_map_price='$new_new_map_price', list_price='$new_list_price', min_amount='$new_min_amount' WHERE productid='$productid'");

            $make_redirect = true;

            if ($product_info["discount_table"] != $new_discount_table) {
                func_generate_discounts(array("$productid"));
            }
        }
    }

    curl_close($ch);

    if ($make_redirect) {
        $url = func_clean_url_get("P", $productid);
        func_header_location($url);
    }
}

Profiler::getInstance()->addPoint();
if ($config["Appearance"]["Enable_surf_stats"] == "Y") {
    Modules\User\Helpers\SurfingHelper::logSurfPath([
        'resource_type' => Modules\User\Models\SurfPathModel::GOAL_TYPE_PRODUCT,
        'resource_id' => $productid,
    ]);
}

Profiler::getInstance()->addPoint();
if (\Modules\Shipping\Helpers\ShippingHelper::isCalcShippingEnabled($oProduct)) {
    $smarty->assign('shipping_rate_show', true);
}

Profiler::getInstance()->addPoint();

x_session_register("notify_email");
$smarty->assign("notify_email", $notify_email);

$smarty->assign("ga_page_name", "detail_page");

# Assign the current location line
$smarty->assign("location", $location);

Profiler::getInstance()->addPoint();


//func_display_cached("customer/home.tpl", 'product_' . $productid);
$output = func_display("customer/home.tpl", $smarty, false);

if ($cache_middle = \Xcart\App\Main\Xcart::app()->middleware->getMiddleware('static_cache')) {
    /** @var \Modules\Core\Middleware\CacheMiddleware $cache_middle */
    $cache_middle->processSave($output);
}

echo $output;

Profiler::getInstance()->addPoint();
Profiler::getInstance()->stop('trace');
