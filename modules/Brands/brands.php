<?php /* ADDED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's brands) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# brands.php, random
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice','image');

$location[] = array(func_get_langvar_by_name("lbl_brands"), "");

$provider_condition = ($single_mode || $current_area == "A"?"":"AND provider='$login'");

if ($current_area == 'P') {
    $provider_condition = '';
}

$brandid = intval($brandid);

function func_brand_is_used($brandid, $provider) {
	global $sql_tbl;
	return func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products] WHERE brandid='$brandid' AND provider!='$provider'");
}

if ($REQUEST_METHOD == "POST" || ($mode == "delete_image" && $brandid)) {


	if ($mode == "details" && ($image_perms = func_check_image_storage_perms($file_upload_data, "B")) !== true) {
		# Check permissions
		$top_message = array(
			"content" => $image_perms['content'],
			"type" => "E"
		);

	} elseif ($mode == "details") {
	#
	# Modify brand details
	#

/*
            $clean_url = trim(stripslashes($clean_url));

            $current_clean_url = NULL;

            if (!empty($brandid)) {

                $current_clean_url = func_clean_url_get_raw_resource_url('M', $brandid);

            }

            if (
                $config['SEO']['clean_urls_enabled'] == 'N'
                || !empty($provider_condition)
                || (
                    !empty($brandid)
                    && !zerolen($current_clean_url)
                    && $current_clean_url == $clean_url
                )
            ) {

                $clean_url_check_result = true;

            } else {

                list(
                    $clean_url_check_result,
                    $check_url_error_code
                ) = func_clean_url_validate($clean_url);

            }

            if ($clean_url_check_result == false) {

                $top_message = array(
                    'content'               => func_get_langvar_by_name('err_' . strtolower($check_url_error_code)),
                    'type'                  => 'E',
                    'clean_url_fill_error'  => true
                );

                if (empty($brandid)) {

                    func_header_location("brands.php?mode=add");

                } else {

                    func_header_location("brands.php?brandid=" . $brandid);

                }

            }

*/

		$orderby = intval($orderby);

		if (!empty($brandid)) {
			if (empty($brand)) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_empty");
				$top_message['type'] = 'E';
				func_header_location("brands.php?brandid=".$brandid);

			} elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[brands] WHERE brand = '$brand' AND brandid != '$brandid'")) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_exist");
				$top_message['type'] = 'E';
				func_header_location("brands.php?brandid=".$brandid);
  			}

		#
		# Update the brand details
		#
			if (!empty($provider_condition))
			#
			# Check the permissions to update brand details
			#
				$do_not_touch = (func_brand_is_used($brandid, $login) > 0);
			else
				$do_not_touch = false;

			$query_data = array(
				"url" => $url,
				"link_to_us_url" => $link_to_us_url,
                                "prevent_search_indexing_of_all_brand_products" => $prevent_search_indexing_of_all_brand_products,
				"prevent_search_indexing_brand_page" => $prevent_search_indexing_brand_page,
				"title" => trim($title),
				"SEO_brand_name_h1" => trim($SEO_brand_name_h1),
				"SEO_h2" => trim($SEO_h2),
				"meta_descr" => trim($meta_descr),
				"disclaimer_text" => trim($disclaimer_text),
				"descr" => $descr,
				"customer_service_name" => $customer_service_name,
				"customer_service_phone" => $customer_service_phone,
				"customer_service_email" => $customer_service_email

			);
			$query_data_lng = array(
				"brandid" => $brandid,
				"code" => $shop_language,
				"descr" => $descr
			);
			if (!$do_not_touch) {
				$query_data_lng['brand'] = $brand;
				if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[brands] WHERE brand = '$brand'") == 0)
					$query_data['brand'] = $brand;
			}

			if ($shop_language != $config['default_admin_language']) {
				func_unset($query_data, "brand", "descr");
			}

			if (empty($provider_condition)) {
				$query_data['avail'] = $avail;
				$query_data['orderby'] = $orderby;
			}

			func_array2update("brands", $query_data, "brandid='$brandid' ".$provider_condition);
			func_array2insert("brands_lng", $query_data_lng, true);

			$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_upd");

		}
		else {
		#
		# Add new brand
		#
			if (empty($brand)) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_empty");
				$top_message['type'] = 'E';
				func_header_location("brands.php?mode=add");

			} elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[brands] WHERE brand = '$brand'")) {
				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_exist");
				$top_message['type'] = 'E';
				func_header_location("brands.php?mode=add");

			} else {

				if ($orderby <= 0)
					$orderby = func_query_first_cell("SELECT MAX(orderby) FROM $sql_tbl[brands]") + 10;

				$query_data = array(
					"brand" => $brand,
					"avail" => $avail,
					"prevent_search_indexing_of_all_brand_products" => $prevent_search_indexing_of_all_brand_products,
					"prevent_search_indexing_brand_page" => $prevent_search_indexing_brand_page,
					"orderby" => $orderby,
					"provider" => $login,
					"descr" => $descr,
					"url" => $url,
					"link_to_us_url" => $link_to_us_url,
	                                "title" => trim($title),
        	                        "SEO_brand_name_h1" => trim($SEO_brand_name_h1),
                	                "SEO_h2" => trim($SEO_h2),
                        	        "meta_descr" => trim($meta_descr),
                        	        "disclaimer_text" => trim($disclaimer_text),
	                                "customer_service_name" => $customer_service_name,
	                                "customer_service_phone" => $customer_service_phone,
        	                        "customer_service_email" => $customer_service_email
				);
				$brandid = func_array2insert("brands", $query_data);

				$query_data = array(
					"brandid" => $brandid,
					"code" => $shop_language,
					"brand" => $brand,
					"descr" => $descr
				);
				func_array2insert("brands_lng", $query_data);

				$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_add");
			}
		}


        if (!empty($brandid)) {
                    // Autogenerate clean URL.
                    $clean_url = func_clean_url_autogenerate('M', $brandid, array('brand' => $brand));
                    $clean_url_save_in_history = false;

		    db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$brandid'");
                    func_clean_url_add($clean_url, 'M', $brandid);
        }



		if (func_check_image_posted($file_upload_data, "B") && $brandid > 0) {
			func_save_image($file_upload_data, "B", $brandid);
		}

        if (is_array($_FILES) && isset($_FILES['edit_image'])) {
            $id = $brandid;
            $from_parent_window = 'Y';
            $source = 'L';
            $filename = 'edit_image';
            $userfile = '';
            $type = 'B';

            $userfile = $_FILES[$filename]['name'];
            $userfile_size = $_FILES[$filename]['size'];
            $userfile_type = $_FILES[$filename]['type'];
            
            if (!empty($userfile)) {
                include $xcart_dir . '/include/image_selection.php';
            }
        }

	}
	elseif ($mode == "delete" and !empty($to_delete) && is_array($to_delete)) {
	#
	# Delete selected brands
	#
		$ids = func_query_column("SELECT brandid FROM $sql_tbl[brands] WHERE brandid IN ('".implode("','", array_keys($to_delete))."') ".$provider_condition);
		if (!empty($ids)) {
			db_query("DELETE FROM $sql_tbl[brands] WHERE brandid IN ('".implode("','", $ids)."')");
			db_query("DELETE FROM $sql_tbl[brands_lng] WHERE brandid IN ('".implode("','", $ids)."')");
			db_query("DELETE FROM $sql_tbl[brands_sf] WHERE brandid IN ('".implode("','", $ids)."')");
			db_query("UPDATE $sql_tbl[products] SET brandid = 0 WHERE brandid IN ('".implode("','", $ids)."')");
			func_delete_image($ids, "B");

#		        db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type = 'M' AND resource_id" . $ids);
#		        db_query("DELETE FROM $sql_tbl[clean_urls_history] WHERE resource_type = 'M' AND resource_id" . $ids);

                        db_query("DELETE FROM $sql_tbl[clean_urls] WHERE resource_type = 'M' AND resource_id IN ('".implode("','", $ids)."')");
                        db_query("DELETE FROM $sql_tbl[clean_urls_history] WHERE resource_type = 'M' AND resource_id IN ('".implode("','", $ids)."')");

			$top_message["content"] = func_get_langvar_by_name("msg_adm_brand_del");
		}
	}
	elseif ($mode == "delete_image" && $brandid) {
	#
	# Delete image of selected brand
	#
		func_delete_image($brandid, "B");
	}
	elseif ($mode == "update" and empty($provider_condition)) {
	#
	# Update brands list
	#
		if (is_array($records)) {
			foreach ($records as $k=>$v) {
				$v["avail"] = (empty($v["avail"]) ? "N" : "Y");
				$v["orderby"] = intval($v["orderby"]);
				db_query("UPDATE $sql_tbl[brands] SET avail='$v[avail]', orderby='$v[orderby]' WHERE brandid='$k' $provider_condition");
			}
			$top_message["content"] = func_get_langvar_by_name("msg_adm_brands_upd");
		}
	} elseif (
        $mode == 'clean_urls_history'
        && $brandid
    ) {

        if (
            empty($clean_urls_history)
            || !is_array($clean_urls_history)
        ) {

            $top_message = array(
                'content' => func_get_langvar_by_name('err_clean_urls_history_empty'),
                'type' => 'E'
            );

        } elseif (func_clean_url_history_delete(array_keys($clean_urls_history))) {

            $top_message = array(
                'content' => func_get_langvar_by_name('txt_clean_urls_history_deleted')
            );

        } else {

            $top_message = array(
                'content' => func_get_langvar_by_name('err_clean_urls_history_delete'),
                'type' => 'E'
            );

        }

    }


	$page_str = (!empty($page) ? "&page=$page" : "");

	func_header_location("brands.php?brandid=$brandid" . $page_str . '&word=' . $word);
}



