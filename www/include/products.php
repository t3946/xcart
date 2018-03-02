<?php

use Modules\Goods\Models\ProductModel;

function func_products_globals(array $product_models = [])
{
    global $active_modules, $config, $sql_tbl, $shop_language, $login;

    $ids = array_map(function($model){return $model->pk;}, $product_models);
    $products = array_map(function($model){return $model->getAttributes(); }, $product_models);

    # Get Extra fields cache
    if (!empty($active_modules['Extra_Fields'])) {
        $GLOBALS['products_ef'] = func_query_hash("SELECT $sql_tbl[extra_fields].*, $sql_tbl[extra_field_values].*, IF($sql_tbl[extra_fields_lng].field != '', $sql_tbl[extra_fields_lng].field, $sql_tbl[extra_fields].field) as field FROM $sql_tbl[extra_field_values], $sql_tbl[extra_fields] LEFT JOIN $sql_tbl[extra_fields_lng] ON $sql_tbl[extra_fields].fieldid = $sql_tbl[extra_fields_lng].fieldid AND $sql_tbl[extra_fields_lng].code = '$shop_language' WHERE $sql_tbl[extra_fields].fieldid = $sql_tbl[extra_field_values].fieldid AND $sql_tbl[extra_field_values].productid IN (" . implode(",", $ids) . ") AND $sql_tbl[extra_fields].active = 'Y' ORDER BY $sql_tbl[extra_fields].orderby", "productid");
    }

    $GLOBALS['thumb_dims'] = func_query_hash("SELECT id, image_x, image_y FROM $sql_tbl[images_T] WHERE id IN ('" . implode("','", $ids) . "')", "id", false);
    $GLOBALS['_taxes']  = func_get_product_tax_rates(array_map(function($id){ return ['productid' => $id]; }, $ids), $login);


    $brandids_in_found_products = $manufacturerids_in_found_products = $brands_in_found_products = [];

    foreach ($products as $k => $v) {
        $ids[] = $v['productid'];

        if (!in_array($v["brandid"], $brandids_in_found_products)) {
            $brandids_in_found_products[] = $v["brandid"];
        }

        if (!in_array($v["manufacturerid"], $manufacturerids_in_found_products)) {
            $manufacturerids_in_found_products[] = $v["manufacturerid"];
        }
    }

    if ($brand_profucts_filtered = array_filter($brandids_in_found_products)) {
        $brands_in_found_products = func_query_hash("SELECT $sql_tbl[brands].brand, $sql_tbl[brands].brandid FROM $sql_tbl[brands] WHERE $sql_tbl[brands].brandid IN (" . implode(",", $brand_profucts_filtered) . ")", "brandid", false);
    }

    $GLOBALS['brands_in_found_products'] = $brands_in_found_products;

//    $manufacturers_in_found_products = $manufacturers_in_found_products ?: [];
//
//    if ($manufacturerids_filtered = array_filter($manufacturerids_in_found_products)) {
//        $manufacturers_in_found_products = func_query_hash("SELECT manufacturerid, allow_pre_orders, reverse_sku, remove_dashes, lead_time_message FROM $sql_tbl[manufacturers] WHERE manufacturerid IN ('" . implode("','", $manufacturerids_filtered) . "')", 'manufacturerid', false);
//    }

}

