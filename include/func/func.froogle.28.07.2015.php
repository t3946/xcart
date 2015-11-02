<?php
if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

#
# Translation string to frogle-compatibility-string
#
function func_froogle_convert($str, $max_len = false) {
        static $tbl = false;


        if ($tbl === false)
                $tbl = array_flip(get_html_translation_table(HTML_ENTITIES));

        $str = str_replace(array("\n","\r","\t"), array(" ", "", " "), $str);
        $str = strip_tags($str);
        $str = strtr($str, $tbl);

        if ($max_len > 0 && strlen($str) > $max_len) {
                $str = preg_replace("/\s+?\S+.{".intval(strlen($str)-$max_len-1+FROOGLE_TAIL_LEN)."}$/Ss", "", $str).FROOGLE_TAIL;
                if (strlen($str) > $max_len)
                        $str = substr($str, 0, $max_len-FROOGLE_TAIL_LEN).FROOGLE_TAIL;
        }

        return $str;
}

function GetGoogleBaseOneRow($productid, $scrip_name=""){
	global $sql_tbl, $xcart_dir, $active_modules, $config, $https_location, $http_location;

	if (empty($productid)){
//		$row = "title\tdescription\tlink\tadwords_redirect\tadwords_grouping\tadwords_labels\timage link\tadditional image link\tid\tprice\tpayment accepted\tpayment notes\tquantity\tweight\texpiration date\tbrand\tcondition\tproduct type\tmpn\tmodel number\tgtin\tcompatible with\tonline only\tshipping\tavailability\tmultipack\tgoogle product category\n";
		$row = "title\tdescription\tlink\tadwords_redirect\timage link\tadditional image link\tid\tprice\tshipping weight\texpiration date\tbrand\tcondition\tproduct type\tmpn\tgtin\tshipping\tavailability\tmultipack\tgoogle product category\n";
		return $row;
	}

        $froogle_location = $config['Froogle']['froogle_used_https_links'] == 'Y' ? $https_location : $http_location;
        $froogle_scheme = $config['Froogle']['froogle_used_https_links'] == 'Y' ? 'https://' : 'http://';

	$where = "";
	$fields = "";
	$joins = "";

	if (!empty($active_modules['Multiple_Storefronts'])) {
		$fields .= ", $sql_tbl[products_sf].sfid";
		$joins .= " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
		$where .= " AND $sql_tbl[products_sf].productid = $productid";
	}

//	if ($config["General"]["disable_outofstock_products"] == "Y") {

	    if ($scrip_name == "main_google" || $scrip_name == "main_google_with_min_amount"){
		if (!empty($active_modules['Product_Options'])) {
			$where .= " AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) >= '0'";
		} else {
			$where .= " AND $sql_tbl[products].avail >= '0'";
		}
	    }
	    else {
                if (!empty($active_modules['Product_Options'])) {
                        $where .= " AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) > '0'";
                } else {
                        $where .= " AND $sql_tbl[products].avail > '0'";
                }
	    }

//	}

	$joins .= " INNER JOIN $sql_tbl[quick_prices] ON $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid = '0'";
	if (!empty($active_modules['Product_Options'])) {
		$joins .= " LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid";
		$fields .= ", IFNULL($sql_tbl[variants].productcode, $sql_tbl[products].productcode) as productcode, IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) as avail, IFNULL($sql_tbl[variants].weight, $sql_tbl[products].weight) as weight";
	}

	if (!empty($active_modules['Manufacturers'])) {
		$fields .= ", IF ($sql_tbl[manufacturers_lng].manufacturer != '', $sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer, $sql_tbl[manufacturers].d_enable_feed";
		$joins .= " LEFT JOIN $sql_tbl[manufacturers] ON $sql_tbl[products].manufacturerid = $sql_tbl[manufacturers].manufacturerid LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[products].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$froogle_lng'";
	}

	if (!empty($active_modules['Brands'])) {
		$fields .= ", IF ($sql_tbl[brands_lng].brand != '', $sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand";
		$joins .= " LEFT JOIN $sql_tbl[brands] ON $sql_tbl[products].brandid = $sql_tbl[brands].brandid LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[products].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$froogle_lng'";
	}

	$product = func_query_first($qqq="SELECT SQL_NO_CACHE $sql_tbl[products].*, $sql_tbl[categories].categoryid_path, $sql_tbl[pricing].price, $sql_tbl[images_T].image_path $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C')");


//func_print_r($product, $qqq);

//func_print_r($product, $config["General"]["disable_outofstock_products"]);
//die("1");


	if (empty($product))
		return;

	if ($scrip_name != "main_google"){
	        if ($product["min_amount"] > 1){
//        	        return;
		}
	}



	$sf_info = func_get_storefront_info($product['sfid'], 'ID', true);

	$product_categories = func_query_hash("SELECT $sql_tbl[products].productid, $sql_tbl[categories].categoryid_path FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[products]) WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' AND $sql_tbl[products].productid='$productid'", 'productid', true, true);

	if (!empty($product["eta_date_mm_dd_yyyy"])){
		$eta_date_mm_dd_yyyy_time_arr = explode("/", $product["eta_date_mm_dd_yyyy"]);
		if (!empty($eta_date_mm_dd_yyyy_time_arr) && is_array($eta_date_mm_dd_yyyy_time_arr)){
			$eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $eta_date_mm_dd_yyyy_time_arr[0], $eta_date_mm_dd_yyyy_time_arr[1], $eta_date_mm_dd_yyyy_time_arr[2]);
			if ($eta_date_mm_dd_yyyy_time > time()){
//				print"ETA date in future.";
//				return;
			}
		}
	}

	if(isset($product['sfid']) && $product['sfid'] != 0) {
		$product['froogle_location'] = $froogle_scheme . func_get_http_location_sf($product['sfid']);
	} else {
		$product['froogle_location'] = $froogle_location;
	}

	$tmp_upc = trim($product['upc']);
	$tmp_upc = isset($tmp_upc) ? abs(intval($tmp_upc)) : 0;
	if (empty($tmp_upc) || $tmp_upc == "0"){
		$product['upc'] = "";
	}

	$clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='P' AND resource_id='$product[productid]'");
	$clean_url_link .="/";

	$product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/'. $clean_url_link;

	if (!empty($sf_info['prefix'])){

		$utm_medium = $product['brand'];
		$utm_medium = preg_replace('/[^\w]/', '', $utm_medium);
		$utm_medium = preg_replace('/[_]/', '', $utm_medium);

		$utm_campaign = $product['productcode'];
		$utm_campaign = preg_replace('/[^\w]/', '', $utm_campaign);
		$utm_campaign = preg_replace('/[_]/', '', $utm_campaign);

		$product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/' . $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'froogle_Google-Shopping&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
		$product['adwords_redirect'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/' . $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'froogle_Product-Ads&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
		$product["adwords_grouping"] = $product['manufacturerid'];
		$product['page_url'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/'. $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'thefind&utm_medium=feed&utm_campaign='.$utm_campaign;
	}

	# Get google product category
	$gpc = func_query_first_cell(" SELECT C.google_product_category FROM $sql_tbl[categories] As C LEFT JOIN $sql_tbl[products_categories] As PC ON PC.categoryid = C.categoryid WHERE PC.productid = ".$product['productid']." and PC.main = 'Y'");

	# Define product category path
	$cats = array();
	if (is_array($product_categories) && isset($product_categories[$product['productid']]) && is_array($product_categories[$product['productid']])) {
		foreach ($product_categories[$product['productid']] as $kpc => $pc) {
			$catids = explode("/", $pc);
			if ($catids[0] == EXCLUDE_CATEGORYID_BRANCH) {
				continue;
			}

			if (!empty($catids)) {
				$cats[$kpc] = func_query("SELECT categoryid, category, google_product_category FROM $sql_tbl[categories] WHERE categoryid IN ('".implode("','", $catids)."') AND avail = 'Y'$sf_cat_condition");
				$catids = array_flip($catids);
				if (!empty($cats[$kpc])) {
					if (count($cats[$kpc]) != count($catids))
                                                    continue;

					foreach ($cats[$kpc] as $k => $v) {
                                                    if (isset($catids[$v['categoryid']])) {
                                                        if (trim($v['google_product_category']) != '') $gpc = $v['google_product_category'];
                                                        $catids[$v['categoryid']] = $v['category'];
                                                    }
					}

					$cats[$kpc] = str_replace("\t", ' ', implode(' > ', $catids));

				}
			}
		}
	}

	if (!empty($cats[0])){
		$cats_path = $cats[0];
        }

	$cats_path_for_thefind = !empty($cats) ? implode(',', $cats) : '';

	$cats_path = func_froogle_convert($cats_path, 1000);
	$cats_path = func_cidev_check_froogle_field($cats_path);
	$cats_path = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $cats_path);

	$cats_path_for_thefind = func_froogle_convert($cats_path_for_thefind, 1000);
	$cats_path_for_thefind = func_cidev_check_froogle_field($cats_path_for_thefind);
	$cats_path_for_thefind = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $cats_path_for_thefind);

	# Define full description
	if (!empty($product['fulldescr']))
		$product['descr'] = $product['fulldescr'];

	$product['descr'] = func_froogle_convert($product['descr'], 10000);
	$product['descr'] = func_cidev_check_froogle_field($product['descr']);
	$product['descr'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $product['descr']);

	$product['product'] = func_froogle_convert($product['product'], 70);
	$product['product'] = func_cidev_check_froogle_field($product['product']);
	$product['product'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $product['product']);

	# Define product image
	$tmp = func_query_first("SELECT id, image_path FROM $sql_tbl[images_P] WHERE $sql_tbl[images_P].id = '$product[productid]'");
	$tmbn = "";
	$image_path = "";
	$image_type = "";

	if (!empty($tmp['id'])) {
		$image_path = $tmp['image_path'];
		$image_type = "P";
	} elseif (!is_null($product['image_path'])) {
		$image_path = $product['image_path'];
		$image_type = "T";
	}

	if (!empty($image_type)) {
		if (!empty($image_path))
			$tmbn = func_get_image_url($product['productid'], $image_type, $image_path);
		if ($tmbn === false || empty($tmbn)) {
			$tmbn = $product['froogle_location'] . '/image.php?id=' . $product['productid'] . '&type=' . $image_type;
		} elseif (strpos($tmbn, $https_location) !== false) {
			$tmbn = str_replace($https_location, $product['froogle_location'], $tmbn);
		}
	}

	$ci = array(
		"city" => $config['General']['default_city'],
		"state" => $config['General']['default_state'],
		"country" => $config['General']['default_country'],
		"zipcode" => $config['General']['default_zipcode']
	);

	if (!empty($active_modules['Product_Options']))
		$product['price'] += func_get_default_options_markup($product['productid'], $product['price']);

	$tmp = func_tax_price($product['price'], $product['productid'], false, NULL, $ci);
	$product['price'] = $tmp['taxed_price'];

	if ($product["new_map_price"] > $product["price"]){
		$product["price"] = $product["new_map_price"];
		$product['taxed_price'] = $product['price'];
	}

	/*if ($product["min_amount"] > 1){
		$new_price =  func_query_first_cell("SELECT MIN(price) FROM $sql_tbl[pricing] WHERE $sql_tbl[pricing].quantity <= '$product[min_amount]' AND $sql_tbl[pricing].variantid = 0 AND $sql_tbl[pricing].productid = '$product[productid]'");
		$new_price *= $product["min_amount"];
		$new_price = func_tax_price($new_price, $product['productid'], false, NULL, $ci);

		$product["price"] = $new_price['taxed_price'];
		$product['taxed_price'] = $new_price['taxed_price'];
	}*/

	if (empty($cidev_number_clicks) || $cidev_number_clicks == 0){
		$cidev_number_clicks = $config["Froogle"]["froogle_number_clicks_last_used"];
	}

	if (empty($cidev_max_cpc_group) || $cidev_max_cpc_group == 0){
		$cidev_max_cpc_group = $config["Froogle"]["froogle_max_cpc_group_last_used"];
	}

	$CPC_group = price_format((max($product["new_map_price"], $product["price"]) - $product["cost_to_us"])/$cidev_number_clicks);

	$product['adwords_labels'] = $CPC_group."-cpc-group";

	if ($CPC_group >= $cidev_max_cpc_group){
		$product['adwords_labels'] = $cidev_max_cpc_group."-cpc-group";
	}

	if ($CPC_group <= 0){
		$product['adwords_labels'] = "0.01-cpc-group";
	}

	if ($product["list_price"] > 20 && (1 - ($product["price"]/$product["list_price"]))>0.50){
		$product['adwords_labels'] .= ", offlist";
	}

	# Define "mpn"
	$pos = strpos($product['productcode'], '-');
	$mpn = '';

	if ($pos && is_numeric($pos) && $pos + 1 != strlen($product['productcode'])) {
		$mpn = substr($product['productcode'], $pos + 1);
	}
	else {
		$mpn = $product['productcode'];
	}

	if (strlen($mpn) < 3){
		$mpn .= "-GBFIX";
	}

	# Define "compatible with"
	$upselling_products = func_query("SELECT p.product_froogle, p.productcode, p.upc, b.brand FROM $sql_tbl[product_links] as pl, $sql_tbl[products] as p LEFT JOIN $sql_tbl[brands] b ON b.brandid=p.brandid WHERE pl.productid1=$product[productid] AND p.productid=pl.productid2");

	$compatible_with = '';

	if (!empty($upselling_products) && is_array($upselling_products)) {

		foreach ($upselling_products as $up) {
			$tmp_upc = trim($up['upc']);
			$tmp_upc = isset($tmp_upc) ? abs(intval($tmp_upc)) : 0;
			if (empty($tmp_upc) || $tmp_upc == "0"){
				$up['upc'] = "";
			}

			$up_pos = strpos($up['productcode'], '-');
			$up_mpn = '';
			if ($up_pos && is_numeric($up_pos) && $up_pos + 1 != strlen($up['productcode'])) {
				$up_mpn = substr($up['productcode'], $up_pos + 1);
			}
			if ($compatible_with != '') {
				$compatible_with .= ', ';
			}

			if (!empty($up_mpn) && !empty($up['upc']) && !empty($up['brand']) && !empty($up['product_froogle'])){

				$up['product_froogle'] = str_replace(":", '-', $up['product_froogle']);
				$compatible_with .= $up['product_froogle'].':'.$up_mpn.':'.$up['upc'].':'.$up['brand'];
				break; # Internal SF tasks: Google Base feed COMPATIBLE_WITH issue
			}
		}
	}

	# Define "online only"
	$online_only = '';

	if ($product['shipping_freight'] == 0.00) {
		$online_only = 'n';
		$product["onlineOnly"] = "0";
	} elseif ($product['shipping_freight'] > 0.00) {
		$online_only = 'y';
		$product["onlineOnly"] = "1";
	}


