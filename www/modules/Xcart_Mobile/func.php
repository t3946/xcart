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
 * Functions for the mobile skin module
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Modules
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2012 Qualiteam software Ltd <info@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: func.php 63 2012-10-30 11:56:13Z skot $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */
if (!defined('XCART_START')) {
  header("Location: ../../");
  die("Access denied");
}
if (!defined('MOBILE_FUNCS_LOADED')) {

  /**
   * Wrapper for the "strpos" function to make it working with array of needles
   */
  function func_mobile_strpos_array($haystack, $needle) {
    if (empty($needle))
      return false;
    if (!is_array($needle))
      $needle = array($needle);
    foreach ($needle as $what) {
      if (($pos = strpos($haystack, $what)) !== false)
        return $pos;
    }
    return false;
  }
  /**
   * Wrapper for php constant function
   */
  function func_mobile_constant($constant) {
    if (defined($constant))
      return constant($constant);
    else
      return false;
  }
  /**
   * Smarty function:
   * Disable the unnecessary modules
   */
  function func_mobile_clear_modules() {
    global $smarty, $active_modules;
    // See the "Modules to disable" list in the modules/Xcart_Mobile/config.php file
    global $modules_to_disable;
    foreach ($modules_to_disable as $_module_name) {
      unset($active_modules[$_module_name]);
    }
    
    // Workaround for the Fast Lane Checkout
    $active_modules['Fast_Lane_Checkout'] = 'Y';
    $smarty->assign('active_modules', $active_modules);
    
    // Workaround for the Feature_Comparison module and some products-list functions
    if (($smarty->get_template_vars('free_products')) == '') {
      $smarty->assign('free_products', true);
      if (!empty($active_modules['Feature_Comparison'])) {
        $smarty->assign('products_has_fclasses', true);
      }
    }
    return false;
  }
  /**
   * Smarty function:
   * Setting up the active navigation tab
   */
  function func_mobile_set_active_tab($params) {
    if (empty($params['mode']))
      return false;
    global $smarty, $mobile_mode, $cat, $php_url;
    $current_mode = $smarty->get_template_vars('main');
    $_active = false;
    switch ($params['mode']) {
      case 'home':
        if ($current_mode == 'catalog' && $cat == 0 && empty($mobile_mode)) {
          $_active = true;
        }
        break;
      case 'catalog':
        if (
                $mobile_mode == 'subcategories'
                || $cat > 0
                || in_array($current_mode, array(
                    'product',
                    'manufacturers_list',
                    'manufacturer_products',
                    'product_configurator'
                        )
                )
        ) {
          $_active = true;
        }
        break;
      case 'search':
        if (in_array($current_mode, array('search', 'advanced_search'))) {
          $_active = true;
        }
        break;
      case 'cart':
        if (
                $current_mode == 'cart'
                || strpos($php_url['url'], 'cart.php')
        ) {
          $_active = true;
        }
        break;
      case 'more':
        if (
                $mobile_mode == 'more'
                || !in_array($current_mode, array(
                    'catalog',
                    'product',
                    'manufacturers_list',
                    'manufacturer_products',
                    'wishlist',
                    'product_configurator',
                    'cart',
                    'search',
                    'advanced_search',
                ))
                && !strpos($php_url['url'], 'cart.php')
        ) {
          $_active = true;
        }
        break;
    }
    $add_class = '';
    if ($_active === true) {
      $add_class = 'ui-btn-active ui-state-persist';
      if ($params['assign']) {
        $smarty->assign($params['assign'], $add_class);
        $add_class = '';
      }
    }
    return $add_class;
  }
  /**
   * Smarty function:
   * Getting the page title
   */
  function func_mobile_get_page_title($params) {
    global $smarty, $location, $config, $mobile_mode, $mode;
    $current_mode = $smarty->get_template_vars('main');
    $_lng_vars = $smarty->get_template_vars('lng');
    $page_title = $smarty->get_template_vars('page_title');
    if ($current_mode == 'product') {
      $location = array_pop($location);
      $page_title = array_shift($location);
    }
    if ($mobile_mode == 'search') {
      $page_title = strip_tags(func_get_langvar_by_name('lbl_search', '', false, true));
    }
    if ($current_mode == 'advanced_search' || $mode == 'advanced_search') {
      $page_title = strip_tags(func_get_langvar_by_name('lbl_advanced_search', '', false, true));
    }
    if ($mobile_mode == 'more' && count($location) == 1) {
      $page_title = strip_tags(func_get_langvar_by_name('lbl_title_more', '', false, true));
    }
    if (empty($page_title) && is_array($location) && count($location) > 1) {
      $location = array_pop($location);
      $page_title = array_shift($location);
    }
    if (empty($page_title)) {
      $page_title = strip_tags(func_get_langvar_by_name('lbl_site_title', '', false, true));
    }
    if (empty($page_title)) {
      $page_title = $config['Company']['company_name'];
    }
    if ($params['assign']) {
      $smarty->assign($params['assign'], $page_title);
      $page_title = '';
    }
    return $page_title;
  }
  /**
   * Smarty function "Clear compiled template" surrogate:
   * Added here because the latest versions af X-Cart entirely
   * removes the generated templates by the "clear_compiled_tpl" function.
   * The accidently removed templates may cause the Smarty-errors
   */
  function func_mobile_clear_compiled_tpl($tpl_src) {
    global $smarty;
    $_params = array(
        'auto_base' => $smarty->compile_dir,
        'auto_source' => $tpl_src,
        'auto_id' => $smarty->compile_id,
        'exp_time' => null,
        'extensions' => array('.inc', '.php', '.md5')
    );
    if (!function_exists('smarty_core_rm_auto')) {
      require_once(SMARTY_CORE_DIR . 'core.rm_auto.php');
    }
    return smarty_core_rm_auto($_params, $smarty);
  }
  /**
   * Smarty outputfilter function:
   * Converts all tables to lists for the proper mobile view
   */
  function func_register_form_convert($tpl_source, &$smarty) {
    $needles = array();
    $replaces = array();
    $needles = array(
        'name="registerform"'
    );
    $replaces = array(
        'name="registerform" data-ajax="false"'
    );
    $tpl_source = str_replace($needles, $replaces, $tpl_source);
    return $tpl_source;
  }
  /**
   * Smarty outputfilter function:
   * Manufacturers list navigation prepare
   */
  function func_mobile_manufacturers_navigation($tpl_source, &$smarty) {
    global $config, $total_items;
    if (
            $total_items > $config['Manufacturers']['manufacturers_per_page']
    ) {
      global $first_page, $manufacturers;
      $pattern = '/<div class="nav-pages"[^>]*>(.*?)<\/div>/is';
      $content = '<div class="nav-pages">' . ($first_page + 1) . ' - ' . min($first_page + count($manufacturers), $total_items) . '&nbsp;' . func_get_langvar_by_name('lbl_of') . '&nbsp;' . $total_items . '</div>';
      $tpl_source = preg_replace($pattern, $content, $tpl_source);
    }
    return $tpl_source;
  }
  /**
   * Smarty postfilter function:
   * Prepare templates for the proper functioning within the mobile framework
   */
  function func_mobile_templates_prepare($tpl_source, &$smarty) {
    global $active_modules;
    $needles = array();
    $replaces = array();
    $_smarty_curr_file = $smarty->_current_file;
    /**
     * Express checkout buttons
     */
    // PayPal
    if ($_smarty_curr_file == 'payments/ps_paypal_pro_express_checkout.tpl') {
      $needles = array('<form');
      $replaces = array('<form data-ajax="false" data-role="none" data-enhance="false"');
    }
    // Bongo International
    if ($_smarty_curr_file == 'modules/Bongo_International/checkout_button.tpl') {
      $needles = array('href="cart.php?mode=bongo_checkout"');
      $replaces = array('href="cart.php?mode=bongo_checkout" rel="external"');
    }
    // Amazon
    if ($_smarty_curr_file == 'modules/Amazon_Checkout/checkout_btn.tpl') {
      $needles = array('href="cart.php?mode=acheckout"');
      $replaces = array('href="cart.php?mode=acheckout" rel="external"');
    }
    // Google
    $_gcheckout_button_code = $smarty->_tpl_vars['gcheckout_button'];
    //$_gcheckout_button_code = $smarty->get_template_vars('gcheckout_button'); TODO: check this method
    if ($_smarty_curr_file == 'modules/Google_Checkout/gcheckout_button.tpl' && !empty($_gcheckout_button_code)) {
      $needles = array(
          '<form',
          '<button class="gcheckout-button" type="submit"><img src=',
          'alt="" /></button>'
      );
      $replaces = array(
          '<form data-ajax="false" data-role="none" data-enhance="false"',
          '<input class="gcheckout-button" type="image" src=',
          ' />'
      );
      $smarty->_tpl_vars['gcheckout_button'] = str_replace($needles, $replaces, $_gcheckout_button_code);
    }
    if (!empty($needles) && !empty($replaces)) {
      $tpl_source = str_replace($needles, $replaces, $tpl_source);
    }
    return $tpl_source;
  }
  /**
   * Smarty function:
   * Prepare sort fields
   */
  function func_mobile_prepare_sort_fields($params) {
    if (is_array($params['fields'])) {
      global $smarty, $page;
      $nav_script = parse_url($smarty->get_template_vars('navigation_script'));
      parse_str($nav_script['query'], $nav_script['query']);
      $_lh = func_get_langvar_by_name('lbl_mobile_low_high', false, false, true);
      $_hl = func_get_langvar_by_name('lbl_mobile_high_low', false, false, true);
      $_az = func_get_langvar_by_name('lbl_mobile_a_z', false, false, true);
      $_za = func_get_langvar_by_name('lbl_mobile_z_a', false, false, true);
      foreach (array_reverse($params['fields']) as $k => $v) {
        $nav_script['query']['sort'] = $k;
        $_d = 0;
        while ($_d <= 1) {
          $nav_script['query']['sort_direction'] = $_d;
          if ($k == 'orderby') {
            $_name = $v;
          } elseif ($k == 'price') {
            $_name = ($_d == 0) ? ($v . ' ' . $_lh) : ($v . ' ' . $_hl);
          } else {
            $_name = ($_d == 0) ? ($v . ' ' . $_az) : ($v . ' ' . $_za);
          }
          if (!empty($page) && empty($nav_script['query']['page'])) {
            $nav_script['query']['page'] = $page;
          }
          $_sort_fields[$nav_script['path'] . '?' . http_build_query($nav_script['query'])] = $_name;
          if ($k == 'orderby')
            break;
          $_d++;
        }
      }
      if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $_sort_fields);
        $_sort_fields = '';
      }
      return $_sort_fields;
    }
    return false;
  }
  /**
   * Smarty function:
   * Arrange perpage values array
   */
  function func_mobile_arrange_perpage_values($_per_page) {
    if (!is_array($_per_page) && empty($_per_page))
      return false;
    global $smarty;
    $_per_page[] = $smarty->get_template_vars('objects_per_page');
    $_per_page = array_unique($_per_page);
    sort($_per_page);
    return $_per_page;
  }
  /**
   * Smarty function:
   * Check if variants has wholesale
   */
  function func_mobile_variants_has_wl($vars) {
    if (!is_array($vars) && empty($vars))
      return false;
    foreach ($vars as $v) {
      if (!empty($v['wholesale'])) {
        return true;
      }
    }
    return false;
  }
  
  /**
   * Smarty function:
   * Return $total_items for navigation
   */
  function func_mobile_get_total_items () {
      global $total_items;
      return $total_items;
  }
}
?>