function func_product_prepare(ProductModel $model, array $ids = [])
{
    global $active_modules, $config, $sql_tbl, $products_ef, $thumb_dims, $login, $manufacturers_in_found_products, $xcart_dir, $_taxes,$current_area, $brands_in_found_products, $cart;


    $product = $model->getAttributes();

    if (empty($v['descr'])) {
        $product['descr'] = func_get_product_descr($v['fulldescr']);
    }

    if (trim(strtoupper(substr($product['descr'], 0, 10))) == 'FEATURES:.') {
        $product['descr'] = trim(substr_replace($product['descr'], '', 0, 10));
    }
    if (!empty($active_modules['Feature_Comparison']) && $v['fclassid']) {
        $products_has_fclasses = true;
    }

    $product['taxed_price'] = $v['taxed_price'] = $v['price'];

    if (!empty($active_modules['Product_Options']) && !empty($v['is_product_options']) && !empty($options_markups[$v['productid']])) {

        # Add product options markup
        $product['price'] += $options_markups[$v['productid']];
        $product['taxed_price'] = $product['price'];
        $v = $product;
    }

    $in_cart = 0;
    if (!empty($cart['products']) && is_array($cart['products'])) {

        # Modify product's quantity based the cart data
        foreach ($cart['products'] as $cv) {
            if ($cv['productid'] == $v['productid'] && intval($v['variantid']) == intval($cv['variantid']))
                $in_cart += $cv['amount'];
        }

        $product['in_cart'] = $in_cart;
        $product['avail'] -= $in_cart;
        if ($product['avail'] < 0) {
            $product['avail'] = 0;
        }
    }

    if (!empty($active_modules['Extra_Fields']) && isset($products_ef[$v['productid']])) {
        # Get extra fields data
        $product['extra_fields'] = $products_ef[$v['productid']];
    }

    # Get thumbnail URL
    $product["tmbn_url"] = false;
    if (!is_null($v['image_path_T'])) {
        $product['is_image_T'] = true;
        if (!empty($v['image_path_T'])) {
            x_load("files");
            $product["tmbn_url"] = func_get_image_url($v['productid'], "T");
        }

        if (isset($thumb_dims[$v['productid']])) {
            $product = func_array_merge($product, $thumb_dims[$v['productid']]);
            unset($thumb_dims[$v['productid']]);

            $config['Appearance']['thumbnail_width'] = intval($config['Appearance']['thumbnail_width']);
            $product['tmbn_x'] = $product['image_x'];
            $product['tmbn_y'] = $product['image_y'];
            if ($config['Appearance']['thumbnail_width'] > 0) {
                $product['tmbn_x'] = intval($config['Appearance']['thumbnail_width']);
                if (!empty($product['image_x']) && !empty($product['image_y']))
                    $product['tmbn_y'] = round($config['Appearance']['thumbnail_width'] / $product['image_x'] * $product['image_y'], 0);
            }
        }

    }

    if (empty($product["tmbn_url"])) {
        $product["tmbn_url"] = func_get_default_image("T");
    }

    unset($product['image_path_T']);

    # Calculate product taxes
    if (!empty($active_modules["Special_Offers"]) && !empty($search_data["products"]["show_special_prices"])) {
        include $xcart_dir . "/modules/Special_Offers/search_results_calculate.php";
    } elseif ($v['is_taxes'] == 'Y' && isset($_taxes[$v['productid']])) {
        $product["taxes"] = func_get_product_taxes($product, $login, false, $_taxes[$v['productid']]);
    }

    if ($product['descr'] == strip_tags($product['descr']))
        $product['descr'] = str_replace("\n", "<br />", $product['descr']);
    if ($product['fulldescr'] == strip_tags($product['fulldescr']))
        $product['fulldescr'] = str_replace("\n", "<br />", $product['fulldescr']);


    if ($v["new_map_price"] > 0) {

        if ($v["new_map_price"] > $product["price"]) {
            $product["price"] = $v["new_map_price"];
            $product['taxed_price'] = $product['price'];
        }

        $product["discount_avail"] = "N";
        $product["discount_slope"] = "";
        $product["discount_table"] = "";
    }

#
## https://basecamp.com/2070980/projects/1577907-x-cart/messages/13257251-internal-sf-tasks
###
    $cidev_warning_code = 0;

    if ($v["list_price"] > 0) {
        if (($v["price"] / $v["list_price"]) < 0.1) {
            $cidev_warning_code = "101";
        }
    }

    if ($v["cost_to_us"] > $v["price"]) {
        $cidev_warning_code = "102";
    }

    if ($cidev_warning_code > 0) {
        if ($v["warning_code"] != $cidev_warning_code) {
            $product["warning_code"] = $cidev_warning_code;
        }
        $product["avail"] = 0;
    }

    $product["allow_pre_orders"] = $manufacturers_in_found_products[$v["manufacturerid"]]["allow_pre_orders"];

    if ($manufacturers_in_found_products[$v["manufacturerid"]]["remove_dashes"] == "Y") {
        $product["productcode"] = str_replace("-", ".", $product["productcode"]);
    }

    if ($manufacturers_in_found_products[$v["manufacturerid"]]["reverse_sku"] == "Y") {

        $cidev_strlen = strlen($product["productcode"]) - 1;

        $new_sku = "";
        for ($i = 0; $i < strlen($product["productcode"]); $i++) {
            $new_sku .= substr($product["productcode"], $cidev_strlen, 1);
            $cidev_strlen--;
        }
        $product["productcode"] = $new_sku;
    }

    if (!empty($v["eta_date_mm_dd_yyyy"])) {
        if ($product["eta_date_mm_dd_yyyy"] > time()) {
            $product["eta_date_in_future"] = "Y";

            if ($current_area == 'C' && $manufacturers_in_found_products[$v["manufacturerid"]]["allow_pre_orders"] != "Y") {
                $product["avail"] = "0";
            }
        }
    }

    if (empty($v["lead_time_message"])) {
        $product["lead_time_message"] = $manufacturers_in_found_products[$v["manufacturerid"]]["lead_time_message"];
    }


    $product["product_availability"] = func_product_availability(false, $product);
    $product["supplier_feeds_enabled"] = func_query_first_cell("SELECT enabled FROM $sql_tbl[supplier_feeds] WHERE manufacturerid='$v[manufacturerid]' AND feed_type = 'I' AND enabled='Y' AND (multiple_feed_destinations!='Y' OR (multiple_feed_destinations='Y' AND feed_file_name='" . $v["controlled_by_feed"] . "'))");
    $product["price"] = $product["taxed_price"] = func_product_price($product);

    if ($product["supplier_feeds_enabled"] == "Y" && empty($v["is_variants"]) && $product["product_availability"] == "out of stock") {
        $new_notify_in_stock_price = $product["price"];
        $product["new_notify_in_stock_price"] = $new_notify_in_stock_price;
    }
//    $tmp_absolute_path = true;

//    $clean_url = func_clean_url_get("P", $v["productid"], $tmp_absolute_path);

//    if ($index_sku_search == "Y") {
//        $clean_url = "http://" . $v["domain"] . "/" . $clean_url;
//
//        if (strpos($product["tmbn_url"], "cdn") !== false && strpos($product["tmbn_url"], "http") === false) {
//            $product["tmbn_url"] = "http://" . $product["tmbn_url"];
//        }
//    }

    $product["clean_url"] = $model->getAbsoluteUrl();


    if ($product['splash_id']) {
        $oSplash = \Xcart\Images\Splash::objects()->filter(['id' => (int) $product['splash_id']])->get();
        if ($oSplash) {
            $product['oSplash'] = $oSplash;
        }
    }

    $product["brand"] = $brands_in_found_products[$v["brandid"]]["brand"];


    return $product;
}