/*
	# Define "shipping"
	if ($product['free_ship_zone'] == -1) {
		$shipping = '';
	} elseif ($product['free_ship_zone'] == 0) {
		$shipping = '::Ground:0.00';
	} else {
		$zone_countries = func_query_column('SELECT field FROM '.$sql_tbl['zone_element']. ' WHERE zoneid='.$product['free_ship_zone'].' AND field_type = "C"');
		$shipping = implode('::Ground:0.00, ', $zone_countries).'::Ground:0.00';
	}
*/
#
##
###
	$shipping_arr = func_define_approximate_shippings($product["productid"], $product);
	$shipping = $shipping_arr["shippings_str"];

	$product["shippings_google_arr"] = $shipping_arr["shippings_google_arr"];
###
##
#
	#
	# Define Detailed product image
	#
	$tmp_all = func_query("SELECT id, imageid, image_path FROM $sql_tbl[images_D] WHERE $sql_tbl[images_D].id = '$product[productid]' AND $sql_tbl[images_D].avail='Y' ORDER BY orderby");

	if (!empty($tmp_all) && is_array($tmp_all)){
		foreach($tmp_all as $k_tmp => $tmp){

			if (!empty($tmp['imageid'])) {

				$tmbn_d = "";
				$image_path = "";
				$image_type = "";

				$image_path = $tmp['image_path'];
				$image_type = "D";

				if (!empty($image_path))
					$tmbn_d = func_get_image_url($tmp['imageid'], $image_type, $image_path);

				if ($tmbn_d === false || empty($tmbn_d)) {
					$tmbn_d = $product['froogle_location'] . '/image.php?id=' . $tmp['imageid'] . '&type=' . $image_type;
				} elseif (strpos($tmbn_d, $https_location) !== false) {
					$tmbn_d = str_replace($https_location, $product['froogle_location'], $tmbn_d);
				}

				if (strpos($tmbn_d, "default_image") !== false) {
					$tmp_all[$k_tmp]["tmbn_no_img"] = "Y";
				}

				$tmp_all[$k_tmp]["tmbn_d"] = $tmbn_d;
			}
		}

		foreach($tmp_all as $k_tmp => $tmp){
			if ($tmp["tmbn_no_img"] != "Y"){
				$tmbn = $tmp["tmbn_d"];
				unset($tmp_all[$k_tmp]);
				break;
			}
		}
	}

	$additional_image_link = "";

	if (!empty($tmp_all) && is_array($tmp_all)){
		$arr_additional_image_link = array();
		$tmp_count_additional_image_link = 0;

		foreach($tmp_all as $k_tmp => $tmp){
			if ($tmp["tmbn_no_img"] != "Y"){
				$arr_additional_image_link[] = $tmp["tmbn_d"];
				$product["additional_image_link"][] = $tmp["tmbn_d"];
				$tmp_count_additional_image_link++;
			}

			if ($tmp_count_additional_image_link == "10"){
				break;
			}
		}

		if ($tmp_count_additional_image_link > 0){
			$additional_image_link = implode(",", $arr_additional_image_link);
		}

	}

	$tmbn_no_img = "";
	if ((strpos($tmbn, "default_image") !== false) || empty($tmbn)) {
		$tmbn_no_img = "Y";
	}


//if ($scrip_name == "main_google")

	if ($sf_info["config"]["Appearance"]["Enable_CDN"]=="Y" && !empty($sf_info["config"]["Appearance"]["CDN_domain"])){
                $tmbn = str_replace($sf_info["domain"], $sf_info["config"]["Appearance"]["CDN_domain"], $tmbn);
                $tmbn = str_replace("www.artistsupplysource.com", $sf_info["config"]["Appearance"]["CDN_domain"], $tmbn);

                $additional_image_link = str_replace($sf_info["domain"], $sf_info["config"]["Appearance"]["CDN_domain"], $additional_image_link);
                $additional_image_link = str_replace("www.artistsupplysource.com", $sf_info["config"]["Appearance"]["CDN_domain"], $additional_image_link);
	}


	$tmp_image_link = $tmbn;
	if (empty($tmp_image_link)){
		$tmp_image_link = $product['froogle_location'] . "/default_image.gif";
	}

	$product['image_link'] = $tmp_image_link;

	if (empty($product['weight'])){
		$product['weight'] = "0.1";
	}

	if ($product["d_enable_feed"] == "Y" && $product["r_avail"] <= 0){

// 	        $max1 = $product["cost_to_us"] + ($product["price"] - $product["cost_to_us"])/3;
//		$product['price'] = max($product["map_price"], $max1);

		$product['price'] = func_decreased_price($product["cost_to_us"], $product["price"], $product["map_price"]);
	}
	else {
		$product['price'] = number_format(round($product['price'], 2), 2, ".", "");
	}


        $multipack = "";
        if ($product["min_amount"] > 1 && $product["mult_order_quantity"] == "Y"){
                $multipack = $product["min_amount"];
		$product['multipack'] = $multipack;
		$product['price'] = price_format($product['price'] * $multipack);
        }

