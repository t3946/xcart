<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: manage_storefronts.php,v 1.0 2010/11/26 13:19:44 kate Exp $
#

if ( !defined('XCART_SESSION_START') ) { header('Location: ../../'); die('Access denied'); }

x_load('image');

$location[] = array(func_get_langvar_by_name('lbl_storefronts_management'), '');

// Remove top banner image
if ($REQUEST_METHOD == 'GET' && $mode == 'delete_icon' && !empty($storefrontid)) {
	
	func_delete_image($storefrontid, 'S');
	
	$top_message['type'] = 'I';
	$top_message['content'] = func_get_langvar_by_name('msg_adm_top_banner_img_del');
	
	switch ($return_to) {

		case 'options':
			$return_url = 'configuration.php?option=Multiple_Storefronts';
			break;

		default:
			$return_url = 'multiple_storefronts.php';
	}

	func_header_location($return_url);
}

if ($REQUEST_METHOD == 'POST') {
	if ($mode == 'add') {

		if (!empty($new_main_domain) && !empty($domain_code)) {
			$new_main_domain = trim($new_main_domain);
			//$new_db_prefix = trim($new_db_prefix);
			if (strlen($new_main_domain < 64) && preg_match('/^([0-9a-z]([0-9a-z\-])*[0-9a-z]\.)+[a-z]{2,6}$/i', $new_main_domain)) {
				$prefixes = func_query_column('SELECT prefix FROM ' . $sql_tbl['storefronts']);
				$domains = func_query_column('SELECT domain FROM ' . $sql_tbl['storefronts']);
				$info_error = 'N';
				/*if (in_array($new_db_prefix, $prefixes) || $new_db_prefix == MAIN_SF_PREFIX) {
					$info_error = 'P';
				}*/

				if (in_array($new_main_domain, $domains)) {
					$info_error = 'D';
				}

				if ($info_error == 'N') {


					if (!empty($file_upload_data['S']) && is_array($file_upload_data['S']))
					{
						$image_perms = func_check_image_storage_perms($file_upload_data, 'S');


						if ($image_perms !== true) {
							$top_message['content'] = $image_perms['content']; 
							$top_message['type'] = 'E';
						}
						else {

							$image_posted = func_check_image_posted($file_upload_data, 'S');

							if ($image_posted) {

							}
							else {
								$top_message['content'] = func_get_langvar_by_name('err_adm_banner_not_posted');
								$top_message['type'] = 'E';
							}
						}
					}


//                    $orderby = func_query_first_cell('SELECT MAX(orderby) FROM ' . $sql_tbl['storefronts']);
//
//                    if (is_numeric($orderby)) {
//                        $orderby += 10;
//                    } else {
//                        $orderby = 10;
//                    }

                    $domain_code = strtoupper($domain_code);

                    $query = array(
                        'domain'	=> $new_main_domain,
                        'code'		=> $domain_code,
                        'prefix'	=> $new_db_prefix ? $new_db_prefix : '',
                        'orderby'	=> 10,
                        'status'    => 'Y', // enable storefront by default
                    );


                    [$model] = \Modules\Sites\Models\SiteModel::objects()->getOrCreate($query);

                    $new_storefront_id = $model->pk;

//                    $new_storefront_id = func_array2insert('storefronts', $query);

                    if (is_numeric($new_storefront_id) && !empty($new_storefront_id)) {
                        $image_id = func_save_image($file_upload_data, 'S', $new_storefront_id);
                    }

                    // Category in the files directory
                    $domain_dir = $xcart_dir . $files_dir . DIRECTORY_SEPARATOR . $new_main_domain;
                    if (!is_dir($domain_dir)) {
                        @mkdir($domain_dir, 0711);
                    }

                    // SF configs
                    if (is_array($domain_specific_config)) {
                        $data = array();
                        $configs_copy = '';
                        foreach ($domain_specific_config as $dsc) {
                            foreach ($dsc as $n => $v) {
                                if (!empty($n)) {
                                    $configs_copy .= '"' . $n . '"' . ', ';
                                }
                            }
                        }
                        $configs_copy = substr($configs_copy, 0, count($configs_copy) - 3);
                        $fields = array(
                            'name',
                            'comment',
                            'value',
                            'category',
                            'orderby',
                            'type',
                            'defvalue',
                            'variants',
                            'validation',
                        );
//									$data = func_query('SELECT ' . implode(', ', $fields) . ' FROM ' . $sql_tbl['config'] . ' WHERE name IN(' . $configs_copy . ')');

                        $sql = <<<SQL
insert ignore into xcart_storefronts_config (storefrontid, name, comment, value, category, orderby , type, `defvalue`, `variants`, `validation`) 
  select {$new_storefront_id}, 
  			c.`name`, 
  			c.`comment`, 
  			c.`value`, 
  			c.`category`, 
  			c.`orderby`, 
  			c.`type`, 
  			c.`defvalue`, 
  			c.`variants`, 
  			c.`validation`
  from `xcart_config` as c
  join (select * from xcart_storefronts_config group by name) as nn on nn.name = c.name and nn.category = c.`category`
SQL;

                        db_query($sql);

                        func_array2update_new('storefronts_config', ['value' => 'Y'],['storefrontid' => $model->pk, 'name' => 'shop_closed']);
                        func_array2update_new('storefronts_config', ['value' => 'N'],['storefrontid' => $model->pk, 'name' => 'https_enabled']);
                        func_array2update_new('storefronts_config', ['value' => 'N'],['storefrontid' => $model->pk, 'name' => 'Enable_CDN']);
                        func_array2update_new('storefronts_config', ['value' => 'N'],['storefrontid' => $model->pk, 'name' => 'search_all_website_show']);
                        func_array2update_new('storefronts_config', ['value' => $domain_code.'-'],['storefrontid' => $model->pk, 'name' => 'opt_order_prefix']);
                        func_array2update_new('storefronts_config', ['value' => 'http://' . $model->domain],['storefrontid' => $model->pk, 'name' => 'company_website']);
                        func_array2update_new('storefronts_config', ['value' => $model->getBaseDomain()],['storefrontid' => $model->pk, 'name' => 'company_name']);
                        func_array2update_new('storefronts_config', ['value' => 'cdn.' . $model->getBaseDomain()],['storefrontid' => $model->pk, 'name' => 'CDN_domain']);
                        func_array2update_new('storefronts_config', ['value' => ''],['storefrontid' => $model->pk, 'name' => 'cidev_main_page_code']);
                        func_array2update_new('storefronts_config', ['value' => ''],['storefrontid' => $model->pk, 'name' => 'html_into_head']);
                        func_array2update_new('storefronts_config', ['value' => ''],['storefrontid' => $model->pk, 'name' => 'cidev_ga_code_number']);
                        func_array2update_new('storefronts_config', ['value' => ''],['storefrontid' => $model->pk, 'name' => 'cidev_yandex_code_number']);
                        func_array2update_new('storefronts_config', ['value' => ''],['storefrontid' => $model->pk, 'name' => 'search_products_unique_id']);
                        func_array2update_new('storefronts_config', ['value' => ''],['storefrontid' => $model->pk, 'name' => 'sf_top_image_alt']);
                        func_array2update_new('storefronts_config', ['value' => "<div id='m_container'><img src='//cdn.{$model->getBaseDomain()}/skin1_kolin/malina/malina2_v.png'></div>"],['storefrontid' => $model->pk, 'name' => 'product_advantages_code']);

//									if (!empty($data)) {
//										foreach ($data as $d) {
//
//											$d['value'] = '';
//
//											if ($d['name'] == 'shop_closed') {
//												$d['value'] = 'Y';
//											}
//											if ($d['name'] == 'opt_order_prefix') {
//												$d['value'] = ''; // default order prefix is empty
//											}
//											$d['storefrontid'] = $new_storefront_id;
//                                            $d['orderby'] = $domain_specific_config[$d['category']][$d['name']];
//											func_array2insert('storefronts_config', $d);
//										}
//									}

                    }


                    $top_message['content'] = func_get_langvar_by_name('msg_adm_storefront_created_success');
                    $top_message['type'] = 'I';
					
				}
				else {
					if ($info_error == 'P') {
						$top_message['content'] = func_get_langvar_by_name('err_mf_prefix_exists', null, false, true);
						$top_message['type'] = 'E';
					}

					if ($info_error == 'D') {
						$top_message['content'] = func_get_langvar_by_name('err_mf_domain_exists', null, false, true);
						$top_message['type'] = 'E';
					}
				}
			}
			else {
				$top_message['content'] = func_get_langvar_by_name('err_mf_wrong_domain_name', null, false, true);
				$top_message['type'] = 'E';
			}
		}
		else {
			$top_message['content'] = func_get_langvar_by_name('err_mf_empty_fields', null, false, true);
			$top_message['type'] = 'E';

		}
	}

	if ($mode == 'modify') {
		if (is_array($sf_links)) {
			db_query("DELETE FROM $sql_tbl[storefront_links]");
        
			foreach ($sf_links as $k => $v) {
				db_query("INSERT INTO $sql_tbl[storefront_links] (storefront1, storefront2) VALUES ('$k','" . implode("'), ('$k','", $v) . "')");
			}
		}
    
		if (is_array($update) && !empty($update)) {
			foreach ($update as $k => $v) {
            	if ($k == 0) {
                	db_query("UPDATE $sql_tbl[config] SET value='" . intval($v['orderby']) . "' WHERE name='default_storefront_orderby'");
                	continue;
                }
            
				if (isset($v['status'])) {
					$shop_closed = ($v['status'] == 'E') ? 'N' : 'Y';
					func_array2update('storefronts_config', array('value' => $shop_closed), 'storefrontid=' . $k . ' AND name="shop_closed"');
				}
				func_array2update('storefronts', $v, 'storefrontid=' . $k);
			}
			$top_message['content'] = func_get_langvar_by_name('msg_adm_mf_update_success', null, false, true);
			$top_message['type'] = 'I';
			
		} else {
			$top_message['content'] = func_get_langvar_by_name('err_mf_update_failed', null, false, true);
			$top_message['type'] = 'E';
		}
	}

	if ($mode == 'delete') {
		if (is_array($delete) && !empty($delete)) {
			$delete_str = implode(', ', array_keys($delete));

			x_load('product', 'category');
			
			// Delete links
			db_query("DELETE FROM $sql_tbl[storefront_links] WHERE storefront1 IN ($delete_str)");
			
			// Delete products
			$productids = func_query_column('SELECT productid FROM ' . $sql_tbl['products_sf'] . ' WHERE sfid IN (' . $delete_str . ')');
			if (is_array($productids)) {
				foreach ($productids as $pid) {
					func_delete_product($pid);
				}
			}
			
			// Delete categories
			$catids = func_query_column('SELECT categoryid FROM ' . $sql_tbl['categories'] . ' WHERE storefrontid IN (' . $delete_str . ')');
			if (is_array($catids)) {
				foreach ($catids as $cid) {
					func_delete_category($cid);
				}
			}

			// Delete speedbar
			if (isset($config['speed_bar'])) {
				$speed_bar = unserialize($config['speed_bar']);
				if (is_array($speed_bar)) {
					foreach ($speed_bar as $k => $v) {
						if (in_array($v['storefrontid'], array_keys($delete))) {
							unset($speed_bar[$k]);
						}
					}
				}
				db_query('REPLACE INTO ' . $sql_tbl['config'] . ' (name,value) VALUES ("speed_bar","' . addslashes(serialize($speed_bar)) . '")');
			}

			// Delete news lists
			if (!empty($active_modules['News_Management'])) {
				$nlids = func_query_column('SELECT listid FROM ' . $sql_tbl['newslists'] . ' WHERE storefrontid IN ("' . $delete_str .'")');
				if (is_array($nlids)) {
					foreach ($nlids as $k => $v) {
						db_query('DELETE FROM ' . $sql_tbl['newslist_subscription'] . ' WHERE listid=' . $v);
						db_query('DELETE FROM ' . $sql_tbl['newslists'] . ' WHERE listid=' . $v);
						db_query('DELETE FROM ' . $sql_tbl['newsletter'] . ' WHERE listid=' . $v);
					}
				}
			}

			// Delete statistic
			db_query('DELETE FROM ' . $sql_tbl['stats_search'] . ' WHERE storefrontid IN ("' . $delete_str . '")');

			// Delete files
			$del_domains = func_query_column('SELECT domain FROM ' . $sql_tbl['storefronts'] . ' WHERE storefrontid IN ("' . $delete_str . '")');
			foreach ($del_domains as $dd) {
				$path = func_allowed_path($xcart_dir . $files_dir, $xcart_dir . $files_dir . DIRECTORY_SEPARATOR . $dd);
				if ($path !== false && is_dir($path)) {
					func_rm_dir($path);
				}
			}

			// Delete sf_configs
			db_query('DELETE FROM ' . $sql_tbl['storefronts_config'] . ' WHERE storefrontid IN ("' . $delete_str . '")');
			
			// Delete images_S
			func_delete_image(array_keys($delete), 'S');

			func_delete_image(array_keys($delete), 'F');

			// Delete featured_products
			db_query('DELETE FROM ' . $sql_tbl['featured_products'] . ' WHERE storefrontid IN ("' . $delete_str . '")');
			
			// Delete XML_Sitemap extra URLs
			if (!empty($active_modules['XML_Sitemap'])) {
				db_query('DELETE FROM ' . $sql_tbl['xmlmap_extra'] . ' WHERE storefrontid IN ("' . $delete_str . '")');
			}

			// Delete discount coupons
			if (!empty($active_modules['Discount_Coupons'])) {
				db_query('DELETE FROM ' . $sql_tbl['discount_coupons'] . ' WHERE storefrontid IN ("' . $delete_str . '")');
			}

			// Delete discounts
			$dids = func_query_column('SELECT discountid FROM ' . $sql_tbl['discounts'] . ' WHERE storefrontid IN ("' . $delete_str .'")');
			
			db_query('DELETE FROM ' . $sql_tbl['discount_memberships'] . ' WHERE discountid IN ("' . implode('", "', $dids) . '")');
			db_query('DELETE FROM ' . $sql_tbl['discounts'] . ' WHERE storefrontid IN ("' . $delete_str . '")');

			// Delete orders. We don't do it.

			// Delete SF
			db_query('DELETE FROM ' . $sql_tbl['storefronts'] . ' WHERE storefrontid IN (' . $delete_str . ')');
			$top_message['content'] = func_get_langvar_by_name('msg_adm_mf_storefronts_deleted', null, false, true);
			$top_message['type'] = 'I';
		} else {
			$top_message['content'] = func_get_langvar_by_name('lbl_no_items_have_been_selected', null, false, true);
			$top_message['type'] = 'E';
		}
	}
	func_header_location('multiple_storefronts.php');
}

