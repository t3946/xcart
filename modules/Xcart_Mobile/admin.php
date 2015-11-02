<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart Software license agreement                                           |
| Copyright (c) 2001-2012 Qualiteam software Ltd <info@x-cart.com>            |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT QUALITEAM SOFTWARE LTD   |
| (hereinafter referred to as "THE AUTHOR") OF REPUBLIC OF CYPRUS IS          |
| FURNISHING OR MAKING AVAILABLE TO YOU WITH THIS AGREEMENT (COLLECTIVELY,    |
| THE "SOFTWARE"). PLEASE REVIEW THE FOLLOWING TERMS AND CONDITIONS OF THIS   |
| LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY     |
| INSTALLING, COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND YOUR COMPANY   |
| (COLLECTIVELY, "YOU") ARE ACCEPTING AND AGREEING TO THE TERMS OF THIS       |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT, DO |
| NOT INSTALL OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL  |
| PROPERTY RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT FOR  |
| SALE OR FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY  |
| GRANTED BY THIS AGREEMENT.                                                  |
+-----------------------------------------------------------------------------+
\*****************************************************************************/
/**
 * Module configuration. Admin side drawings
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Modules
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2012 Qualiteam software Ltd <info@x-cart.com>. All rights reserved
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: admin.php 70 2012-11-13 11:37:11Z skot $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */
if (!defined('XCART_START')) {
  header('Location: ../../');
  die('Access denied');
}
if (
        func_mobile_constant('AREA_TYPE') == 'A'
        && strpos($php_url['url'], func_mobile_constant('DIR_ADMIN') . '/configuration.php')
        && isset($_GET['option']) && $_GET['option'] == 'Xcart_Mobile'
) {
  define('IS_MULTILANGUAGE', true);
  /**
   * Header text save
   */
  
  if (!empty($_POST)) {
    if (!empty($_POST['gpg_key']['xcart_mobile_header_text']) && md5(func_query_first_cell("SELECT value FROM $sql_tbl[languages] WHERE name = 'txt_xcart_mobile_homepage_text' AND code = '$shop_language'")) != md5($_POST['gpg_key']['xcart_mobile_header_text'])) {
      func_array2insert('languages', array(
          'code' => $shop_language,
          'name' => 'txt_xcart_mobile_homepage_text',
          'value' => $_POST['gpg_key']['xcart_mobile_header_text'],
          'topic' => 'Text'), true
      );
      unset($_POST['gpg_key']);
      if ($config['General']['use_cached_lng_vars'] == 'Y') {
          func_data_cache_clear('get_language_vars');
      }
      
      $_smarty_compile_dir = $smarty->compile_dir; // remeber compile_dir value
      
      $smarty->compile_dir = $var_dirs['templates_c'] . '/' . md5($_mobile_skin_dir); // set compile_dir for mobile skin
      $smarty->clear_compiled_tpl(); // clear all compiled mobile customer-side templates
      $smarty->compile_dir = $_smarty_compile_dir; // turn back the default compile_dir value
    }
    /*
      Module congiguration save
     */
    if (isset($xcart_mobile_config) && !empty($xcart_mobile_config)) {
      db_query("UPDATE $sql_tbl[config] SET value = '" . addslashes(serialize($xcart_mobile_config)) . "' WHERE name = 'xcart_mobile_admin_configuration'");
    }
    func_header_location('configuration.php?option=Xcart_Mobile');
  }

  /**
   * Getting the module configuration settings
   */
  $xcart_mobile_config = unserialize(stripslashes(func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name = 'xcart_mobile_admin_configuration'")));
  /**
   * Adding content to the configuration page
   */
  $smarty->assign('xcart_dir', $xcart_dir);
  /**
   * Smarty prefilter function
   */
  function func_xcart_mobile_admin_configuration($tpl_source, &$smarty) {
    if ($smarty->_current_file == 'admin/main/configuration.tpl') {
      global $xcart_dir, $xcart_mobile_config, $shop_language, $sql_tbl;
      $xcart_mobile_config['header_text'] = func_query_first_cell("SELECT value FROM $sql_tbl[languages] WHERE name = 'txt_xcart_mobile_homepage_text' AND code = '$shop_language'");
      $smarty->assign('xcart_mobile_config', $xcart_mobile_config);
      $tpl_source = str_replace('{assign var="first_row" value=1}', '{assign var="first_row" value=1} {include file="modules/Xcart_Mobile/admin/configuration.tpl"}', $tpl_source);
    }
    return $tpl_source;
  }
  $smarty->register_prefilter('func_xcart_mobile_admin_configuration');

  /**
   * Orders search postprocessing
   */
} elseif (
        (strpos($_SERVER['PHP_SELF'], 'orders.php') || strpos($_SERVER['PHP_SELF'], 'order.php'))
        && !in_array($mode, array("export", "export_found", "export_all", "export_continue", "xpdf_invoice"))
) {
  x_session_register('search_data');
  if (
          (is_array($posted_data['features']) && in_array('mobile_added', array_values($posted_data['features'])))
          || (!empty($search_data) && is_array($search_data) && is_array($search_data['orders']) && $search_data['orders']['featured']['mobile_added'])
  ) {
    if (!$posted_data['productcode']) {
      $posted_data['productcode'] = '%';
    }
    if (is_array($search_data['orders']) && !$search_data['orders']['productcode']) {
      $search_data['orders']['productcode'] = '%';
    }
  }
  if (
	!empty($search_data) && is_array($search_data) &&
          $_GET['mode'] != 'search'
          && $_POST['mode'] != 'search'
          && is_array($search_data['orders'])
          && $search_data['orders']['productcode'] == '%'
  ) {
    unset($search_data['orders']['productcode']);
    x_session_save('search_data');
  }
  func_mobile_clear_compiled_tpl('main/orders.tpl');
  func_mobile_clear_compiled_tpl('main/orders_list.tpl');
  func_mobile_clear_compiled_tpl('main/order_info.tpl');
  function func_mobile_process_orders($tpl_source, &$smarty) {
    if ($smarty->_current_file == 'main/orders.tpl') {
      $tpl_source = str_replace('<option value="gc_applied"', '<option value="mobile_added"{if $features.mobile_added} selected="selected"{/if}>' . func_get_langvar_by_name('lbl_orders_with_mobile_products', false, false, true) . '</option> <option value="gc_applied"', $tpl_source);
    }
    global $sql_tbl, $search_data;
    x_session_register('search_data');
    $fb_orders = $smarty->get_template_vars('orders');
    if (
	    !empty($search_data) && is_array($search_data) &&
            is_array($search_data['orders'])
            && $search_data['orders']['features']['mobile_added']
            && !empty($fb_orders)
    ) {
      global $search_condition, $config, $xcart_dir, $sort_string, $total_items, $objects_per_page;
      if (strpos($search_condition, 'GROUP BY')) {
        $search_condition = str_replace("GROUP BY", "AND " . $sql_tbl['order_details'] . ".extra_data LIKE '%added_in_mobile%' GROUP BY", $search_condition);
      }
      $_res = db_query("SELECT $sql_tbl[orders].orderid $search_condition");
      $total_items = db_num_rows($_res);
      $page = $search_data['orders']['page'];
      // Prepare the page navigation
      $objects_per_page = $config['Appearance']['orders_per_page_admin'];
      include $xcart_dir . '/include/navigation.php';
      // Get the results for current pages
      if (defined('IS_XCART_44')) {
        $fb_orders = func_query("SELECT $sql_tbl[orders].*, $sql_tbl[customers].id AS existing_userid, $sql_tbl[customers].login, IF($sql_tbl[order_details].extra_data LIKE '%added_in_mobile%', 1, 0) as has_mobile_products $search_condition ORDER BY $sort_string LIMIT $first_page, $objects_per_page");
      } else {
        $fb_orders = func_query("SELECT $sql_tbl[orders].*, IF($sql_tbl[order_details].extra_data LIKE '%added_in_mobile%', 1, 0) as has_mobile_products $search_condition ORDER BY $sort_string LIMIT $first_page, $objects_per_page");
      }
      // Assign the Smarty variables
      $smarty->assign('first_item', $first_page + 1);
      $smarty->assign('last_item', min($first_page + $objects_per_page, $total_items));
      $smarty->assign('total_items', $total_items);
    } elseif (!empty($fb_orders)) {
      foreach ($fb_orders as $k => $v) {
	if (!empty($v["orderid"])){
        $fb_orders[$k]['has_mobile_products'] = func_query_first_cell("SELECT IF(extra_data LIKE '%added_in_mobile%', 1, 0) FROM $sql_tbl[order_details] WHERE orderid = $v[orderid]");
	}
      }
    }
    $smarty->assign('orders', $fb_orders);

    if ($smarty->_current_file == 'main/orders_list.tpl') {
      $tpl_source = str_replace('#{$orders[oid].orderid}', '#{$orders[oid].orderid}{if $orders[oid].has_mobile_products}<img src="{$current_location}/mobile_icon.gif" alt="M" title="' . func_get_langvar_by_name('lbl_has_products_added_in_mobile', false, false, true) . '" style="vertical-align: top; margin-left: 5px;" />{/if}', $tpl_source);
    }
    if ($smarty->_current_file == 'main/order_info.tpl') {
      $products = $smarty->get_template_vars('products');
      if ($products) {
        global $current_location;
        foreach ($products as $k => $v) {
          if ($v['extra_data']['added_in_mobile'] == 1) {
            $products[$k]['product'] .= ' <img src="' . $current_location . '/mobile_icon.gif" alt="F" title="' . func_get_langvar_by_name('lbl_mobile_added_in_mobile', false, false, true) . '" style="vertical-align: top; margin-left: 5px;" />';
          }
        }
        $smarty->assign('products', $products);
      }
    }
    return $tpl_source;
  }
  $smarty->register_prefilter('func_mobile_process_orders');
}
?>