//func_print_r($product);
//die();
	$product_availability = func_product_availability(false,false,false,false,false,$product);

	$product['mpn'] = $mpn;
	$product['gpc'] = $gpc;
	$product['cats_path'] = $cats_path;
	$product['google_descr'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['descr'], 5000));
	$product['google_brand'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['brand'], 256));
	$product['google_product'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['product'], 80));

	$row = $product['google_product']."\t".
	$product['google_descr']."\t".
	$product['link'] . "\t".
	$product['adwords_redirect'] . "\t".
//	$product['adwords_grouping'] . "\t".
//	$product['adwords_labels'] . "\t".
	$tmp_image_link."\t".
	$additional_image_link."\t".
	$product['productid']."\t".
	$product['price']."\t".
//	func_froogle_convert($config['Froogle']['froogle_payment_accepted'], 65536)."\t".
//	func_froogle_convert($config['Froogle']['froogle_payment_notes'], 65536)."\t".
//	(($product['avail'] < 0) ? 0 : ($product['avail']))."\t".
	$product['weight'].($product['weight'] > 0 ? " lb":"")."\t".
	date("Y-m-d", time()+(empty($config['Froogle']['froogle_expiration_date']) ? 0.5 : $config['Froogle']['froogle_expiration_date'])*86400)."\t".
	$product['google_brand']."\t".
	"new\t".
	"$cats_path"."\t".
	"$mpn\t".
//	"$mpn\t".
	trim($product['upc']) . "\t".
//	trim($compatible_with) . "\t".
//	"$online_only\t".
	"$shipping\t" .
	"$product_availability\t".$multipack."\t".$gpc;

	$row_arr["row"] = $row;
	$row_arr["product"] = $product;

	return $row_arr;
}

function GetTheFindOneRow($productid){
        global $sql_tbl, $xcart_dir, $active_modules, $config, $https_location, $http_location;

        if (empty($productid)){
		$row = "Title\tDescription\tImage_Link\tPage_URL\tDirect_URL\tPrice\tSKU\tUPC-EAN\tMPN\tISBN\tUnique_ID\tFree Shipping\tOnline_Only\tStock_Quantity\tBrand\tCategories\tCondition\tHot or Not\tCompatible_With\tSimilar_To\tWeight\n";
                return $row;
        }

        $froogle_location = $config['Froogle']['froogle_used_https_links'] == 'Y' ? $https_location : $http_location;
        $froogle_scheme = $config['Froogle']['froogle_used_https_links'] == 'Y' ? 'https://' : 'http://';

        $where = "";
        $fields = "";
        $joins = "";

        if (!empty($active_modules['Multiple_Storefronts'])) {
                $fields .= ", $sql_tbl[products_sf].sfid";
                $joins .= " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
                $where .= " AND $sql_tbl[products_sf].productid = $productid";
        }

//        if ($config["General"]["disable_outofstock_products"] == "Y") {
                if (!empty($active_modules['Product_Options'])) {
                        $where .= " AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) > '0'";
                } else {
                        $where .= " AND $sql_tbl[products].avail > '0'";
                }
//        }

        $joins .= " INNER JOIN $sql_tbl[quick_prices] ON $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid = '0'";
        if (!empty($active_modules['Product_Options'])) {
                $joins .= " LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid";
                $fields .= ", IFNULL($sql_tbl[variants].productcode, $sql_tbl[products].productcode) as productcode, IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) as avail, IFNULL($sql_tbl[variants].weight, $sql_tbl[products].weight) as weight";
        }

        if (!empty($active_modules['Manufacturers'])) {
                $fields .= ", IF ($sql_tbl[manufacturers_lng].manufacturer != '', $sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer, $sql_tbl[manufacturers].d_enable_feed";
                $joins .= " LEFT JOIN $sql_tbl[manufacturers] ON $sql_tbl[products].manufacturerid = $sql_tbl[manufacturers].manufacturerid LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[products].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$froogle_lng'";
        }

        if (!empty($active_modules['Brands'])) {
                $fields .= ", IF ($sql_tbl[brands_lng].brand != '', $sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand";
                $joins .= " LEFT JOIN $sql_tbl[brands] ON $sql_tbl[products].brandid = $sql_tbl[brands].brandid LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[products].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$froogle_lng'";
        }

        $product = func_query_first("SELECT SQL_NO_CACHE $sql_tbl[products].*, $sql_tbl[categories].categoryid_path, $sql_tbl[pricing].price, $sql_tbl[images_T].image_path $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C')");

        if (empty($product))
                return;

        $sf_info = func_get_storefront_info($product['sfid']);

        $product_categories = func_query_hash("SELECT $sql_tbl[products].productid, $sql_tbl[categories].categoryid_path FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[products]) WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' AND $sql_tbl[products].productid='$productid'", 'productid', true, true);

        if (!empty($product["eta_date_mm_dd_yyyy"])){
                $eta_date_mm_dd_yyyy_time_arr = explode("/", $product["eta_date_mm_dd_yyyy"]);
                if (!empty($eta_date_mm_dd_yyyy_time_arr) && is_array($eta_date_mm_dd_yyyy_time_arr)){
                        $eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $eta_date_mm_dd_yyyy_time_arr[0], $eta_date_mm_dd_yyyy_time_arr[1], $eta_date_mm_dd_yyyy_time_arr[2]);
                        if ($eta_date_mm_dd_yyyy_time > time()){
//                              print"ETA date in future.";
                                return;
                        }
                }
        }

        if(isset($product['sfid']) && $product['sfid'] != 0) {
                $product['froogle_location'] = $froogle_scheme . func_get_http_location_sf($product['sfid']);
        } else {
                $product['froogle_location'] = $froogle_location;
        }

        $tmp_upc = trim($product['upc']);
        $tmp_upc = isset($tmp_upc) ? abs(intval($tmp_upc)) : 0;
        if (empty($tmp_upc) || $tmp_upc == "0"){
                $product['upc'] = "";
        }

        $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='P' AND resource_id='$product[productid]'");
        $clean_url_link .="/";

        $product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/'. $clean_url_link;

        if (!empty($sf_info['prefix'])){

                $utm_medium = $product['brand'];
                $utm_medium = preg_replace('/[^\w]/', '', $utm_medium);
                $utm_medium = preg_replace('/[_]/', '', $utm_medium);

                $utm_campaign = $product['productcode'];
                $utm_campaign = preg_replace('/[^\w]/', '', $utm_campaign);
                $utm_campaign = preg_replace('/[_]/', '', $utm_campaign);

                $product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/' . $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'froogle_Google-Shopping&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
                $product['adwords_redirect'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/' . $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'froogle_Product-Ads&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
                $product["adwords_grouping"] = $product['manufacturerid'];
                $product['page_url'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/'. $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'thefind&utm_medium=feed&utm_campaign='.$utm_campaign;
        }

        # Define product category path
        $cats = array();
        if (is_array($product_categories) && isset($product_categories[$product['productid']]) && is_array($product_categories[$product['productid']])) {
                foreach ($product_categories[$product['productid']] as $kpc => $pc) {
                        $catids = explode("/", $pc);
                        if ($catids[0] == EXCLUDE_CATEGORYID_BRANCH) {
                                continue;
                        }

                        if (!empty($catids)) {
                                $cats[$kpc] = func_query("SELECT categoryid, category FROM $sql_tbl[categories] WHERE categoryid IN ('".implode("','", $catids)."') AND avail = 'Y'$sf_cat_condition");
                                $catids = array_flip($catids);
                                if (!empty($cats[$kpc])) {
                                        if (count($cats[$kpc]) != count($catids))
                                                    continue;

                                        foreach ($cats[$kpc] as $k => $v) {
                                                    if (isset($catids[$v['categoryid']])) {
                                                        $catids[$v['categoryid']] = $v['category'];
                                                    }
                                        }

                                        $cats[$kpc] = str_replace("\t", ' ', implode(' > ', $catids));

                                }
                        }
                }
        }

        if (!empty($cats[0])){
                $cats_path = $cats[0];
        }

        $cats_path_for_thefind = !empty($cats) ? implode(',', $cats) : '';

        $cats_path = func_froogle_convert($cats_path, 1000);
        $cats_path = func_cidev_check_froogle_field($cats_path);
        $cats_path = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $cats_path);

        $cats_path_for_thefind = func_froogle_convert($cats_path_for_thefind, 1000);
        $cats_path_for_thefind = func_cidev_check_froogle_field($cats_path_for_thefind);
        $cats_path_for_thefind = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $cats_path_for_thefind);

        # Define full description
        if (!empty($product['fulldescr']))
                $product['descr'] = $product['fulldescr'];

        $product['descr'] = func_froogle_convert($product['descr'], 10000);
        $product['descr'] = func_cidev_check_froogle_field($product['descr']);
        $product['descr'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $product['descr']);

        $product['product'] = func_froogle_convert($product['product'], 70);
        $product['product'] = func_cidev_check_froogle_field($product['product']);
        $product['product'] = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $product['product']);

        # Define product image
        $tmp = func_query_first("SELECT id, image_path FROM $sql_tbl[images_P] WHERE $sql_tbl[images_P].id = '$product[productid]'");
        $tmbn = "";
        $image_path = "";
        $image_type = "";

        if (!empty($tmp['id'])) {
                $image_path = $tmp['image_path'];
                $image_type = "P";
        } elseif (!is_null($product['image_path'])) {
                $image_path = $product['image_path'];
                $image_type = "T";
        }

        if (!empty($image_type)) {
                if (!empty($image_path))
                        $tmbn = func_get_image_url($product['productid'], $image_type, $image_path);
                if ($tmbn === false || empty($tmbn)) {
                        $tmbn = $product['froogle_location'] . '/image.php?id=' . $product['productid'] . '&type=' . $image_type;
                } elseif (strpos($tmbn, $https_location) !== false) {
                        $tmbn = str_replace($https_location, $product['froogle_location'], $tmbn);
                }
        }

        $ci = array(
                "city" => $config['General']['default_city'],
                "state" => $config['General']['default_state'],
                "country" => $config['General']['default_country'],
                "zipcode" => $config['General']['default_zipcode']
        );

        if (!empty($active_modules['Product_Options']))
                $product['price'] += func_get_default_options_markup($product['productid'], $product['price']);

        $tmp = func_tax_price($product['price'], $product['productid'], false, NULL, $ci);
        $product['price'] = $tmp['taxed_price'];

        if ($product["new_map_price"] > $product["price"]){
                $product["price"] = $product["new_map_price"];
                $product['taxed_price'] = $product['price'];
        }

        /*if ($product["min_amount"] > 1){
                $new_price =  func_query_first_cell("SELECT MIN(price) FROM $sql_tbl[pricing] WHERE $sql_tbl[pricing].quantity <= '$product[min_amount]' AND $sql_tbl[pricing].variantid = 0 AND $sql_tbl[pricing].productid = '$product[productid]'");
                $new_price *= $product["min_amount"];
                $new_price = func_tax_price($new_price, $product['productid'], false, NULL, $ci);

                $product["price"] = $new_price['taxed_price'];
                $product['taxed_price'] = $new_price['taxed_price'];
        }*/

        if (empty($cidev_number_clicks) || $cidev_number_clicks == 0){
                $cidev_number_clicks = $config["Froogle"]["froogle_number_clicks_last_used"];
        }

        if (empty($cidev_max_cpc_group) || $cidev_max_cpc_group == 0){
                $cidev_max_cpc_group = $config["Froogle"]["froogle_max_cpc_group_last_used"];
        }

        $CPC_group = price_format((max($product["new_map_price"], $product["price"]) - $product["cost_to_us"])/$cidev_number_clicks);

        $product['adwords_labels'] = $CPC_group."-cpc-group";

        if ($CPC_group >= $cidev_max_cpc_group){
                $product['adwords_labels'] = $cidev_max_cpc_group."-cpc-group";
        }

        if ($CPC_group <= 0){
                $product['adwords_labels'] = "0.01-cpc-group";
        }

        if ($product["list_price"] > 20 && (1 - ($product["price"]/$product["list_price"]))>0.50){
                $product['adwords_labels'] .= ", offlist";
        }

        # Define "mpn"
        $pos = strpos($product['productcode'], '-');
        $mpn = '';

        if ($pos && is_numeric($pos) && $pos + 1 != strlen($product['productcode'])) {
                $mpn = substr($product['productcode'], $pos + 1);
        }
        else {
                $mpn = $product['productcode'];
        }

        if (strlen($mpn) < 3){
                $mpn .= "-GBFIX";
        }

        # Define "compatible with"
        $upselling_products = func_query("SELECT p.product_froogle, p.productcode, p.upc, b.brand FROM $sql_tbl[product_links] as pl, $sql_tbl[products] as p LEFT JOIN $sql_tbl[brands] b ON b.brandid=p.brandid WHERE pl.productid1=$product[productid] AND p.productid=pl.productid2");

        $compatible_with = '';

        if (!empty($upselling_products) && is_array($upselling_products)) {

                foreach ($upselling_products as $up) {
                        $tmp_upc = trim($up['upc']);
                        $tmp_upc = isset($tmp_upc) ? abs(intval($tmp_upc)) : 0;
                        if (empty($tmp_upc) || $tmp_upc == "0"){
                                $up['upc'] = "";
                        }

                        $up_pos = strpos($up['productcode'], '-');
                        $up_mpn = '';
                        if ($up_pos && is_numeric($up_pos) && $up_pos + 1 != strlen($up['productcode'])) {
                                $up_mpn = substr($up['productcode'], $up_pos + 1);
                        }
                        if ($compatible_with != '') {
                                $compatible_with .= ', ';
                        }

                        if (!empty($up_mpn) && !empty($up['upc']) && !empty($up['brand']) && !empty($up['product_froogle'])){

                                $up['product_froogle'] = str_replace(":", '-', $up['product_froogle']);
                                $compatible_with .= $up['product_froogle'].':'.$up_mpn.':'.$up['upc'].':'.$up['brand'];
                                break; # Internal SF tasks: Google Base feed COMPATIBLE_WITH issue
                        }
                }
        }

        # Define "online only"
        $online_only = '';

        if ($product['shipping_freight'] == 0.00) {
                $online_only = 'n';
        } elseif ($product['shipping_freight'] > 0.00) {
                $online_only = 'y';
        }

