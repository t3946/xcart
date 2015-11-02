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

x_load('backoffice','files','taxes', 'debug', 'froogle');

set_time_limit(0);

$location[] = array(func_get_langvar_by_name("lbl_froogle_export"), "");
include $xcart_dir."/include/import_tools.php";

$is_ftp_module = '';
if(function_exists("ftp_connect") && !empty($config['Froogle']['froogle_username']) && !empty($config['Froogle']['froogle_password']))
	$is_ftp_module = 'Y';

$froogle_host = 'uploads.google.com';

x_session_register("store_froogle_filename");
x_session_register("cidev_feed_type");
x_session_register("cidev_exclude_product_without_img");

x_session_register("cidev_number_clicks");
x_session_register("cidev_max_cpc_group");

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
//			$froogle_file = ($config['Froogle']['froogle_username'] ? $config['Froogle']['froogle_username'] : "froogle").".txt";
			$froogle_file = "froogle.txt";
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

		if ($cidev_feed_type == "froogle_googlebase"){
			db_query("UPDATE $sql_tbl[config] SET value='$_POST[cidev_max_cpc_group]' WHERE name='froogle_max_cpc_group_last_used'");
			db_query("UPDATE $sql_tbl[config] SET value='$_POST[cidev_number_clicks]' WHERE name='froogle_number_clicks_last_used'");
		}
	}


        if (!empty($_POST["cidev_number_clicks"])){
                $cidev_number_clicks = $_POST["cidev_number_clicks"];
                x_session_save("cidev_number_clicks");
        }

        if (!empty($_POST["cidev_max_cpc_group"])){
                $cidev_max_cpc_group = $_POST["cidev_max_cpc_group"];
                x_session_save("cidev_max_cpc_group");
        }


        if ($REQUEST_METHOD == "POST"){
                $cidev_exclude_product_without_img = $_POST["cidev_exclude_product_without_img"];
		if (empty($cidev_exclude_product_without_img))
			$cidev_exclude_product_without_img = "";
                x_session_save("cidev_exclude_product_without_img");
        }


	$froogle_iso = ($froogle_iso && is_string($froogle_iso) && strlen($froogle_iso) == 2) ? strtolower($froogle_iso) : false;
	
	$froogle_location = $config['Froogle']['froogle_used_https_links'] == 'Y' ? $https_location : $http_location;
    $froogle_scheme = $config['Froogle']['froogle_used_https_links'] == 'Y' ? 'https://' : 'http://';


#
##
###
	$cidev_get_files_location = func_get_files_location();

        if (!file_exists($cidev_get_files_location))
                func_mkdir($cidev_get_files_location);

###
##
#


//    if ($config['Froogle']['froogle_only_current_storefront'] == 'Y' && is_numeric($current_storefront)) {
//        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
//    } else {
//        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $froogle_file;
//    }

#
##
###
                if ($sf_info['prefix'] == "MAIN_SF_PREFIX"){
                        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . "AR-" . $froogle_file;
                        $sf_info['prefix'] = "AR-";
                } else {
                        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"] . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;

                        $cidev_get_files_location = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"];

                        if (!file_exists($cidev_get_files_location)){
                                func_mkdir($cidev_get_files_location);
                        }
                }
###
##
#