#
# Process the GET request
#

if ($mode == "add" or !empty($brandid)) {
#
# Get the brand data and display brand details page
#
	$location[count($location)-1][1] = "brands.php?word=num";

	if (!empty($brandid)) {
		$brand_data = func_query_first("SELECT $sql_tbl[brands].*, IF($sql_tbl[images_B].id IS NULL, '', 'Y') as is_image, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand, IFNULL($sql_tbl[brands_lng].descr, $sql_tbl[brands].descr) as descr, $sql_tbl[clean_urls].clean_url, $sql_tbl[clean_urls].mtime FROM $sql_tbl[brands] LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands_lng].brandid = $sql_tbl[brands].brandid AND $sql_tbl[brands_lng].code = '$shop_language' LEFT JOIN $sql_tbl[images_B] ON $sql_tbl[images_B].id = $sql_tbl[brands].brandid LEFT JOIN $sql_tbl[clean_urls] ON $sql_tbl[clean_urls].resource_type = 'M' AND $sql_tbl[clean_urls].resource_id = '$brandid' WHERE $sql_tbl[brands].brandid = '$brandid'");

		if (empty($brand_data)) {
			$top_message["content"] = func_get_langvar_by_name("msg_adm_err_brand_not_exists");
			$top_message["type"] = "E";
			func_header_location("brands.php");
		}
		else {
			$brand_data["used_by_others"] = func_brand_is_used($brandid, $brand_data["provider"]);
			$brand_data['customer_url'] = ($HTTPS) ? 'https://' : 'http://';
			if (!empty($active_modules['Multiple_Storefronts'])) {
				if ($current_area == 'C') {
					$brand_data['customer_url'] .= func_get_http_location_sf($current_storefront) . '/brands.php?brandid=' . $brandid;
				} else {
					$sfid = false;
					$sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[brands_sf] WHERE brandid = '$brandid' AND brandid != '0'");
					if (empty($sfid)) {
						$is_exist_default = func_query_first_cell("SELECT COUNT(sfid) FROM $sql_tbl[brands_sf] WHERE brandid = '$brandid' AND brandid = '0'");
						if ($is_exist_default > 0) {
							$sfid = 0;
						}
					}
					if ($sfid !== false) {
						$brand_data['customer_url'] .= func_get_http_location_sf($sfid) . '/brands.php?brandid=' . $brandid;
					} else {
						$brand_data['customer_url'] = '';
					}
				}
			} else {
				$brand_data['customer_url'] .= $xcart_catalogs['customer'] . '/brands.php?brandid=' . $brandid;
			}

		        $brand_data['clean_urls_history'] = func_query_hash("SELECT id, clean_url FROM $sql_tbl[clean_urls_history] WHERE resource_type = 'M' AND resource_id = '$brandid' ORDER BY mtime DESC", "id", false, true);

			$location[] = array($brand_data["brand"], "");
			$smarty->assign("brand", $brand_data);
			$smarty->assign("image", func_image_properties("B", $brandid));
		}
	}
	else
		$location[] = array(func_get_langvar_by_name("lbl_add_brand"), "");

	$smarty->assign("mode", "brand_info");
}
else {
#
# Get and display the brands list
#

	$where = '';
	if (!empty($word)) {
		if (in_array($word, range('a', 'z'))) {
			$where = " WHERE b.brand LIKE '$word%'";
		} elseif ($word == 'num') {
			$where = " WHERE b.brand REGEXP '^[0-9]+.*'";
		}
        
		$smarty->assign('word', $word);
        
		$word = 'word=' . $word;
	}

	if (!empty($active_modules['Multiple_Storefronts']) && $current_area == 'C') {
		if (empty($where)) {
			$where = " WHERE $sql_tbl[brands_sf].sfid = $current_storefront";
	} else {
			$where .= " AND $sql_tbl[brands_sf].sfid = $current_storefront";
		}
		$total_items = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[brands_sf] b $where");
	} else {
		$total_items = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[brands] b $where");
	}

	if ($total_items > 0) {

		#
		# Prepare the page navigation
		#
		$objects_per_page = $config["Brands"]["brands_per_page"];

		$total_nav_pages = ceil($total_items/$objects_per_page)+1;

		include $xcart_dir."/include/navigation.php";

		#
		# Get the brands list
		#
		if (!empty($active_modules['Multiple_Storefronts']) && $current_area == 'C') {
			$brands = func_query("SELECT b.*, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand,"
                . " CONCAT($sql_tbl[customers].lastname,', ',$sql_tbl[customers].firstname) as provider_name,"
				. " IF($sql_tbl[customers].login IS NULL,'','Y') as is_provider"
				. " FROM $sql_tbl[brands] b LEFT JOIN $sql_tbl[customers] ON b.provider=$sql_tbl[customers].login"
                . " LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands_lng].brandid = b.brandid AND $sql_tbl[brands_lng].code = '$shop_language'"
				. " INNER JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid=b.brandid"
				. $where
                . " GROUP BY b.brandid"
                . " ORDER BY b.orderby, b.brand LIMIT $first_page, $objects_per_page");
		} else {
    		$brands = func_query("SELECT b.*, IFNULL($sql_tbl[brands_lng].brand, b.brand) as brand,"
                . " CONCAT($sql_tbl[customers].lastname,', ',$sql_tbl[customers].firstname) as provider_name,"
                . " IF($sql_tbl[customers].login IS NULL,'','Y') as is_provider"
                . " FROM $sql_tbl[brands] b"
                . " LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands_lng].brandid = b.brandid AND $sql_tbl[brands_lng].code = '$shop_language'"
                . " LEFT JOIN $sql_tbl[customers] ON b.provider=$sql_tbl[customers].login"
                . $where
                . " ORDER BY b.orderby, b.brand LIMIT $first_page, $objects_per_page");
		}

		if (is_array($brands)) {
			$products_in_brands = func_query_hash("SELECT COUNT(*), brandid FROM $sql_tbl[products] GROUP BY brandid", 'brandid', false, true);
        
			foreach ($brands as $k=>$v) {
				//$brands[$k]["products_count"] = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products] WHERE brandid='$v[brandid]'");
				if (isset($products_in_brands[$v['brandid']])) {
					$brands[$k]["products_count"] = $products_in_brands[$v['brandid']];
				}
                
				$brands[$k]["used_by_others"] = func_brand_is_used($v["brandid"], $v["provider"]);

#
##
###
                              if (substr($v["provider_name"], 0, 2) == ", "){
                                      $brands[$k]["provider_name"] = substr_replace($v["provider_name"], '', 0, 2);
                              }
###
##
#

			}

			$smarty->assign("navigation_script","brands.php?");
			$smarty->assign("brands", $brands);
			$smarty->assign("first_item", $first_page+1);
			$smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));

		}

	}

	$smarty->assign('words', range('a', 'z'));
    
	$smarty->assign('navigation_script', 'brands.php?' . $word);

	$smarty->assign("total_items",$total_items);

}

if (!empty($page))
	$smarty->assign("page", $page);

?>