/*
        # Define "shipping"
        if ($product['free_ship_zone'] == -1) {
                $shipping = '';
        } elseif ($product['free_ship_zone'] == 0) {
                $shipping = '::Ground:0.00';
        } else {
                $zone_countries = func_query_column('SELECT field FROM '.$sql_tbl['zone_element']. ' WHERE zoneid='.$product['free_ship_zone'].' AND field_type = "C"');
                $shipping = implode('::Ground:0.00, ', $zone_countries).'::Ground:0.00';
        }
*/
#
##
###
        $shipping_arr = func_define_approximate_shippings($product["productid"], $product);
        $shipping = $shipping_arr["shippings_str"];
###
##
#
        #
        # Define Detailed product image
        #
        $tmp_all = func_query("SELECT id, imageid, image_path FROM $sql_tbl[images_D] WHERE $sql_tbl[images_D].id = '$product[productid]' AND $sql_tbl[images_D].avail='Y' ORDER BY orderby");

        if (!empty($tmp_all) && is_array($tmp_all)){
                foreach($tmp_all as $k_tmp => $tmp){

                        if (!empty($tmp['imageid'])) {

                                $tmbn_d = "";
                                $image_path = "";
                                $image_type = "";

                                $image_path = $tmp['image_path'];
                                $image_type = "D";

                                if (!empty($image_path))
                                        $tmbn_d = func_get_image_url($tmp['imageid'], $image_type, $image_path);

                                if ($tmbn_d === false || empty($tmbn_d)) {
                                        $tmbn_d = $product['froogle_location'] . '/image.php?id=' . $tmp['imageid'] . '&type=' . $image_type;
                                } elseif (strpos($tmbn_d, $https_location) !== false) {
                                        $tmbn_d = str_replace($https_location, $product['froogle_location'], $tmbn_d);
                                }

                                if (strpos($tmbn_d, "default_image") !== false) {
                                        $tmp_all[$k_tmp]["tmbn_no_img"] = "Y";
                                }

                                $tmp_all[$k_tmp]["tmbn_d"] = $tmbn_d;
                        }
                }

                foreach($tmp_all as $k_tmp => $tmp){
                        if ($tmp["tmbn_no_img"] != "Y"){
                                $tmbn = $tmp["tmbn_d"];
                                unset($tmp_all[$k_tmp]);
                                break;
                        }
                }
        }

        $additional_image_link = "";

        if (!empty($tmp_all) && is_array($tmp_all)){
                $arr_additional_image_link = array();
                $tmp_count_additional_image_link = 0;

                foreach($tmp_all as $k_tmp => $tmp){
                        if ($tmp["tmbn_no_img"] != "Y"){
                                $arr_additional_image_link[] = $tmp["tmbn_d"];
                                $tmp_count_additional_image_link++;
                        }

                        if ($tmp_count_additional_image_link == "10"){
                                break;
                        }
                }

                if ($tmp_count_additional_image_link > 0){
                        $additional_image_link = implode(",", $arr_additional_image_link);
                }

        }

        $tmbn_no_img = "";
        if ((strpos($tmbn, "default_image") !== false) || empty($tmbn)) {
                $tmbn_no_img = "Y";
        }

	if ($tmbn_no_img == "Y") {
		$tmbn = "no image";
	}

	$upc_ean = "";
	$isbn = "";

	$count_num = strlen($product['upc']);

	if ($count_num == "10"){
		$isbn = $product['upc'];
	}
	else if ($count_num == "12"){
		$upc_ean = $product['upc'];
	}
	else if ($count_num == "13"){
		$tmp_substr = substr($product['upc'], 0, 3);

		if ($tmp_substr == "978"){
			$isbn = $product['upc'];
		}
		else {
			$upc_ean = $product['upc'];
		}
	}

	$free_ship_zone = "";
	if ($product['free_ship_zone'] == "14" || $product['free_ship_zone'] == "15"){
		// USA: Contiguous OR USA and Canada: Contiguous;
		$free_ship_zone = "Free Shipping";
	}

	$hot_or_not = "1";
	if ($product['avail'] == "1000000"){
		$hot_or_not = "0";
	}

	$compatible_with = "";
	$similar_to = "";

	$row = iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['product']))."\t".
	iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['descr'], 10000))."\t".
	$tmbn."\t".
	$product['page_url']."\t".
	$product['froogle_location'] . '/product.php?productid='.$product["productid"]."\t".
	number_format(round($product['price'], 2), 2, ".", "")."\t".
	iconv("UTF-8", "ISO-8859-1//TRANSLIT",$product['productcode'])."\t".
	$upc_ean."\t".
	$mpn."\t".
	$isbn."\t".
	$product['productid']."\t".
	$free_ship_zone."\t".
	"Yes"."\t".
	(($product['avail'] < 0) ? 0 : ($product['avail']))."\t".
	iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['brand']))."\t".
	$cats_path_for_thefind."\t".
	"new"."\t".
	$hot_or_not."\t".
	$compatible_with."\t".
	$similar_to."\t".
	$product['weight'];
        $row .="\n";

        return $row;
}