//func_print_r($froogle_file, $sf_info);
//die();

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

			fwrite($fp, utf8_encode("Title\tDescription\tImage_Link\tPage_URL\tDirect_URL\tPrice\tSKU\tUPC-EAN\tMPN\tISBN\tUnique_ID\tFree Shipping\tOnline_Only\tStock_Quantity\tBrand\tCategories\tCondition\tHot or Not\tCompatible_With\tSimilar_To\tWeight"."\n"));

		    }
		    else {

			if ($froogle_iso) {
				fputs($fp, utf8_encode("title\tdescription\tlink\tadwords_redirect\tadwords_grouping\tadwords_labels\timage link\tadditional image link\tid\tprice\tpayment accepted\tpayment notes\tquantity\tweight\texpiration date\tbrand\tcondition\tproduct type\tmpn\tmodel number\tgtin\tcompatible with\tonline only\tshipping\tavailability\tmultipack\n"));

			} else {
				fputs($fp, utf8_encode("title\tdescription\tlink\tadwords_redirect\tadwords_grouping\tadwords_labels\timage link\tadditional image link\tid\tprice\tpayment accepted\tpayment notes\tquantity\tweight\texpiration date\tbrand\tcondition\tproduct type\tmpn\tmodel number\tgtin\tcompatible with\tonline only\tshipping\tavailability\tmultipack\n"));
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


//$where .= " AND $sql_tbl[products].productid='255663'";



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

//			$products = db_query("SELECT NOW(), $sql_tbl[products].*, $sql_tbl[categories].categoryid_path, $sql_tbl[pricing].price, $sql_tbl[images_T].image_path $fields FROM ($sql_tbl[categories], $sql_tbl[products_categories], $sql_tbl[pricing], $sql_tbl[products]) LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[products].productid = $sql_tbl[images_T].id $joins WHERE $sql_tbl[products].productid = $sql_tbl[products_categories].productid AND $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[pricing].priceid = $sql_tbl[quick_prices].priceid AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[categories].avail = 'Y' $where GROUP BY $sql_tbl[products].productid HAVING (price > '0' OR $sql_tbl[products].product_type = 'C') ORDER BY $sql_tbl[products].product $limit");

			$products = db_query("SELECT SQL_NO_CACHE $sql_tbl[products].productid 
                                FROM $sql_tbl[products] 
                                INNER JOIN $sql_tbl[products_sf] 
                                ON $sql_tbl[products].productid= $sql_tbl[products_sf].productid
                                INNER JOIN $sql_tbl[products_categories]
                                ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                                INNER JOIN $sql_tbl[categories]
                                ON $sql_tbl[categories].categoryid = $sql_tbl[products_categories].categoryid
                                WHERE 
                                $sql_tbl[products_sf].sfid = $current_storefront
                                AND $sql_tbl[categories].avail = 'Y'
                                AND $sql_tbl[products_categories].main = 'Y'
                                AND $sql_tbl[products].forsale = 'Y'");


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

			    if ($cidev_feed_type == "thefind"){
				$post = GetTheFindOneRow($product["productid"]);
			    }
			    else {
				$post = GetGoogleBaseOneRow($product["productid"]);
			    }

			    if ( ($cidev_feed_type == "thefind") || ($tmbn_no_img != "Y") || ($tmbn_no_img == "Y" && $cidev_exclude_product_without_img != "Y" )){

				if ($cidev_feed_type == "thefind"){
					$post .="\n";
					fwrite($fp, iconv("UTF-8", "UTF-8//IGNORE", utf8_encode($post))); 
				}
				else {
					fputs($fp, iconv("UTF-8", "UTF-8//IGNORE", utf8_encode($post))."\n");
				}

				$cnt++;
				if ($cnt % 100 == 0) {
					func_flush(".");
					if($cnt % 5000 == 0) {
						func_flush("<br />\n");
					}

					func_flush();
				}
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

/*    
    if ($config['Froogle']['froogle_only_current_storefront'] == 'Y' && is_numeric($current_storefront)) {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
    } else {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $froogle_file;
    }
*/
#
##
###
                if ($sf_info['prefix'] == "MAIN_SF_PREFIX"){
                        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . "AR-" . $froogle_file;
                        $sf_info['prefix'] = "AR-";
                } else {
                        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"] . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
                }
###
##
#

	
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

#
##
###
	$froogle_file = "froogle.txt";
###
##
#

	$store_froogle_filename = $froogle_file;

/*    
    if ($config['Froogle']['froogle_only_current_storefront'] == 'Y' && is_numeric($current_storefront)) {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
    } else {
        $froogle_file = func_get_files_location() . DIRECTORY_SEPARATOR . $froogle_file;
    }
*/

#
##
###
                if ($sf_info['prefix'] == "MAIN_SF_PREFIX"){
                        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . "AR-" . $froogle_file;
                        $sf_info['prefix'] = "AR-";
                } else {
                        $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"] . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file;
                }
###
##
#


	
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

if ($mode == "ffinish" && empty($cidev_exclude_product_without_img)){
	$cidev_exclude_product_without_img = "N";
}
else{
	$cidev_exclude_product_without_img = "Y";
}

$smarty->assign("cidev_exclude_product_without_img", $cidev_exclude_product_without_img);

$smarty->assign("froogle_file", $store_froogle_filename);
//$smarty->assign("def_froogle_file", ($config['Froogle']['froogle_username'] ? $config['Froogle']['froogle_username'] : "froogle").".txt");
$smarty->assign("def_froogle_file", "froogle.txt");

$smarty->assign("is_ftp_module", $is_ftp_module);

$smarty->assign("main", "froogle_export");

if ($store_froogle_iso)
	$smarty->assign("froogle_iso", $store_froogle_iso);
$smarty->assign("froogle_lng", $store_froogle_lng ? $store_froogle_lng : $shop_language);

# Assign the current location line
$smarty->assign("location", $location);

?>
