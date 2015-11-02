<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: func.php,v 1.8.2.1 2006/04/14 07:23:23 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../../"); die("Access denied"); }

#
# Build subcategories data cache as JS-code
#
function func_fc_build_categories($cat = false, $tick = 0, $memhersipids = false, $languages = false) {
	global $sql_tbl, $shop_language, $xcart_dir, $all_languages, $current_area, $smarty, $var_dirs, $config, $fcat_module_path;

	$path = $var_dirs["cache"];
	$tpl = $fcat_module_path."/".$config["Fancy_Categories"]["fancy_categories_skin"]."/fancy_subcategories.tpl";

	$where = '';
	if (is_array($cat)) {
		$where = "$sql_tbl[categories].categoryid IN ('".implode("','", $cat)."')";
	}
	elseif (!empty($cat) && (is_string($cat) || is_numeric($cat))) {
		$where = "$sql_tbl[categories].categoryid = '$cat'";
	}

	if (!empty($where))
		$where = " AND ".$where;

	# Get categories list
	$res = db_query("SELECT $sql_tbl[categories].categoryid, $sql_tbl[categories].categoryid_path FROM $sql_tbl[categories], $sql_tbl[categories] as subcat WHERE subcat.parentid = $sql_tbl[categories].categoryid".$where." GROUP BY $sql_tbl[categories].categoryid");

	if (!$res)
		return false;

	$count = db_num_rows($res);
	if (empty($count))
		return false;

	$cat = 0;
	if (!function_exists("func_get_categories_list")) {
		include $xcart_dir."/include/categories.php";
	}

	if (!function_exists("func_get_categories_list")) {
		return false;
	}

	# Get memberships list
	if (!is_array($membershipids)) {
		$tmp = func_get_memberships("C");
		$memhersipids = array(0);
		if (!empty($tmp)) {
			foreach ($tmp as $mid) {
				$memhersipids[] = $mid['membershipid'];
			}
		}
		unset($tmp);
		
	}

	# Get languages list
	if (!is_array($languages)) {
		$languages = array();
		foreach ($all_languages as $l)
			$languages[] = $l['code'];
	}

	if (count($memhersipids) == 0 || count($languages) == 0)
		return false;

	# Display service header
	func_display_service_header(func_get_langvar_by_name("lbl_rebuilding_subcategory_cache", array("count" => $count, "mcount" => count($memhersipids), "lcount" => count($languages)), false, true), true);

	$shop_language_old = $shop_language;
	$user_account_old = $user_account;
	$current_area_old = $current_area;
	$current_area = "C";

	# Disable 'fancy_cache' option
	$old_fancy_cache = func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name = 'fancy_cache'");
	if ($old_fancy_cache == 'Y')
		func_array2update("config", array("value" => "N"), "name = 'fancy_cache'");

	$i = 0;
	while ($row = db_fetch_array($res)) {
		foreach ($languages as $shop_language) {
			foreach ($memhersipids as $mid) {
				$user_account['membershipid'] = $mid;
				$cats = func_get_categories_list($row['categoryid'], true, 'level');
				if (empty($cats['all_categories']))
					break;

				$fp = @fopen($path."/fc.".$config["Fancy_Categories"]["fancy_categories_skin"].".".$user_account['membershipid'].".".$shop_language.".".$row['categoryid'].".js", "w");
				if (!$fp)
					break;

				fwrite($fp, fc_categories2js($cats['all_categories'], $row['categoryid'], $tpl));
				fclose($fp);

				$i++;
				if ($tick > 0 && $i % $tick == 0) {
					func_flush(". ");
				}
			}
		}
	}

	db_free_result($res);

	# Enable 'fancy_cache' option
	if ($old_fancy_cache == 'Y')
		func_array2update("config", array("value" => "Y"), "name = 'fancy_cache'");

	$shop_language = $shop_language_old;
	$user_account = $user_account_old;
	$current_area = $current_area_old;

	return true;
}

#
# Check - rebuild skin cache or not
#
function func_fc_check_rebuild($id = false, $type = 'C', $old_data = array()) {
	global $sql_tbl, $config;

	$cache_options = array("fancy_js", "fancy_download", "fancy_cache");

	# Check enabled configuration variable
	$enable = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[config] WHERE name IN ('".implode("','", $cache_options)."') AND value = 'Y'");
	if ($enable != count($cache_options))
		return false;

	if ($type == 'P' && $config['Appearance']['count_products'] != 'Y')
		return false;

	if ($id === false)
		return true;

	$cats = array();
	switch ($type) {
		case "P":

			# Get affected categories based productid
			$where = is_array($id) ? "IN ('".implode("','", $id)."')" : "= '$id'";
			$paths = func_query_column("SELECT $sql_tbl[categories].categoryid_path FROM $sql_tbl[products_categories], $sql_tbl[categories] WHERE $sql_tbl[products_categories].categoryid = $sql_tbl[categories].categoryid AND $sql_tbl[products_categories].productid $where GROUP BY $sql_tbl[categories].categoryid");
			if (!empty($paths)) {
				foreach ($paths as $path) {
					$cats = array_merge($cats, explode("/", $path));
				}
			}

			break;

		case "C":
			
			# Get affected categories based categoryid
			$categories = func_fc_save_category_data($id);
			if (empty($categories))
				return true;

			foreach ($categories as $cid => $c) {
				if (in_array($cid, $cats))
					continue;

				$data = $old_data[$cid];

				if (!empty($c['parentid']) && (empty($data) || $c['order_by'] != $data['order_by'] || $c['category'] != $data['category'])) {
					$cats[] = $c['parentid'];
				}

				# Check additional category options
				$diff = false;
				if (!empty($data)) {

					# Check category memberhsip levels
					if (count($data['memberships']) != count($c['memberships'])) {
						$diff = true;
					} else {

						# Check changes in category membership levels
						$int = array_intersect(array_keys($data['memberships']), array_keys($c['memberships']));
						if (count($int) != count($data['memberships']))
							$diff = true;
					}

					if (!$diff) {

						# Check category international descriptions
						if (count($data['languages']) != count($c['languages'])) {
							$diff = true;
						} else {

							# Check changes in category international descriptions
							$int = array_intersect(array_keys($data['languages']), array_keys($c['languages']));
							if (count($int) != count($data['languages'])) {
								$diff = true;
							} else {

								# Compare category international descriptions
								foreach ($data['languages'] as $code => $l) {
									if ($l['category'] != $c['languages'][$code]['category'] || $l['description'] != $c['languages'][$code]['description']) {
										$diff = true;
										break;
									}
								}
							}
						}
					}
				}

				if (empty($data) || $diff || $c['avail'] != $data['avail'] || $c['categoryid_path'] != $data['categoryid_path']) {

					# Get category parents and childs
					if ($config['Appearance']['count_products'] == 'Y') {
						$path = explode("/", $c['categoryid_path']);
						if (count($path) > 1 || $c['avail'] == 'Y')
							$cats = array_merge($cats, $path);

					} elseif ($c['avail'] == 'Y') {
						$cats[] = $cid;
						if ($c['parentid'] != 0)
							$cats[] = $c['parentid'];
					}

					if (($c['avail'] != $data['avail'] && $c['avail'] == 'Y') || ($c['categoryid_path'] != $data['categoryid_path'])) {
						$childs = func_fc_get_category_childs($cid);
						if (!empty($childs))
							$cats = array_merge($cats, $childs);
					}
				}
			}
	}

	if (empty($cats))
		return false;

	return array_unique($cats);
}