function SubmitProductToGBFeed($productid, $MerchantID, $client_id, $key_file_location, $update_type, $service, $forsale){
	global $started_at, $sql_tbl;

# utype = 1 | 2 = productid; utype = 3 = manufacturerid

	if ($forsale == "N"){
		try {
		$results3 = $service->products->delete($MerchantID, "online:en:US:".$productid);
		}
		catch (Google_ServiceException $e) {
		    print "Error code :" . $e->getCode() . "\n";
		    // Error message is formatted as "Error calling <REQUEST METHOD> <REQUEST URL>: (<CODE>) <MESSAGE OR REASON>".
		    print "Error message: " . $e->getMessage() . "\n";
		} 
		catch (Google_Exception $e) {
		    // Other error.
		    print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";
		    }
		

		db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$productid' AND time_stamp <= '$started_at'");
		return false;
	}


#########################
/*
$productid = "326716";
$product_info["product"]["productid"] = $productid;
$product_info["product"]["product"] = "TTTT";
$product_info["product"]["productcode"] = "CC-TTTT";
$update_type = "1";
*/
#########################

//func_print_r($productid, $update_type);

	if ($update_type == "2"){

		try {
		print ("update type 2 try productid = ".$productid."\n");
		$results = $service->products->get($MerchantID, "online:en:US:".$productid);


//func_print_r($results);
//die();
		
		print( "  proceed with quantity for ".$productid."\n");
		$postBody = $results->toSimpleObject();
		###$postBody->price["value"] = $product_info["product"]["price"];
		$postBody = (array)$postBody;


                $fields = ", IFNULL($sql_tbl[variants].avail, $sql_tbl[products].r_avail) as r_avail";
                $joins = " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
	        $joins .= " INNER JOIN $sql_tbl[quick_prices] ON $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid = '0'";
                $joins .= " LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid";
                $where = " AND $sql_tbl[products_sf].productid = $productid AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) >= '0'";

	        $product = func_query_first("SELECT SQL_NO_CACHE $sql_tbl[products].product_type, $sql_tbl[products].cost_to_us, $sql_tbl[products].map_price, $sql_tbl[products].manufacturerid, $sql_tbl[products].eta_date_mm_dd_yyyy, $sql_tbl[pricing].price $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C')");

###
		$product["d_enable_feed"] = func_query_first_cell("SELECT d_enable_feed FROM $sql_tbl[manufacturers] WHERE manufacturerid='".$product['manufacturerid']."'");

	        if ($product["d_enable_feed"] == "Y" && $product["r_avail"] <= 0){
        	        $product['price'] = func_decreased_price($product["cost_to_us"], $product["price"], $product["map_price"]);
	        }
###
		$postBody["price"]["value"] = $product["price"];

		$quantity_found = false;
		$key_quantity = 0;
		if (!empty($postBody["customAttributes"]) && is_array($postBody["customAttributes"])){
			foreach ($postBody["customAttributes"] as $k => $v){
				if ($v["name"] == "quantity"){
					$postBody["customAttributes"][$k]["value"] = $product["r_avail"];
					$quantity_found = true;
					break;
				}
			}

			if (!$quantity_found){
/*			       print ("quantity not fnd\n");*/
				$key_quantity = 6; 
				/*count($postBody["customAttributes"]);*/
			}
		}

		if (!$quantity_found){
			$postBody["customAttributes"][$key_quantity]["name"] = "quantity";
			$postBody["customAttributes"][$key_quantity]["type"] = "int";
			$postBody["customAttributes"][$key_quantity]["value"] = $product["r_avail"];
		}

		$product_availability = func_product_availability(false,false,false,false,false,$product);
		$postBody["availability"] = $product_availability;

		$expirationDate = time()+60*60*24*30;
		$expirationDate = date("Y-m-d", $expirationDate);
		$postBody["expirationDate"] = $expirationDate;

		### call instead insert ###  $results2 = $service- >products->insert($MerchantID, $results_new);###
		$optParams = array();
		$params = array('merchantId' => $MerchantID, 'postBody' => $postBody);
		$params = array_merge($params, $optParams);

		$results2 = $service->products->call('insert', array($params), "Google_Service_ShoppingContent_Product");

//func_print_r($results2);
//die();

		}
		catch (Google_ServiceException $e) {
		    print "Error code :" . $e->getCode() . "\n";
		    // Error message is formatted as "Error calling <REQUEST METHOD> <REQUEST URL>: (<CODE>) <MESSAGE OR REASON>".
		    print "Error message: " . $e->getMessage() . "\n";
		} 
		catch (Google_Exception $e) {
		    // Other error.
		    print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";
		    if ($e->getCode() == '404') {
			$update_type = "1";
/*			print ("utype changed to ".$update_type."\n");*/
		    }
		}

	}
	if ($update_type == "1"){

		print ("update type 1 try productid = ".$productid."\n");

	        $product_info = GetGoogleBaseOneRow($productid);
/*	        func_print_r($product_info);*/

        	if (empty($product_info["product"]) || !is_array($product_info["product"])){
/*			return false;*/
			$update_type = 1;
		}
		else
		{

		$postBody["price"]["value"] = $product_info["product"]["price"];
		$postBody["price"]["currency"] = "USD";

#
##
		if (!empty($product_info["product"]["multipack"])){
			$postBody["multipack"] = $product_info["product"]["multipack"];
		}
##
#


		$postBody["shipping"] = $product_info["product"]["shippings_google_arr"];

		$postBody["shippingWeight"]["value"] = $product_info["product"]["weight"];
		$postBody["shippingWeight"]["unit"] = "lb";

		$postBody["destinations"][0]["destinationName"] = "ShoppingApi";
		$postBody["destinations"][0]["intention"] = "required";
                $postBody["destinations"][1]["destinationName"] = "AffiliateNetwork";
                $postBody["destinations"][1]["intention"] = "required";
                $postBody["destinations"][2]["destinationName"] = "Shopping";
                $postBody["destinations"][2]["intention"] = "required";

                $postBody["customAttributes"][0]["name"] = "payment accepted";
                $postBody["customAttributes"][0]["type"] = "text";
                $postBody["customAttributes"][0]["value"] = "check";
                $postBody["customAttributes"][1]["name"] = "payment accepted";
                $postBody["customAttributes"][1]["type"] = "text";
                $postBody["customAttributes"][1]["value"] = "visa";
                $postBody["customAttributes"][2]["name"] = "payment accepted";
                $postBody["customAttributes"][2]["type"] = "text";
                $postBody["customAttributes"][2]["value"] = "mastercard";
                $postBody["customAttributes"][3]["name"] = "payment accepted";
                $postBody["customAttributes"][3]["type"] = "text";
                $postBody["customAttributes"][3]["value"] = "discover";
                $postBody["customAttributes"][4]["name"] = "payment accepted";
                $postBody["customAttributes"][4]["type"] = "text";
                $postBody["customAttributes"][4]["value"] = "american express";
                $postBody["customAttributes"][5]["name"] = "payment accepted";
                $postBody["customAttributes"][5]["type"] = "text";
                $postBody["customAttributes"][5]["value"] = "All purchase orders are subject to verification.";
                $postBody["customAttributes"][6]["name"] = "quantity";
                $postBody["customAttributes"][6]["type"] = "int";
                $postBody["customAttributes"][6]["value"] = $product_info["product"]["r_avail"];
                $postBody["customAttributes"][7]["name"] = "model number";
                $postBody["customAttributes"][7]["type"] = "text";
                $postBody["customAttributes"][7]["value"] = $product_info["product"]["mpn"];

		if (!empty($product_info["product"]["additional_image_link"]) && is_array($product_info["product"]["additional_image_link"]))
	                $postBody["additionalImageLinks"] = $product_info["product"]["additional_image_link"];

                $postBody["adwordsGrouping"] = $product_info["product"]["adwords_grouping"];
                $postBody["adwordsLabels"][0] = $product_info["product"]["adwords_labels"];
                $postBody["adwordsRedirect"] = $product_info["product"]["adwords_redirect"];

                $product_availability = func_product_availability(false,false,false,false,false,$product_info["product"]);
                $postBody["availability"] = $product_availability;

                $postBody["brand"] = $product_info["product"]["google_brand"];
                $postBody["channel"] = "online";
                $postBody["condition"] = "new";
                $postBody["contentLanguage"] = "en";
                $postBody["description"] = $product_info["product"]["google_descr"];
                $postBody["id"] = "online:en:US:".$productid;
                $postBody["imageLink"] = $product_info["product"]["image_link"];
                $postBody["kind"] = "content#product";
                $postBody["link"] = $product_info["product"]["link"];
                $postBody["mpn"] = $product_info["product"]["mpn"];
                $postBody["offerId"] = $productid;
                $postBody["onlineOnly"] = $product_info["product"]["onlineOnly"];
                $postBody["productType"] = $product_info["product"]["cats_path"];
                $postBody["targetCountry"] = "US";
                $postBody["title"] = $product_info["product"]["google_product"];

                $expirationDate = time()+60*60*24*30;
                $expirationDate = date("Y-m-d", $expirationDate);
                $postBody["expirationDate"] = $expirationDate;

                $optParams = array();
                $params = array('merchantId' => $MerchantID, 'postBody' => $postBody);
                $params = array_merge($params, $optParams);
                $results2 = $service->products->call('insert', array($params), "Google_Service_ShoppingContent_Product");
                }
	}


	if ($update_type == "1" || $update_type == "2"){
		db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$productid' AND (type='1' OR type='2')");
	}

//func_print_r($postBody, $product_info, $results2);
//func_print_r($results2);
//die("!!!");

}


