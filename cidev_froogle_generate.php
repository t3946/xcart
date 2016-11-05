<?php /* MODIFIED: random:20889 [2010 Apr 21 14:23][Custom development (Google Base feed adjustment)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2008 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2008           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: cidev_froogle_generate.php,v 1.42.2.29 2008/05/08 06:31:19 max Exp $
#

require "./auth.php";

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('EXCLUDE_CATEGORYID_BRANCH', 5099);

x_session_register("store_froogle_lng");
x_session_register("store_froogle_iso");
x_session_register('froogle_export_step');
x_session_register('froogle_total');
x_session_register('number_of_steps');
//x_session_register('cidev_current_store_front');
x_session_register('active_modules');

#
# Translation string to frogle-compatibility-string
#
function func_froogle_convert($str, $max_len = false) {
	static $tbl = false;

//	ini_set('memory_limit', '512M');

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

x_load('backoffice','files','taxes', 'debug');

ini_set('memory_limit', -1);
set_time_limit(0);

//$location[] = array(func_get_langvar_by_name("lbl_froogle_export"), "");
//include $xcart_dir."/include/import_tools.php";

$is_ftp_module = '';
if(function_exists("ftp_connect") && !empty($config['Froogle']['froogle_username']) && !empty($config['Froogle']['froogle_password']))
	$is_ftp_module = 'Y';

$froogle_host = 'uploads.google.com';
$froogle_file = "thefind.txt";

x_session_register("cidev_storefronts");

//func_print_r($storefronts);

if ($mode == "fcreate_sdfjk2894jksdnf974hkasd67540238ojwejkdfh923"){
	$cidev_storefronts = $storefronts;

####################
#	foreach ($cidev_storefronts as $k => $v){
#		if ($v["storefrontid"] != "34"){
#			unset($cidev_storefronts[$k]);
#		}
#	}
####################

	$cidev_storefronts[0] = func_get_storefront_info(0);

}

if ($use_next_store_front == "Y"){
	$cidev_current_store_front = "-1"; // disable sf_info
}

//func_print_r($cidev_storefronts);

if ( (!empty($cidev_storefronts) && is_array($cidev_storefronts)) && ($use_next_store_front == "Y" || $mode == "fcreate_sdfjk2894jksdnf974hkasd67540238ojwejkdfh923") ){
	foreach ($cidev_storefronts as $k => $v){
		$cidev_current_store_front = $v["storefrontid"];
		break;
	}
	unset($cidev_storefronts[$k]);
	x_session_save("cidev_storefronts");	
}

if ($cidev_current_store_front == "" && $cidev_current_store_front != "0"){
        $cidev_current_store_front = "-1"; // disable sf_info
}

//func_print_r($cidev_storefronts, $cidev_current_store_front);

$sf_info = func_get_storefront_info($cidev_current_store_front);

//func_print_r($sf_info);

//if ($use_next_store_front == "Y")
//die("123");


# Export data

if (
	!empty($sf_info) &&
	!empty($active_modules["Froogle"]) 
	&& (
		($REQUEST_METHOD == "GET" && ($mode == "fcreate_sdfjk2894jksdnf974hkasd67540238ojwejkdfh923" || $mode == 'fcontinue_dfv385erfkljhsdkfy9238470rjkweufashfgxdrtr7') )
	)
) {

	print($sf_info["domain"] . ": TheFind.com product feed generation. <br />");
	

	$froogle_iso = ($froogle_iso && is_string($froogle_iso) && strlen($froogle_iso) == 2) ? strtolower($froogle_iso) : false;
	
	$froogle_location = $config['Froogle']['froogle_used_https_links'] == 'Y' ? $https_location : $http_location;
	$froogle_scheme = $config['Froogle']['froogle_used_https_links'] == 'Y' ? 'https://' : 'http://';

	if ($sf_info['prefix'] == "MAIN_SF_PREFIX"){
		$froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . "AR-" . $froogle_file;
		$sf_info['prefix'] = "AR-";
	} else {
	        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"] . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;

#
##
###
                $cidev_get_files_location = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"];

                if (!file_exists($cidev_get_files_location))
                        func_mkdir($cidev_get_files_location);
###
##
#

	}

	if (($mode == "fcreate_sdfjk2894jksdnf974hkasd67540238ojwejkdfh923" || $use_next_store_front == "Y") && is_file($froogle_file)) {
		unlink($froogle_file);
		$froogle_export_step = '';
		$number_of_steps = '';
		$froogle_total = '';
	}

	$fp = func_fopen($froogle_file, 'a+', true);

	if ($fp !== false) {

		func_flush(func_get_langvar_by_name('lbl_exporting_data_', null, false, true) . "<br />\n");
		# Write file header

		# Full header: 
		# title\tdescription\tlink\timage_link\tid\texpiration_date\tlabel\tprice\tprice_type\tcurrency\tpayment_accepted\tpayment_notes\tquantity\tbrand\tupc\tisbn\tmemory\tprocessor_speed\tmodel_number\tsize\tweight\tcondition\tcolor\tactor\tartist\tauthor\tformat\tproduct_type\tlocation

		if (empty($froogle_export_step)) {
			fwrite($fp, utf8_encode("Title\tDescription\tImage_Link\tPage_URL\tDirect_URL\tPrice\tSKU\tUPC-EAN\tMPN\tISBN\tUnique_ID\tFree Shipping\tOnline_Only\tStock_Quantity\tBrand\tCategories\tCondition\tHot or Not\tCompatible_With\tSimilar_To\tWeight"."\n"));
		}

		$where = "";
		$fields = "";
		$joins = "";

		if (!empty($active_modules['Multiple_Storefronts'])) {
			$fields .= ", $sql_tbl[products_sf].sfid";
			$joins .= " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
    			$where .= " AND $sql_tbl[products_sf].sfid = $cidev_current_store_front";
		}

		if ($config["General"]["disable_outofstock_products"] == "Y") {
			if (!empty($active_modules['Product_Options'])) {
				$where .= " AND IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) > '0'";
			} else {
				$where .= " AND $sql_tbl[products].avail > '0'";
			}
		}

		$joins .= " INNER JOIN $sql_tbl[quick_prices] ON $sql_tbl[quick_prices].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].membershipid = '0'";
		if (!empty($active_modules['Product_Options'])) {
			$joins .= " LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid AND $sql_tbl[quick_prices].variantid = $sql_tbl[variants].variantid";
			$fields .= ", IFNULL($sql_tbl[variants].productcode, $sql_tbl[products].productcode) as productcode, IFNULL($sql_tbl[variants].avail, $sql_tbl[products].avail) as avail, IFNULL($sql_tbl[variants].weight, $sql_tbl[products].weight) as weight";
		}

		if ($froogle_lng) {
			$joins .= " LEFT JOIN $sql_tbl[products_lng] ON $sql_tbl[products].productid = $sql_tbl[products_lng].productid AND $sql_tbl[products_lng].code = '$froogle_lng'";
			$fields .= ", IF($sql_tbl[products_lng].product != '', $sql_tbl[products_lng].product, $sql_tbl[products].product) as product, IF($sql_tbl[products_lng].descr != '', $sql_tbl[products_lng].descr, $sql_tbl[products].descr) as descr, IF($sql_tbl[products_lng].fulldescr != '', $sql_tbl[products_lng].fulldescr, $sql_tbl[products].fulldescr) as fulldescr";
		}

		if (!empty($active_modules['Manufacturers'])) {
			$fields .= ", IF ($sql_tbl[manufacturers_lng].manufacturer != '', $sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer";
			$joins .= " LEFT JOIN $sql_tbl[manufacturers] ON $sql_tbl[products].manufacturerid = $sql_tbl[manufacturers].manufacturerid LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[products].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$froogle_lng'";
		}

		if (!empty($active_modules['Brands'])) {
			$fields .= ", IF ($sql_tbl[brands_lng].brand != '', $sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand";
			$joins .= " LEFT JOIN $sql_tbl[brands] ON $sql_tbl[products].brandid = $sql_tbl[brands].brandid LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[products].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$froogle_lng'";
		}

		if (empty($froogle_total)) {
			$froogle_total = count(func_query_column("SELECT COUNT(*) FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' AND ($sql_tbl[pricing].price > '0' OR $sql_tbl[products].product_type = 'C') $where GROUP BY $sql_tbl[products].productid"));
		}

		if (!empty($froogle_total) && $froogle_total > 0) {
			
			if (!is_numeric($froogle_export_step)) {
				$froogle_export_step = 1;
		        }

			if (!is_numeric($number_of_steps)) {
				$number_of_steps = ceil($froogle_total / FROOGLE_EXPORT_LIMIT);
			}
			
			$limit_from = ($froogle_export_step - 1) * FROOGLE_EXPORT_LIMIT;

			$limit = 'LIMIT ' . $limit_from . ', ' . FROOGLE_EXPORT_LIMIT;
			
			if ($froogle_export_step > $number_of_steps) {
				$user_account = $_user_account;
				fclose($fp);
				$top_message["type"] = "I";
				$top_message["content"] = func_get_langvar_by_name("msg_adm_froogle_file_success");
                		x_session_unregister('froogle_export_step');
//				func_header_location('cidev_froogle_generate.php?mode=ffinish');
				func_header_location("cidev_froogle_generate.php?mode=fcontinue_dfv385erfkljhsdkfy9238470rjkweufashfgxdrtr7&use_next_store_front=Y&cidev_current_store_front=".$cidev_current_store_front);
			}

#
##
###
//                      db_query("RESET QUERY CACHE");
###
##
#

			$products = db_query("SELECT NOW(), $sql_tbl[products].*, $sql_tbl[categories].categoryid_path, $sql_tbl[pricing].price, $sql_tbl[images_T].image_path $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C') ORDER BY $sql_tbl[products].product $limit");
		    

            		ini_set('memory_limit', '512M');

			func_flush(func_get_langvar_by_name('lbl_exporting_data_pass_', array('pass' => 1), false, true) . "<br />\n");
			
			$productids = func_query_column("SELECT $sql_tbl[products].productid, $sql_tbl[pricing].price, $sql_tbl[products].product_type FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C') ORDER BY $sql_tbl[products].product $limit", 'productid');
			func_flush(func_get_langvar_by_name('lbl_exporting_data_pass_', array('pass' => 2), false, true) . "<br />\n");
			
			$product_categories = func_query_hash("SELECT $sql_tbl[products].productid, $sql_tbl[categories].categoryid_path FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[products]) WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' AND $sql_tbl[products].productid IN ('" . implode('\',\'', $productids) . "')$sf_cat_condition", 'productid', true, true);
			func_flush(func_get_langvar_by_name('lbl_exporting_data_pass_', array('pass' => 3), false, true) . "<br />\n");

			$cnt = 0;

			$msg = func_get_langvar_by_name('lbl_step_x_of_y', 
				array(
					'X' => $froogle_export_step,
					'Y'	=> $number_of_steps,
				), false, true);

			func_flush($msg . '<br />' . "\n");
			
		        $froogle_export_step++;




			while ($product = db_fetch_array($products)) {

#
## https://basecamp.com/2070980/projects/1577907-x-cart/messages/14168738-internal-sf-tasks
###
//                                if ($product["min_amount"] > 1){
//                                        continue;
//                                }
###
##
#

#
##
###

                                if (!empty($product["eta_date_mm_dd_yyyy"])){
//                                        $eta_date_mm_dd_yyyy_time_arr = explode("/", $product["eta_date_mm_dd_yyyy"]);
//                                        if (!empty($eta_date_mm_dd_yyyy_time_arr) && is_array($eta_date_mm_dd_yyyy_time_arr)){
//                                                $eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $eta_date_mm_dd_yyyy_time_arr[0], $eta_date_mm_dd_yyyy_time_arr[1], $eta_date_mm_dd_yyyy_time_arr[2]);
//                                                if ($eta_date_mm_dd_yyyy_time > time())
                                                if ($product["eta_date_mm_dd_yyyy"] > time()){
//                                                        print"ETA date in future.";
                                                        continue;
                                                }
//                                        }
                                }
###
##
#

		                if(isset($product['sfid']) && $product['sfid'] != 0) {
                		    $product['froogle_location'] = $froogle_scheme . func_get_http_location_sf($product['sfid']);
		                } else {
                		    $product['froogle_location'] = $froogle_location;
		                }


#
##
###
                                $tmp_upc = trim($product['upc']);
                                $tmp_upc = isset($tmp_upc) ? abs(intval($tmp_upc)) : 0;
                                if (empty($tmp_upc) || $tmp_upc == "0"){
                                        $product['upc'] = "";
                                }

                                $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='P' AND resource_id='$product[productid]'");
                                $clean_url_link .="/";

//		                $product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'];
				$product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/'. $clean_url_link;


				if (!empty($sf_info['prefix'])){

                		        $utm_medium = $product['brand'];
		                        $utm_medium = preg_replace('/[^\w]/', '', $utm_medium);
                		        $utm_medium = preg_replace('/[_]/', '', $utm_medium);

					$utm_campaign = $product['productcode'];
					$utm_campaign = preg_replace('/[^\w]/', '', $utm_campaign);
					$utm_campaign = preg_replace('/[_]/', '', $utm_campaign);
/*
					$product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'] . '&utm_source=' . $sf_info['prefix'] . 'froogle_Google-Shopping&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
					$product['adwords_redirect'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'] . '&utm_source=' . $sf_info['prefix'] . 'froogle_Product-Ads&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
					$product["adwords_grouping"] = $product['manufacturerid'];
					$product['page_url'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'] . '&utm_source=' . $sf_info['prefix'] . 'thefind&utm_medium=feed&utm_campaign='.$product['productcode'];
*/

                                        $product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/' . $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'froogle_Google-Shopping&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
                                        $product['adwords_redirect'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/' . $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'froogle_Product-Ads&utm_medium='.$utm_medium.'&utm_campaign='.$utm_campaign;
                                        $product["adwords_grouping"] = $product['manufacturerid'];
                                        $product['page_url'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/'. $clean_url_link . '?utm_source=' . $sf_info['prefix'] . 'thefind&utm_medium=feed&utm_campaign='.$utm_campaign;

				}
###
##
#

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
//				$cats_path = !empty($cats) ? '"'.implode('","', $cats).'"' : '';

                                if (!empty($cats[0])){
                                        $cats_path = $cats[0];
                                }

//                                $cats_path = func_query_first_cell("SELECT $sql_tbl[categories].category FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].categoryid=$sql_tbl[categories].categoryid WHERE $sql_tbl[products_categories].productid='$product[productid]' AND $sql_tbl[products_categories].main='Y'");#Internal SF tasks: Google Base feed "Product type field"


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


#
##
###
                                if ($product["new_map_price"] > $product["price"]){
                                        $product["price"] = $product["new_map_price"];
                                        $product['taxed_price'] = $product['price'];
                                }

                                if ($product["min_amount"] > 1){
                                        $new_price =  func_query_first_cell("SELECT MIN(price) FROM $sql_tbl[pricing] WHERE $sql_tbl[pricing].quantity <= '$product[min_amount]' AND $sql_tbl[pricing].variantid = 0 AND $sql_tbl[pricing].productid = '$product[productid]'");
                                        $new_price *= $product["min_amount"];
                                        $new_price = func_tax_price($new_price, $product['productid'], false, NULL, $ci);

                                        $product["price"] = $new_price['taxed_price'];
                                        $product['taxed_price'] = $new_price['taxed_price'];
                                }
###
##
#


#
##
###

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

###
##
#

#
##
###
                                if ($product["list_price"] > 20 && (1 - ($product["price"]/$product["list_price"]))>0.50){
                                        $product['adwords_labels'] .= ", offlist";
                                }
###
##
#

	
				# Define "mpn"
				global $xcart_dir;
				$mpn = Xcart\Product::model(['productid'=>$product['productid']])->getMPN();
/*				$pos = strpos($product['productcode'], '-');
				$mpn = '';
	
				if ($pos && is_numeric($pos) && $pos + 1 != strlen($product['productcode'])) {
					$mpn = substr($product['productcode'], $pos + 1);
				}

                                else {
                                        $mpn = $product['productcode'];
                                }

                                if (strlen($mpn) < 3){
                                                $mpn .= "-GBFIX";
                                }*/

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
	
				# Define "shipping"
			
				if ($product['free_ship_zone'] == -1) {
					$shipping = '';
				} elseif ($product['free_ship_zone'] == 0) {
					$shipping = '::Ground:0.00';
				} else {
					$zone_countries = func_query_column('SELECT field FROM '.$sql_tbl['zone_element']. ' WHERE zoneid='.$product['free_ship_zone'].' AND field_type = "C"');
					$shipping = implode('::Ground:0.00, ', $zone_countries).'::Ground:0.00';
				}
	
	
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


				$post = iconv("UTF-8", "ISO-8859-1//TRANSLIT",func_froogle_convert($product['product']))."\t".
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


				$post .="\n";
				fwrite($fp, iconv("UTF-8", "UTF-8//IGNORE", utf8_encode($post))); 

				$cnt++;
				if ($cnt % 100 == 0) {
					func_flush(".");
					if($cnt % 5000 == 0) {
						func_flush("<br />\n");
					}

					func_flush();
				}
			}


			func_header_location('cidev_froogle_generate.php?mode=fcontinue_dfv385erfkljhsdkfy9238470rjkweufashfgxdrtr7&cidev_current_store_front='.$cidev_current_store_front);
		}		
	}
	else {
		die("EEEEEEEEEEEEror");
		$top_message["type"] = "E";
		$top_message["content"] = func_get_langvar_by_name("msg_adm_froogle_file_unsuccess");
	}
	func_header_location("cidev_froogle_generate.php?use_next_store_front=Y&mode=fcontinue_dfv385erfkljhsdkfy9238470rjkweufashfgxdrtr7&cidev_current_store_front=".$cidev_current_store_front);
}


die("DONE!");
?>