if ($storefronts) {
	$qstores = count($storefronts);
	if ($qstores >= MAX_STOREFRONTS) {
		$smarty->assign('max_storefronts', 'Y');
	}
    $avail_licenses = MAX_STOREFRONTS - $qstores;
} else {
    $avail_licenses = MAX_STOREFRONTS;
}
$smarty->assign('avail_licenses', $avail_licenses);

$active_licenses = func_query_first_cell('SELECT COUNT(*) FROM ' . $sql_tbl['storefronts']
    . ' WHERE status = "E"');
$smarty->assign('active_licenses', $active_licenses);

// Get storefronts options
if (!empty($storefronts) && is_array($storefronts)) {

	// Add default storefront
	$sf_default = func_get_storefront_info(0, 'ID', true);
	$_sf_config = array();
	foreach ($sf_default['config'] as $c) {
		foreach ($c as $k => $v) {
			$_sf_config[$k] = $v;
		}
	}
    $sf_default['orderby'] = $config['default_storefront_orderby'];
	$sf_default['config'] = $_sf_config;
	$storefronts[0] = $sf_default;

	$_sf_config = func_query_hash("SELECT name, value, storefrontid FROM $sql_tbl[storefronts_config]", 'storefrontid');
	$_sf_links = func_query_hash("SELECT storefront1, storefront2, 'Y' FROM $sql_tbl[storefront_links]", array('storefront1', 'storefront2'), false, true);

	if (!empty($_sf_config)) {

		foreach ($storefronts as $k => $storefront) {
			
			$storefront['links'] = (!empty($_sf_links[$storefront['storefrontid']])) ? $_sf_links[$storefront['storefrontid']] : array();
                        
			$_sfid = $storefront['storefrontid'];
			
			if (isset($_sf_config[$_sfid]) && is_array($_sf_config[$_sfid])) {

				$storefront['config'] = array();

				foreach ($_sf_config[$_sfid] as $opt) {
					$storefront['config'][$opt['name']] = $opt['value'];
				}
			}
			
			$storefronts[$k] = $storefront;
		}
	}

	usort($storefronts, func_msf_sort_config_array);

	$smarty->assign('storefronts', $storefronts);
}

?>