function Submit_expirationDate_ToGBFeed($productid, $MerchantID, $client_id, $key_file_location, $service){
	global $sql_tbl;

	try {
		$results = $service->products->get($MerchantID, "online:en:US:".$productid);

		$postBody = $results->toSimpleObject();
		$postBody = (array)$postBody;

		$expirationDate = time()+60*60*24*30;
		$expirationDate = date("Y-m-d", $expirationDate);
		$postBody["expirationDate"] = $expirationDate;

		$optParams = array();
		$params = array('merchantId' => $MerchantID, 'postBody' => $postBody);
		$params = array_merge($params, $optParams);
		$results2 = $service->products->call('insert', array($params), "Google_Service_ShoppingContent_Product");
	}
	catch (Google_ServiceException $e) {
		print "Error code :" . $e->getCode() . "\n";
		// Error message is formatted as "Error calling <REQUEST METHOD> <REQUEST URL>: (<CODE>) <MESSAGE OR REASON>".
		print "Error message: " . $e->getMessage() . "\n";
	}
	catch (Google_Exception $e) {
		// Other error.
		print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";
		if ($e->getCode() == '404') {
		}
	}
}

function AddProductToAmazonBatch($productid, $update_type, $amazon_inventory_batch_count, $ainventory){

	if ($update_type == "2" || $update_type == "1,2"){
                $count_ainventory = count($ainventory);
                $ainventory[$count_ainventory]["productid"] = $productid;
                $amazon_inventory_batch_count++;
	}

        $AddProductToAmazonBatch_arr["amazon_inventory_batch_count"] = $amazon_inventory_batch_count;
        $AddProductToAmazonBatch_arr["ainventory"] = $ainventory;

        return $AddProductToAmazonBatch_arr;
}

function AddProductToGoogleBaseBatch($productid, $MerchantID, $update_type, $service, $forsale, $google_products_batch_count, $gproducts, $google_inventory_batch_count, $ginventory){

	if ($update_type == "1" || $update_type == "1,2"){

	        if (1 == 2 /*$forsale == "N"*/){
        	        try {
				print("GB: tried to delete item with productid = $productid \r\n\r\n");

	                        $log_text = "GB: tried to delete item with productid = $productid";
        	                func_backprocess_log("incremental feeds", $log_text);

	                	$results3 = $service->products->delete($MerchantID, "online:en:US:".$productid);
	                }
        	        catch (Google_ServiceException $e) {
                	    print "Error code :" . $e->getCode() . "\n";
	                    // Error message is formatted as "Error calling <REQUEST METHOD> <REQUEST URL>: (<CODE>) <MESSAGE OR REASON>".
        	            print "Error message: " . $e->getMessage() . "\n";

                            $log_text = "Error code :" . $e->getCode() . "\n"."Error message: " . $e->getMessage();
                            func_backprocess_log("incremental feeds", $log_text);

                	}
	                catch (Google_Exception $e) {
        	            // Other error.
                	    print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";

                            $log_text = "An error occurred: (" . $e->getCode() . ") " . $e->getMessage();
                            func_backprocess_log("incremental feeds", $log_text);
	                }

			return false;
	        }
		else {
			$Batchid = $google_products_batch_count;
			$count_gproducts = count($gproducts);
			$gproducts[$count_gproducts]["productid"] = $productid;
			$gproducts[$count_gproducts]["Batchid"] = $Batchid;
			$google_products_batch_count++;
		}
	}
	elseif ($update_type == "2" && $forsale == "Y"){
		$Batchid = $google_inventory_batch_count;
		$count_ginventory = count($ginventory);
		$ginventory[$count_ginventory]["productid"] = $productid;
		$ginventory[$count_ginventory]["Batchid"] = $Batchid;
		$google_inventory_batch_count++;
	}

	$AddProductToGoogleBaseBatch_arr["google_products_batch_count"] = $google_products_batch_count;
	$AddProductToGoogleBaseBatch_arr["gproducts"] = $gproducts;
	$AddProductToGoogleBaseBatch_arr["google_inventory_batch_count"] = $google_inventory_batch_count;
	$AddProductToGoogleBaseBatch_arr["ginventory"] = $ginventory;

	return $AddProductToGoogleBaseBatch_arr;
}

function SubmitGoogleInventoryBatch($ginventory, $service, $MerchantID){
        global $started_at, $sql_tbl;

	foreach ($ginventory as $k => $v){

                $fields = ", IFNULL($sql_tbl[variants].avail, $sql_tbl[products].r_avail) as r_avail, $sql_tbl[products].cost_to_us, $sql_tbl[products].map_price, $sql_tbl[products].manufacturerid, $sql_tbl[products].eta_date_mm_dd_yyyy";
                $joins = " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
                $joins .= " INNER JOIN $sql_tbl[quick_prices] ON $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid = '0'";
                $joins .= " LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid";
                $where = " AND $sql_tbl[products_sf].productid = '$v[productid]' AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) >= '0'";

                $product = func_query_first("SELECT SQL_NO_CACHE $sql_tbl[products].product_type, $sql_tbl[pricing].price $fields, $sql_tbl[products].min_amount, $sql_tbl[products].mult_order_quantity FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C')");


###
                $product["d_enable_feed"] = func_query_first_cell("SELECT d_enable_feed FROM $sql_tbl[manufacturers] WHERE manufacturerid='".$product['manufacturerid']."'");

                if ($product["d_enable_feed"] == "Y" && $product["r_avail"] <= 0){
                        $product['price'] = func_decreased_price($product["cost_to_us"], $product["price"], $product["map_price"]);
                }

		$product_availability = func_product_availability(false,false,false,false,false,$product);
		
//		if ($product["min_amount"]>1) {
//		    $product_availability = 'out of stock';
//		}
###



//		$postBody["entries"][$k]["batchId"] = $v["Batchid"];
		$postBody["entries"][$k]["batchId"] = $v["productid"];
		$postBody["entries"][$k]["merchantId"] = $MerchantID;
		$postBody["entries"][$k]["storeCode"] = "online";
		$postBody["entries"][$k]["productId"] = "online:en:US:".$v["productid"];
		$postBody["entries"][$k]["inventory"]["kind"] = "content#inventory";

//if ($v["productid"] == "140060")
//$postBody["entries"][$k]["inventory"]["price"]["value"] = "";
//else

#
##
		if ($product["min_amount"] > 1 && $product["mult_order_quantity"] == "Y"){
                        $postBody["entries"][$k]["inventory"]["price"]["value"] = price_format($product["min_amount"]*$product["price"]);
                }
		else {
			$postBody["entries"][$k]["inventory"]["price"]["value"] = $product["price"];
		}
##
#
		$postBody["entries"][$k]["inventory"]["price"]["currency"] = "USD";

		$postBody["entries"][$k]["inventory"]["availability"]= $product_availability;
		$postBody["entries"][$k]["inventory"]["quantity"]= $product["r_avail"];
	}


	try {

                $k++;
                print("\nGB: tried to submit $k items as inventory feed \n");

		$log_text = "GB: tried to submit $k items as inventory feed";
		func_backprocess_log("incremental feeds", $log_text);


                $optParams = array();
		$params = array('postBody' => $postBody);
		$params = array_merge($params, $optParams);
                $results = $service->inventory->call('custombatch', array($params), "Google_Service_ShoppingContent_InventoryCustomBatchResponse");


###
                $results_arr = (array)$results;
                $log_text = "";
                foreach ($results_arr as $k => $v){
                 if (!empty($v) && is_array($v)){
                  foreach ($v as $kk => $vv){
                   if ($kk == "entries" && !empty($vv) && is_array($vv)){
                    foreach ($vv as $kkk => $vvv){
                     if (!empty($vvv["errors"])){
                      $log_text .= "batchId: ".$vvv["batchId"]." code: " . $vvv["errors"]["code"]. " message: ".$vvv["errors"]["message"] . "\n";
                     }
                    }
                   }
                  }
                 }
                }
                if (!empty($log_text)){
                        func_backprocess_log("incremental feeds", $log_text);
                }

//func_print_r($log_text);
###


//$ginventory_new = json_encode($postBody);
//func_print_r($ginventory_new);
//		$results = $service->inventory->custombatch($ginventory_new);
	}
	catch (Google_ServiceException $e) {
		print "Error code :" . $e->getCode() . "\n";
		// Error message is formatted as "Error calling <REQUEST METHOD> <REQUEST URL>: (<CODE>) <MESSAGE OR REASON>".
		print "Error message: " . $e->getMessage() . "\n";

                $log_text = "Error code :" . $e->getCode() . "\n"."Error message: " . $e->getMessage();
                func_backprocess_log("incremental feeds", $log_text);

	}
	catch (Google_Exception $e) {
		// Other error.
		print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";

                $log_text = "An error occurred: (" . $e->getCode() . ") " . $e->getMessage();
                func_backprocess_log("incremental feeds", $log_text);
	}

//func_print_r($results);
}

