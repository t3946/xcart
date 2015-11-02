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
# $Id: froogle.php,v 1.42.2.29 2008/05/08 06:31:19 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('EXCLUDE_CATEGORYID_BRANCH', 5099);

x_session_register("store_froogle_lng");
x_session_register("store_froogle_iso");
x_session_register('froogle_export_step');
x_session_register('froogle_total');
x_session_register('number_of_steps');
x_session_register('current_storefront');
x_session_register('active_modules');

#
# Translation string to frogle-compatibility-string
#
function func_froogle_convert($str, $max_len = false) {
	static $tbl = false;

	ini_set('memory_limit', '512M');

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

set_time_limit(0);

$location[] = array(func_get_langvar_by_name("lbl_froogle_export"), "");
include $xcart_dir."/include/import_tools.php";

$is_ftp_module = '';
if(function_exists("ftp_connect") && !empty($config['Froogle']['froogle_username']) && !empty($config['Froogle']['froogle_password']))
	$is_ftp_module = 'Y';

$froogle_host = 'uploads.google.com';

x_session_register("store_froogle_filename");
x_session_register("cidev_feed_type");

$sf_info = func_get_storefront_info($current_storefront);

# Export data

if (
	!empty($active_modules["Froogle"]) 
	&& (
		($REQUEST_METHOD == "POST" && $mode == "fcreate") 
		|| ($REQUEST_METHOD == 'GET' && $mode == 'fcontinue')
	)
) {
	if (empty($froogle_file)) {
		if (empty($config['froogle_export_file'])) {
			$froogle_file = ($config['Froogle']['froogle_username'] ? $config['Froogle']['froogle_username'] : "froogle").".txt";
		} else {
			$froogle_file = $config['froogle_export_file'];
		}
	} elseif($froogle_file != $config['froogle_export_file']) { 
		func_array2insert("config", array('name' => 'froogle_export_file', 'comment' => 'Froogle file for export','value' => $froogle_file, 'type' => 'text'), true);
	}
	$store_froogle_filename = $froogle_file;



	if (!empty($_POST["cidev_feed_type"])){
		$cidev_feed_type = $_POST["cidev_feed_type"];
		x_session_save("cidev_feed_type");
	}


	$froogle_iso = ($froogle_iso && is_string($froogle_iso) && strlen($froogle_iso) == 2) ? strtolower($froogle_iso) : false;
	
	$froogle_location = $config['Froogle']['froogle_used_https_links'] == 'Y' ? $https_location : $http_location;
    $froogle_scheme = $config['Froogle']['froogle_used_https_links'] == 'Y' ? 'https://' : 'http://';

    if ($config['Froogle']['froogle_only_current_storefront'] == 'Y' && is_numeric($current_storefront)) {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
    } else {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $froogle_file;
    }

	if ($REQUEST_METHOD == 'POST' && is_file($froogle_file)) {
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


		    if ($cidev_feed_type == "thefind"){

			fputs($fp, '"Title"'."\t".'"Description"'."\t".'"Image_Link"'."\t".'"Page_URL"'."\t".'"Direct_URL"'."\t".'"Price"'."\t".'"SKU"'."\t".'"UPC-EAN"'."\t".'"MPN"'."\t".'"ISBN"'."\t".'"Unique_ID"'."\t".'"Free Shipping"'."\t".'"Online_Only"'."\t".'"Stock_Quantity"'."\t".'"Brand"'."\t".'"Categories"'."\t".'"Condition"'."\t".'"Hot or Not"'."\t".'"Compatible_With"'."\t".'"Similar_To"'."\t".'"Weight"'."\n");

		    }
		    else {

			if ($froogle_iso) {
				fputs($fp, "title\tdescription\tlink\timage link\tid\tprice\tpayment accepted\tpayment notes\tquantity\tweight\texpiration date\tbrand\tcondition\tproduct type\tmpn\tmodel number\tgtin\tcompatible with\tonline only\tshipping\tavailability\n");

			} else {
				fputs($fp, "title\tdescription\tlink\timage link\tid\tprice\tpayment accepted\tpayment notes\tquantity\tweight\texpiration date\tbrand\tcondition\tproduct type\tmpn\tmodel number\tgtin\tcompatible with\tonline only\tshipping\tavailability\n");
			}

		    }
		}

		$where = "";
		$fields = "";
		$joins = "";

		if (!empty($active_modules['Multiple_Storefronts'])) {
            $fields .= ", $sql_tbl[products_sf].sfid";
			$joins .= " INNER JOIN $sql_tbl[products_sf] ON  $sql_tbl[products].productid= $sql_tbl[products_sf].productid";
            if ($config['Froogle']['froogle_only_current_storefront'] == 'Y') {
    			$where .= " AND $sql_tbl[products_sf].sfid = $current_storefront";
            }
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
				func_header_location('froogle.php?mode=ffinish');
			}

			$products = db_query("SELECT $sql_tbl[products].*, $sql_tbl[categories].categoryid_path, $sql_tbl[pricing].price, $sql_tbl[images_T].image_path $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C') ORDER BY $sql_tbl[products].product $limit");
		    
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

                if(isset($product['sfid']) && $product['sfid'] != 0) {
                    $product['froogle_location'] = $froogle_scheme . func_get_http_location_sf($product['sfid']);
                } else {
                    $product['froogle_location'] = $froogle_location;
                }


#
##
###
                $product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'];


		if (!empty($sf_info['prefix'])){
			$product['link'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'] . '&utm_source=' . $sf_info['prefix'] . 'froogle&utm_medium=cpc&utm_campaign='.$product['productcode'];

			$product['page_url'] = $product['froogle_location'] . constant('DIR_CUSTOMER') . '/product.php?productid=' . $product['productid'] . '&utm_source=' . $sf_info['prefix'] . 'thefind&utm_medium=feed&utm_campaign='.$product['productcode'];
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
				$cats_path = !empty($cats) ? '"'.implode('","', $cats).'"' : '';

				# Define full description
				if (!empty($product['fulldescr']))
					$product['descr'] = $product['fulldescr'];

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
	
				# Define "mpn"
	
				$pos = strpos($product['productcode'], '-');
				$mpn = '';
	
				if ($pos && is_numeric($pos) && $pos + 1 != strlen($product['productcode'])) {
					$mpn = substr($product['productcode'], $pos + 1);
				}
	
				# Define "compatible with"
	
				$upselling_products = func_query("SELECT p.product_froogle, p.productcode, p.upc, b.brand FROM $sql_tbl[product_links] as pl, $sql_tbl[products] as p LEFT JOIN $sql_tbl[brands] b ON b.brandid=p.brandid WHERE pl.productid1=$product[productid] AND p.productid=pl.productid2");
	
				$compatible_with = '';
	
				if (!empty($upselling_products) && is_array($upselling_products)) {
	
					foreach ($upselling_products as $up) {
						$up_pos = strpos($up['productcode'], '-');
						$up_mpn = '';
						if ($up_pos && is_numeric($up_pos) && $up_pos + 1 != strlen($up['productcode'])) {
							$up_mpn = substr($up['productcode'], $up_pos + 1);
						}
						if ($compatible_with != '') {
							$compatible_with .= ', ';
						}
						$compatible_with .= $up['product_froogle'].':'.$up_mpn.':'.$up['upc'].':'.$up['brand'];
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
	
	
				# Post string
# START: random:20889 [2010 Apr 21 14:23] 
/* DISABLED! msg: 538076042
			if ($product['free_ship_zone'] != -1) {
				$free_ship_str = func_froogle_convert(func_get_langvar_by_name('lbl_free_shipping', NULL, false, true));
				$post = func_froogle_convert($product['product'], 78-strlen($free_ship_str)). ': '.$free_ship_str;
			} else {
				$post = func_froogle_convert($product['product'], 80);
			}
			$post .= "\t".
*/


			    if ($cidev_feed_type == "thefind"){


                                # Define Detailed product image
                                $tmp = func_query_first("SELECT id, imageid, image_path FROM $sql_tbl[images_D] WHERE $sql_tbl[images_D].id = '$product[productid]' AND $sql_tbl[images_D].avail='Y' ORDER BY orderby LIMIT 1");
                                $tmbn_d = "";
                                $image_path = "";
                                $image_type = "";

                                if (!empty($tmp['imageid'])) {
                                        $image_path = $tmp['image_path'];
                                        $image_type = "D";

                                        if (!empty($image_path))
                                                $tmbn_d = func_get_image_url($tmp['imageid'], $image_type, $image_path);
                                        if ($tmbn_d === false || empty($tmbn_d)) {
                                                $tmbn_d = $product['froogle_location'] . '/image.php?id=' . $tmp['imageid'] . '&type=' . $image_type;

                                        } elseif (strpos($tmbn_d, $https_location) !== false) {
                                                $tmbn_d = str_replace($https_location, $product['froogle_location'], $tmbn_d);
                                        }

					$tmbn = $tmbn_d;
                                }

				if (strpos($tmbn, "default_image") !== false) {
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


				$post = '"'.func_froogle_convert($product['product']).'"'."\t".
				'"'.func_froogle_convert($product['descr']).'"'."\t".
				'"'.$tmbn.'"'."\t".
				'"'.$product['page_url'].'"'."\t".
				'"'.$product['froogle_location'] . '/product.php?productid='.$product["productid"].'"'."\t".
				'"'.number_format(round($product['price'], 2), 2, ".", "").'"'."\t".
				'"'.$product['productcode'].'"'."\t".
				'"'.$upc_ean.'"'."\t".
				'"'.$mpn.'"'."\t".
				'"'.$isbn.'"'."\t".
				'"'.$product['productid'].'"'."\t".
				'"'.$free_ship_zone.'"'."\t".
				'"Yes"'."\t".
				'"'.(($product['avail'] < 0) ? 0 : ($product['avail'])).'"'."\t".
				'"'.func_froogle_convert($product['brand']).'"'."\t".
				$cats_path."\t".
				'"new"'."\t".
				'"'.$hot_or_not.'"'."\t".
				'"'.$compatible_with.'"'."\t".
				'"'.$similar_to.'"'."\t".
				'"'.$product['weight'].'"';

			    }
			    else {

				$post = func_froogle_convert($product['product_froogle'], 80)."\t".
# END: random:20889 [2010 Apr 21 14:23] 
				func_froogle_convert($product['descr'], 65536)."\t".
				$product['link'] . "\t".
				$tmbn."\t".
				$product['productid']."\t".
				number_format(round($product['price'], 2), 2, ".", "")."\t".
				func_froogle_convert($config['Froogle']['froogle_payment_accepted'], 65536)."\t".
				func_froogle_convert($config['Froogle']['froogle_payment_notes'], 65536)."\t".
				(($product['avail'] < 0) ? 0 : ($product['avail']))."\t".
				$product['weight']."\t".
				date("Y-m-d", time()+(empty($config['Froogle']['froogle_expiration_date']) ? 0.5 : $config['Froogle']['froogle_expiration_date'])*86400)."\t".
				func_froogle_convert($product['brand'], 256)."\t".
				"new\t".
				"$cats_path"."\t".
				"$mpn\t".
				"$mpn\t".
				trim($product['upc']) . "\t".
				trim($compatible_with) . "\t".
				"$online_only\t".
				"$shipping\t" . 
		                'in stock';
			    }


				fputs($fp, $post."\n");
				$cnt++;
				if ($cnt % 100 == 0) {
					func_flush(".");
					if($cnt % 5000 == 0) {
						func_flush("<br />\n");
					}

					func_flush();
				}
			}
			func_header_location('froogle.php?mode=fcontinue');
		}		
	}
	else {
		$top_message["type"] = "E";
		$top_message["content"] = func_get_langvar_by_name("msg_adm_froogle_file_unsuccess");
	}

	if ($froogle_lng)
		$store_froogle_lng = $froogle_lng;

	if ($froogle_iso)
		$store_froogle_iso = $froogle_iso;

	func_header_location("froogle.php");
}
elseif(!empty($active_modules["Froogle"]) && $REQUEST_METHOD == "POST" && $mode == "fdownload" && $froogle_file) {
    
    if ($config['Froogle']['froogle_only_current_storefront'] == 'Y' && is_numeric($current_storefront)) {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
    } else {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $froogle_file;
    }
	
    # Download export file
	if (!file_exists($froogle_file)) {
		$top_message['content'] = func_get_langvar_by_name("lbl_file_not_found");
		$top_message['type'] = "E";
		func_header_location("froogle.php");
	}

	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=".basename($froogle_file));
	func_readfile($froogle_file);
	exit;
}
elseif(!empty($active_modules["Froogle"]) && $REQUEST_METHOD == "POST" && $mode == "fupload" && $froogle_file && $is_ftp_module) {
	$store_froogle_filename = $froogle_file;
    
    if ($config['Froogle']['froogle_only_current_storefront'] == 'Y' && is_numeric($current_storefront)) {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
    } else {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $froogle_file;
    }
	
    # Upload export file to Froogle server
	if (!file_exists($froogle_file)) {
		$top_message['content'] = func_get_langvar_by_name("lbl_file_not_found");
		$top_message['type'] = "E";
		func_header_location("froogle.php");
	}

	if (function_exists("ftp_connect")) {
		$ftp = ftp_connect($froogle_host);
		$top_message["type"] = "E";
		if($ftp && @ftp_login($ftp, $config['Froogle']['froogle_username'], $config['Froogle']['froogle_password'])) {
			ftp_pasv($ftp, true);
			$fp = func_fopen($froogle_file, "r", true);
			if ($fp) {
				if (@ftp_fput($ftp, basename($froogle_file), $fp, FTP_BINARY)) {
					$top_message["content"] = func_get_langvar_by_name("msg_adm_froogle_success");
					$top_message["type"] = "I";
				}
				else {
					$top_message["content"] = func_get_langvar_by_name("msg_adm_err_froogle_FTP_write_failed");
				}

				fclose($fp);
			}
			else {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_froogle_file_not_found");
			}

			ftp_quit($ftp);
		}
		else {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_err_froogle_FTP_failed");
		}
	}
	else {
		@exec("ftp -v -u ftp://".$config['Froogle']['froogle_username'].":".$config['Froogle']['froogle_password']."@".$froogle_host."/".func_shellquote(basename($froogle_file))." ".func_shellquote($froogle_file)." 2>&1", $res);
		$res = @implode("\n", $res);
		if (strpos($res, "226 ") !== false) {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_froogle_success");
			$top_message["type"] = "I";
		}
		else {
			$top_message["type"] = "E";
			$top_message["content"] = func_get_langvar_by_name("msg_adm_err_froogle_FTP_failed");
		}
	}

	func_header_location("froogle.php");
}



$smarty->assign("cidev_sf_info_prefix", $sf_info['prefix']);

$smarty->assign("cidev_feed_type", $cidev_feed_type);

$smarty->assign("froogle_file", $store_froogle_filename);
$smarty->assign("def_froogle_file", ($config['Froogle']['froogle_username'] ? $config['Froogle']['froogle_username'] : "froogle").".txt");

$smarty->assign("is_ftp_module", $is_ftp_module);

$smarty->assign("main", "froogle_export");

if ($store_froogle_iso)
	$smarty->assign("froogle_iso", $store_froogle_iso);
$smarty->assign("froogle_lng", $store_froogle_lng ? $store_froogle_lng : $shop_language);

# Assign the current location line
$smarty->assign("location", $location);

?>