#
# Save categories data in specific format
#
function func_fc_save_category_data($cat) {
	global $sql_tbl;

	$where = is_array($cat) ? "IN ('".implode("','", $cat)."')" : "= '$cat'";
	$data = func_query_hash("SELECT * FROM $sql_tbl[categories] WHERE categoryid $where", "categoryid", false);
	if (empty($data))
		return array();

	# Get category memberships
	foreach ($data as $cid => $v) {
		$data[$cid]['memberships'] = func_query_hash("SELECT * FROM $sql_tbl[category_memberships] WHERE categoryid = '$cid'", "membershipid", false);
		if (empty($data[$cid]['memberships']))
			$data[$cid]['memberships'] = array();
		$data[$cid]['languages'] = func_query_hash("SELECT * FROM $sql_tbl[categories_lng] WHERE categoryid = '$cid'", "code", false);
		if (empty($data[$cid]['languages']))
			$data[$cid]['languages'] = array();
	}

	return $data;
}

#
# Get enabled subcategories of selected category
#
function func_fc_get_category_childs($cat) {
	global $sql_tbl;

	$childs = func_query_column("SELECT categoryid FROM $sql_tbl[categories] WHERE parentid = '$cat' AND avail = 'Y'");
	if (empty($childs))
		return false;

	$subchilds = array();
	foreach ($childs as $c) {
		$res = func_fc_get_category_childs($c);
		if (!empty($res))
			$subchilds = array_merge($subchilds, $res);
	}
	return array_merge($childs, $subchilds);
}


#
# Remove subcategories cache
#
function func_fc_remove_cache($tick = 0, $categories = false, $membershipids = false, $languages = false) {
	global $xcart_dir, $var_dirs;

	$path = $var_dirs["cache"];
	$dir = @opendir($path);
	if ($dir) {
		func_display_service_header("lbl_deleting_subcategory_cache");
		$i = 0;
		while ($file = readdir($dir)) {
			if ($file == '.' || $file == '..' || !preg_match("/^fc\.([^\.]+)\.(\d+)\.([\w]{2})\.(\d+)\.js$/S", $file, $match))
				continue;

			if (!empty($categories) && !in_array($match[4], $categories))
				continue;
			if (!empty($membershipids) && !in_array($match[2], $membershipids))
				continue;
			if (!empty($languages) && !in_array($match[3], $languages))
				continue;

			@unlink($path."/".$file);
			$i++;
			if ($tick > 0 && $i % $tick == 0) {
				func_flush(". ");
			}
		}
		closedir($dir);
		return true;
	}

	return false;
}

#
# Build subcategory data as JS-code
#
function fc_categories2js($categories, $cat, $tpl) {
	global $smarty;

	if (empty($categories) || !is_array($categories) || empty($tpl))
		return false;

	$tmp = current($categories);
	$level = substr_count($tmp['categoryid_path'], "/");
	$smarty->assign("level", $level);
	reset($categories);

	$smarty->assign("categories", $categories);
	$content = func_display($tpl, $smarty, false);
	$content = str_replace(array("\n","\r","'"), array("","","\'"), $content);
	$content = preg_replace("/>\s+</s", "><", $content);
	$content = "span_content[$cat] = '".$content."';\n";
	$content .= "categories[$cat] = [];\n";
	foreach ($categories as $catid => $c) {
		$content .= "categories[$cat][$catid] = ".(($c['subcategory_count'] > 0) ? "true" : "false").";\n";
	}

	return $content;
}

function func_mark_last_categories(&$categories, $columns = array()) {
	end($categories);
	$last = key($categories);
	reset($categories);
	$categories[$last]['last'] = true;

	foreach ($categories as $k => $v) {
		if (!empty($v['childs']) && is_array($v['childs'])) {
			$c = $columns;
			$c[] = !$v['last'];
			func_mark_last_categories($categories[$k]['childs'], $c);
		}

		$categories[$k]['columns'] = $columns;
	}
}
?>