function SubmitGoogleProductsBatch($gproducts, $service, $MerchantID){
	global $sql_tbl;

//	func_print_r($gproducts);

	$count_skipped = 0;
	$k_counter = 0;


	foreach ($gproducts as $k => $v){

		$product_info = GetGoogleBaseOneRow($v["productid"], "main_google");
		
		$pforsale = func_query_first_cell("SELECT SQL_NO_CACHE $sql_tbl[products].forsale FROM $sql_tbl[products] WHERE $sql_tbl[products].productid = '$v[productid]'");

                if ( $pforsale == 'Y' && empty($product_info["product"]["shippings_google_arr"])){
			print("\nProduct skipped - $v[productid] \n");

	                $log_text = "Product skipped shipping null for sale item- ".$v["productid"];
        	        func_backprocess_log("incremental feeds", $log_text);

			$count_skipped++;
			continue;
		}

		

/*
                if (empty($product_info["product"]["shippings_google_arr"])){
                        func_print_r($postBody);
                        //die("test1");
                }

                if (!empty($product_info["product"]["shippings_google_arr"]) && is_array($product_info["product"]["shippings_google_arr"])){
                        foreach ($product_info["product"]["shippings_google_arr"] as $kk => $vv){
                                $ttt= trim($vv["price"]["value"]);
                                if ($ttt == ""){
                                        func_print_r($postBody);
                                        //die("test2");
                                }
                        }
                }
*/

//                $postBody["entries"][$k_counter]["batchId"] = $v["Batchid"];
		
//		if ($pforsale == 'N' || (empty($product_info["product"]) || !is_array($product_info["product"])) || $product_info["product"]["min_amount"] > 1) 
		if ($pforsale == 'N' || (empty($product_info["product"]) || !is_array($product_info["product"])) ) {
                    $postBody["entries"][$k_counter]["batchId"] = $v["productid"];
	            $postBody["entries"][$k_counter]["merchantId"] = $MerchantID;
    	            $postBody["entries"][$k_counter]["method"] = "delete";
        	    $postBody["entries"][$k_counter]["productId"] = "online:en:US:".$v["productid"];
		    $k_counter++;
		    
		
		} else
		{
                $postBody["entries"][$k_counter]["batchId"] = $v["productid"];
                $postBody["entries"][$k_counter]["merchantId"] = $MerchantID;
                $postBody["entries"][$k_counter]["method"] = "insert";
                $postBody["entries"][$k_counter]["productId"] = "online:en:US:".$v["productid"];
                $postBody["entries"][$k_counter]["product"]["kind"] = "content#product";
                $postBody["entries"][$k_counter]["product"]["id"] = "online:en:US:".$v["productid"];
                $postBody["entries"][$k_counter]["product"]["offerId"] = $v["productid"];
                $postBody["entries"][$k_counter]["product"]["title"] = $product_info["product"]["google_product"];
                $postBody["entries"][$k_counter]["product"]["description"] = $product_info["product"]["google_descr"];
                $postBody["entries"][$k_counter]["product"]["link"] = $product_info["product"]["link"];
                $postBody["entries"][$k_counter]["product"]["imageLink"] = $product_info["product"]["image_link"];
                $postBody["entries"][$k_counter]["product"]["contentLanguage"] = "en";
                $postBody["entries"][$k_counter]["product"]["targetCountry"] = "US";
                $postBody["entries"][$k_counter]["product"]["channel"] = "online";

/*
                $expirationDate = time()+60*60*24*30;
                $expirationDate = date("Y-m-d", $expirationDate);
                $postBody["entries"][$k_counter]["product"]["expirationDate"] = $expirationDate;
*/

###
		$product_availability = func_product_availability(false,false,false,false,false,$product_info["product"]);
###
                $postBody["entries"][$k_counter]["product"]["availability"] = $product_availability;
                $postBody["entries"][$k_counter]["product"]["brand"] = $product_info["product"]["google_brand"];
                $postBody["entries"][$k_counter]["product"]["condition"] = "new";
                $postBody["entries"][$k_counter]["product"]["mpn"] = $product_info["product"]["mpn"];

//if ($product_info["product"]["productid"] == "140060")
//$postBody["entries"][$k_counter]["product"]["price"]["value"] = "";
//else
                $postBody["entries"][$k_counter]["product"]["price"]["value"] = $product_info["product"]["price"];
                $postBody["entries"][$k_counter]["product"]["price"]["currency"] = "USD";
                $postBody["entries"][$k_counter]["product"]["productType"] = $product_info["product"]["cats_path"];
                if (trim($product_info["product"]["gpc"]) != '') $postBody["entries"][$k_counter]["product"]["googleProductCategory"] = $product_info["product"]["gpc"];

#
##
                if (!empty($product_info["product"]["multipack"])){
                        $postBody["entries"][$k_counter]["product"]["multipack"] = $product_info["product"]["multipack"];
                }
##
#

                $postBody["entries"][$k_counter]["product"]["shipping"] = $product_info["product"]["shippings_google_arr"];
                $postBody["entries"][$k_counter]["product"]["shippingWeight"]["value"] = $product_info["product"]["weight"];
                $postBody["entries"][$k_counter]["product"]["shippingWeight"]["unit"] = "lb";

#
##
		if ($product_info["product"]["dim_z"] > 0 && $product_info["product"]["dim_x"] > 0 && $product_info["product"]["dim_y"] > 0){
	                $postBody["entries"][$k_counter]["product"]["shippingHeight"]["unit"] = "in";
        	        $postBody["entries"][$k_counter]["product"]["shippingHeight"]["value"] = $product_info["product"]["dim_z"];

	                $postBody["entries"][$k_counter]["product"]["shippingLength"]["unit"] = "in";
	                $postBody["entries"][$k_counter]["product"]["shippingLength"]["value"] = max($product_info["product"]["dim_x"], $product_info["product"]["dim_y"]);

	                $postBody["entries"][$k_counter]["product"]["shippingWidth"]["unit"] = "in";
        	        $postBody["entries"][$k_counter]["product"]["shippingWidth"]["value"] = min($product_info["product"]["dim_x"], $product_info["product"]["dim_y"]);
		}
##
#

                $postBody["entries"][$k_counter]["product"]["adwordsGrouping"] = $product_info["product"]["adwords_grouping"];
                $postBody["entries"][$k_counter]["product"]["adwordsLabels"][0] = $product_info["product"]["adwords_labels"];
                $postBody["entries"][$k_counter]["product"]["adwordsRedirect"] = $product_info["product"]["adwords_redirect"];

                $postBody["entries"][$k_counter]["product"]["destinations"][0]["destinationName"] = "ShoppingApi";
                $postBody["entries"][$k_counter]["product"]["destinations"][0]["intention"] = "required";
                $postBody["entries"][$k_counter]["product"]["destinations"][1]["destinationName"] = "AffiliateNetwork";
                $postBody["entries"][$k_counter]["product"]["destinations"][1]["intention"] = "required";
                $postBody["entries"][$k_counter]["product"]["destinations"][2]["destinationName"] = "Shopping";
                $postBody["entries"][$k_counter]["product"]["destinations"][2]["intention"] = "required";

                $postBody["entries"][$k_counter]["product"]["onlineOnly"] = $product_info["product"]["onlineOnly"];

                $postBody["entries"][$k_counter]["product"]["customAttributes"][0]["name"] = "payment accepted";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][0]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][0]["value"] = "check";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][1]["name"] = "payment accepted";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][1]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][1]["value"] = "visa";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][2]["name"] = "payment accepted";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][2]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][2]["value"] = "mastercard";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][3]["name"] = "payment accepted";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][3]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][3]["value"] = "discover";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][4]["name"] = "payment accepted";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][4]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][4]["value"] = "american express";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][5]["name"] = "payment accepted";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][5]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][5]["value"] = "All purchase orders are subject to verification.";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][6]["name"] = "quantity";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][6]["type"] = "int";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][6]["value"] = $product_info["product"]["r_avail"];
                $postBody["entries"][$k_counter]["product"]["customAttributes"][7]["name"] = "model number";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][7]["type"] = "text";
                $postBody["entries"][$k_counter]["product"]["customAttributes"][7]["value"] = $product_info["product"]["mpn"];

		$k_counter++;
		}
	}

//func_print_r($postBody, $product_info["product"]);
//die("2");

        try {

//		$k_counter -= $count_skipped;
		print("\nGB: tried to submit $k_counter items as product feed \n");

                $log_text = "GB: tried to submit $k_counter items as product feed";
                func_backprocess_log("incremental feeds", $log_text);

                $optParams = array();
                $params = array('postBody' => $postBody);
                $params = array_merge($params, $optParams);
                $results = $service->products->call('custombatch', array($params), "Google_Service_ShoppingContent_ProductsCustomBatchResponse");

###
		$results_arr = (array)$results;
		$log_text = "";
		foreach ($results_arr as $k => $v){
		 if (!empty($v) && is_array($v)){
		  foreach ($v as $kk => $vv){
		   if ($kk == "entries" && !empty($vv) && is_array($vv)){
		    foreach ($vv as $kkk => $vvv){
		     if (!empty($vvv["errors"])){
		      $log_text .= "batchId: ".$vvv["batchId"]." code: " . $vvv["errors"]["code"]. " message: ".$vvv["errors"]["message"] . "\n";
		     }
		    }
		   }
		  }
		 }      
		}
		if (!empty($log_text)){
			func_backprocess_log("incremental feeds", $log_text);
		}
###

//$ginventory_new = json_encode($postBody);
//func_print_r($ginventory_new);
//              $results = $service->inventory->custombatch($ginventory_new);
        }
        catch (Google_ServiceException $e) {
                print "Error code :" . $e->getCode() . "\n";
                // Error message is formatted as "Error calling <REQUEST METHOD> <REQUEST URL>: (<CODE>) <MESSAGE OR REASON>".
                print "Error message: " . $e->getMessage() . "\n";

                $log_text = "Error code :" . $e->getCode() . "\n"."Error message: " . $e->getMessage();
                func_backprocess_log("incremental feeds", $log_text);
        }
        catch (Google_Exception $e) {
                // Other error.
                print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";

                $log_text = "An error occurred: (" . $e->getCode() . ") " . $e->getMessage();
                func_backprocess_log("incremental feeds", $log_text);
        }




//func_print_r($product_info["product"]);

//func_print_r($postBody);
//func_print_r($results_arr);


//func_print_r($log_text);

//die("test");

}

function SubmitAmazonInventoryBatch($ainventory, $a_config, $marketplaceIdArray){
        global $sql_tbl, $xcart_dir;

	if (empty($ainventory) || !is_array($ainventory)){
	        print('Amazon inventory empty\r');
		return false;
	}
######################### Avail start #########################
	$feed = <<<EOD
<?xml version="1.0" encoding="utf-8" ?>
<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
<Header>
   <DocumentVersion>1.01</DocumentVersion>
   <MerchantIdentifier>M_SELLER_354577</MerchantIdentifier>
   </Header>
   <MessageType>Inventory</MessageType>
EOD;

	$MessageID = 0;
	foreach ($ainventory as $k => $v){

                $fields = ", IFNULL($sql_tbl[variants].avail, $sql_tbl[products].r_avail) as r_avail, IFNULL($sql_tbl[variants].productcode, $sql_tbl[products].productcode) as productcode, $sql_tbl[products].cost_to_us, $sql_tbl[products].map_price, $sql_tbl[products].manufacturerid, $sql_tbl[products].eta_date_mm_dd_yyyy";
                $joins = " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
                $joins .= " INNER JOIN $sql_tbl[quick_prices] ON $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid = '0'";
                $joins .= " LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid";
                $where = " AND $sql_tbl[products_sf].productid = '$v[productid]' AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) >= '0'";

                $product = func_query_first("SELECT SQL_NO_CACHE $sql_tbl[products].product_type, $sql_tbl[products].amazon_fba_avail, $sql_tbl[pricing].price $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C')");


###
                $product["d_enable_feed"] = func_query_first_cell("SELECT d_enable_feed FROM $sql_tbl[manufacturers] WHERE manufacturerid='".$product['manufacturerid']."'");

                if ($product["d_enable_feed"] == "Y" && $product["r_avail"] <= 0){
                        $product['price'] = func_decreased_price($product["cost_to_us"], $product["price"], $product["map_price"]);
                }

		$product["productid"] = $v["productid"];
		$productcode = $product["productcode"];
		$price = $product["price"];
		$avail = $product["r_avail"];
                $product["product_availability"] = func_product_availability(false,false,false,false,false,$product);

		$ainventory[$k] = $product;

		$a_query = "Select cidev_get_amazon_price('$v[productid]') As 'aprice', M.amazon_leadtimetoship As 'aleadtime', cidev_get_amazon_quantity('$v[productid]') As 'aquantity' from xcart_products P left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid where P.productid = '$v[productid]'";
		$a_result = func_query_first($a_query);

		if ($a_result["aquantity"]==0){
			$product["product_availability"] = "out of stock";
	                $price = $a_result["aprice"];
			$product["price"] = $price;

        	        $aleadtime = $a_result["aleadtime"];

			$avail = $a_result["aquantity"];
			$product["avail"] = $avail;
		} else {
	                $price = $a_result["aprice"];
			$product["price"] = $price;

        	        $aleadtime = $a_result["aleadtime"];

			$avail = $a_result["aquantity"];
			$product["avail"] = $avail;
		}

		if ($product["product_availability"] == "in stock"||$product["product_availability"] == "out of stock"){
			$MessageID++;

/*
			$feed .= <<<EOD
<Message>
<MessageID>$MessageID</MessageID>
<OperationType>Update</OperationType>
<Inventory>
<SKU>$productcode</SKU>
<Quantity>$avail</Quantity>
<FulfillmentLatency>$aleadtime</FulfillmentLatency>
</Inventory>
</Message>
EOD;
*/

			if ($product["amazon_fba_avail"] > 0){
                        $feed .= <<<EOD
<Message>
<MessageID>$MessageID</MessageID>
<OperationType>Update</OperationType>
<Inventory>
<SKU>$productcode</SKU>
<FulfillmentCenterID>AMAZON_NA</FulfillmentCenterID>
<Lookup>FulfillmentNetwork</Lookup>
<SwitchFulfillmentTo>AFN</SwitchFulfillmentTo>
</Inventory>
</Message>
EOD;
			} elseif ($product["amazon_fba_avail"] <= 0){
                        $feed .= <<<EOD
<Message>
<MessageID>$MessageID</MessageID>
<OperationType>Update</OperationType>
<Inventory>
<SKU>$productcode</SKU>
<FulfillmentCenterID>DEFAULT</FulfillmentCenterID>
<Quantity>$avail</Quantity>
<FulfillmentLatency>$aleadtime</FulfillmentLatency>
<SwitchFulfillmentTo>MFN</SwitchFulfillmentTo>
</Inventory>
</Message>
EOD;
			}
		}
	}

	$feed .= <<<EOD
</AmazonEnvelope>
EOD;

/*

*/
	print($feed."\n\n");

	print("INVENTORY pull\n\n");
	
	$a_service = new MarketplaceWebService_Client(
	     AWS_ACCESS_KEY_ID,
	     AWS_SECRET_ACCESS_KEY,
	     $a_config,
	     APPLICATION_NAME,
	     APPLICATION_VERSION);


	$feedHandle = @fopen('php://temp', 'rw+');
	fwrite($feedHandle, $feed);
	if(!$feedHandle) die("Can't open device");
	rewind($feedHandle);


	$parameters = array (
	  'Merchant' => MERCHANT_ID,
	  'MarketplaceIdList' => $marketplaceIdArray,
	  'FeedType' => '_POST_INVENTORY_AVAILABILITY_DATA_',
	  'FeedContent' => $feedHandle,
	  'PurgeAndReplace' => false,
	  'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
	//  'MWSAuthToken' => '<MWS Auth Token>', // Optional
	);

	$request = new MarketplaceWebService_Model_SubmitFeedRequest($parameters);

	invokeSubmitFeed($a_service, $request);

	@fclose($feedHandle);

######################### Avail End #########################

######################### Price start #########################
        $feed = <<<EOD
<?xml version="1.0" encoding="utf-8" ?>
<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
<Header>
   <DocumentVersion>1.01</DocumentVersion>
   <MerchantIdentifier>M_SELLER_354577</MerchantIdentifier>
   </Header>
   <MessageType>Price</MessageType>
EOD;

        $MessageID = 0;
        foreach ($ainventory as $k => $product){

                $productcode = $product["productcode"];
                $productid = $product["productid"];
                
		$a_query = "Select cidev_get_amazon_price('$productid') As 'aprice', M.amazon_leadtimetoship As 'aleadtime', cidev_get_amazon_quantity('$v[productid]') As 'aquantity'  from xcart_products P left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid where P.productid = '$productid'";
		$a_result = func_query_first($a_query);

		if ($a_result["aquantity"]==0){
			$product["product_availability"] = "out of stock";
	                $price = $a_result["aprice"];
			$product["price"] = $price;

        	        $aleadtime = $a_result["aleadtime"];
		} else {
			$product["product_availability"] = "in stock";
	                $price = $a_result["aprice"];
			$product["price"] = $price;

        	        $aleadtime = $a_result["aleadtime"];
		}

                if ($product["product_availability"] == "in stock"||$product["product_availability"] == "out of stock"){
                        $MessageID++;

                        $feed .= <<<EOD
<Message>
<MessageID>$MessageID</MessageID>
<Price>
<SKU>$productcode</SKU>
<StandardPrice currency="USD">$price</StandardPrice>
</Price>
</Message>
EOD;
                }
        }

        $feed .= <<<EOD
</AmazonEnvelope>
EOD;

	print($feed."\n\n");

	print("INVENTORY pull\n\n");

 
        $a_service = new MarketplaceWebService_Client(
             AWS_ACCESS_KEY_ID,
             AWS_SECRET_ACCESS_KEY,
             $a_config,
             APPLICATION_NAME,
             APPLICATION_VERSION);


        $feedHandle = @fopen('php://temp', 'rw+');
        fwrite($feedHandle, $feed);
        if(!$feedHandle) die("Can't open device");
        rewind($feedHandle);


        $parameters = array (
          'Merchant' => MERCHANT_ID,
          'MarketplaceIdList' => $marketplaceIdArray,
          'FeedType' => '_POST_PRODUCT_PRICING_DATA_',
          'FeedContent' => $feedHandle,
          'PurgeAndReplace' => false,
          'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
        //  'MWSAuthToken' => '<MWS Auth Token>', // Optional
        );

        $request = new MarketplaceWebService_Model_SubmitFeedRequest($parameters);

        invokeSubmitFeed($a_service, $request);

        @fclose($feedHandle);
######################### Price end #########################
        if ($MessageID > 0) {
                print("\nAMZ: tried to submit $MessageID items as inventory feed \n");

		$log_text = "AMZ: tried to submit $MessageID items as inventory feed";
		func_backprocess_log("incremental feeds", $log_text);		
		}

}

function SubmitAmazonProductsBatch(){
        global $sql_tbl;

}

function invokeSubmitFeed(MarketplaceWebService_Interface $a_service, $request)
  {
      try {
              $response = $a_service->submitFeed($request);

                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        SubmitFeedResponse\n");
                if ($response->isSetSubmitFeedResult()) {
                    echo("            SubmitFeedResult\n");
                    $submitFeedResult = $response->getSubmitFeedResult();
                    if ($submitFeedResult->isSetFeedSubmissionInfo()) {
                        echo("                FeedSubmissionInfo\n");
                        $feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
                        if ($feedSubmissionInfo->isSetFeedSubmissionId())
                        {
                            echo("                    FeedSubmissionId\n");
                            echo("                        " . $feedSubmissionInfo->getFeedSubmissionId() . "\n");
                        }
                        if ($feedSubmissionInfo->isSetFeedType())
                        {
                            echo("                    FeedType\n");
                            echo("                        " . $feedSubmissionInfo->getFeedType() . "\n");
                        }
                        if ($feedSubmissionInfo->isSetSubmittedDate())
                        {
                            echo("                    SubmittedDate\n");
                            echo("                        " . $feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($feedSubmissionInfo->isSetFeedProcessingStatus())
                        {
                            echo("                    FeedProcessingStatus\n");
                            echo("                        " . $feedSubmissionInfo->getFeedProcessingStatus() . "\n");
                        }
                        if ($feedSubmissionInfo->isSetStartedProcessingDate())
                        {
                            echo("                    StartedProcessingDate\n");
                            echo("                        " . $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($feedSubmissionInfo->isSetCompletedProcessingDate())
                        {
                            echo("                    CompletedProcessingDate\n");
                            echo("                        " . $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT) . "\n");
                        }
                    }
                }
                if ($response->isSetResponseMetadata()) {
                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId())
                    {
                        echo("                RequestId\n");
                        echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                }

                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }

//     return $feedSubmissionInfo->getFeedProcessingStatus();

}

?>
