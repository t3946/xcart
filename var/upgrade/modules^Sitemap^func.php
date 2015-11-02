<?php
/* vim: set ts=4 sw=4 sts=4 et: */
/*****************************************************************************\
 +-----------------------------------------------------------------------------+
 | X-Cart                                                                      |
 | Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>                  |
 | All rights reserved.                                                        |
 * -----------------------------------------------------------------------------+
 | PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
 | FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
 | AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
 |                                                                             |
 | THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
 | THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
 | FAZLYEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
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
 | The Initial Developer of the Original Code is Ruslan R. Fazlyev             |
 | Portions created by Ruslan R. Fazlyev are Copyright (C) 2001-2010           |
 | Ruslan R. Fazlyev. All Rights Reserved.                                     |
 * -----------------------------------------------------------------------------+
\**************************************************************************** */

/**
 * Module functions
 *
 * @copyright   Copyright (c) 2001-2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license     http://www.x-cart.com/software_license_agreement.html X-Cart license agreement
 * @author      Slam <slam@x-cart.com>
 * @category    X-Cart
 * @package     Modules
 * @subpackage  Sitemap
 * @version     $Id$
 * @since       4.4.0
 */
if (!defined('XCART_START')) { header('Location: ../../'); die('Access denied');}

/**
 * Collect all items for the sitemap
 *
 * @return array
 */
function sitemap_get_items()
{
    global $config;
    $items = array();
    if (is_array($config['Sitemap']['items'])) {
        foreach ($config['Sitemap']['items'] as $item) {
            $function_name = 'sitemap_get_' . $item;
            if (function_exists($function_name)) {
                $items[$item] = $function_name();
            }
        }
    }
    return $items;
}

/**
 * Collect categories
 *
 * @return array
 */
function sitemap_get_categories()
{
    global $config;
    if ($config['Sitemap']['sitemap_display_categories'] == 'Y') {
        $items = sitemap_get_categories_recurs(0);
        return sitemap_define_urls($items, 'C');
    } else {
        return false;
    }
}

/**
 * Collect products
 *
 * @return array
 */
function sitemap_get_products()
{
    global $config;
    if ($config['Sitemap']['sitemap_display_products'] == 'Y') {
        $query = sitemap_build_products_query(false);
        $items = func_query($query);
        return sitemap_define_urls($items, 'P');
    } else {
        return false;
    }
}

/**
 * Collect manufacturers
 *
 * @return array
 */
function sitemap_get_manufacturers()
{
    global $sql_tbl, $config, $active_modules, $current_storefront;
    if ($config['Sitemap']['sitemap_display_manufacturers'] == 'Y' && isset($active_modules['Manufacturers'])) {
        if ($config['Sitemap']['sitemap_multilang'] == 'Y') {
            $code = $GLOBALS['shop_language'];
            $multilang_select = "IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer)";
            $multilang_join = "LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers].manufacturerid = $sql_tbl[manufacturers_lng].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$code'";
        } else {
            $multilang_select = "$sql_tbl[manufacturers].manufacturer";
            $multilang_join = '';
        }
        if ($config['Sitemap']['sitemap_manufacturers_order'] == 'ASC') {
            $order_by = 'name';
        } else {
            $order_by = "$sql_tbl[manufacturers].orderby, name";
        }
        $query = "SELECT $sql_tbl[manufacturers].manufacturerid as id, $multilang_select as name FROM $sql_tbl[manufacturers] $multilang_join WHERE avail='Y' ORDER BY $order_by";
        $items = func_query($query);
        return sitemap_define_urls($items, 'M');
    } else {
        return false;
    }
}

/**
 * Collect static pages
 *
 * @return array
 */
function sitemap_get_pages()
{
    global $sql_tbl, $config;

    if ($config['Sitemap']['sitemap_display_pages'] == 'Y') {
        if ($config['Sitemap']['sitemap_multilang'] == 'Y') {
            $code = $GLOBALS['shop_language'];
            $multilang_where = "AND language='$code'";
        }

        if ($config['Sitemap']['sitemap_pages_order'] == 'ASC') {
            $order_by = 'name';
        } else {
            $order_by = "$sql_tbl[pages].orderby, name";
        }

        $query = "SELECT $sql_tbl[pages].pageid as id, $sql_tbl[pages].title as name FROM $sql_tbl[pages] WHERE $sql_tbl[pages].active = 'Y' AND $sql_tbl[pages].level='E' $multilang_where ORDER BY $order_by";

        $items = func_query($query);
        return sitemap_define_urls($items, 'S');
    } else {
        return false;
    }
}

/**
 * Collect extra urls
 *
 * @return array
 */
function sitemap_get_extra()
{
    global $sql_tbl, $config;

    if ($config['Sitemap']['sitemap_display_extra'] == 'Y') {
        if ($config['Sitemap']['sitemap_extra_order'] == 'ASC') {
            $order_by = 'name';
        } else {
            $order_by = "$sql_tbl[sitemap_extra].orderby, name";
        }

        $query = "SELECT $sql_tbl[sitemap_extra].name as name, $sql_tbl[sitemap_extra].url as url, $sql_tbl[sitemap_extra].id as id FROM $sql_tbl[sitemap_extra] WHERE $sql_tbl[sitemap_extra].active = 'Y' ORDER BY $order_by";
        $items = func_query($query);
        return $items;
    } else {
        return false;
    }
}

/**
 * Creates URL using avaliable processor. Currently avaliable:
 * - Clean URLs
 * - X-SEO: Friendly URLs
 * - default php ones
 *
 * @param  string $type   C|P|M|S|H
 * @param  int    $id     item id
 * @param  array  $params additional params. now only params[url] is used. if it passed, exactly this url will be returned
 * @return string
 */
function sitemap_get_url($type, $id, $params = array())
{
    global $config, $active_modules, $xseo;

    $id = intval($id);

    $url = '';

    if (isset($params['url'])) {
        $url = $params['url'];
    } else if (isset($config['SEO']['clean_urls_enabled'])) {
        $url = func_get_resource_url($type, $id, '', false);
    } else if (isset($active_modules['XSEO']) && ( isset($xseo['modules']['urls']) && $xseo['modules']['urls']['active'] != false)) {
        global $shop_language;
        $url = xseo_urls_get_url($id, $type, $shop_language, true);
    } else {
        switch ($type) {
            case 'C':
                $url = 'home.php?cat=' . $id;
                break;
            case 'P':
                $url = 'product.php?productid=' . $id;
                break;
            case 'M':
                $url = 'manufacturers.php?manufacturerid=' . $id;
                break;
            case 'S':
                $url = 'pages.php?pageid=' . $id;
                break;
case 'B':
$url = 'brands.php?brandid=' . $id;
break;
            case 'H':
                $url = 'home.php';
                break;
            default:
                $url = '';
                break;
        }
    }

    if (empty($url)) {
        $url = 'home.php';
    }

    return $url;
}


/**
 * Collect brands
 *
 * @return array
 */
function sitemap_get_brands()
{
    global $sql_tbl, $config, $active_modules, $current_storefront;
    if (!empty($active_modules['Multiple_Storefronts'])) {
        $sf_join = " LEFT JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid = $sql_tbl[brands].brandid";
        $sf_condition = " AND $sql_tbl[brands_sf].sfid = $current_storefront";
    } else {
        $sf_join = '';
        $sf_condition = '';
    }
    if ($config['Sitemap']['sitemap_display_brands'] == 'Y') {
    $multilang_select = "$sql_tbl[brands].brand";
    $order_by = "$sql_tbl[brands].orderby, name";
        $query = "SELECT $sql_tbl[brands].brandid as id, $multilang_select as name FROM $sql_tbl[brands] $sf_join WHERE avail='Y' $sf_condition ORDER BY $order_by";
    $items = func_query($query);
    return sitemap_define_urls($items, 'B');
    }
}


/**
 * Assing URLs to all items in the passed array
 *
 * @param  array $items
 * @param  string $type C|P|M|S|H
 * @return array
 */
function sitemap_define_urls($items, $type)
{
    if (is_array($items)) {
        array_walk($items, 'sitemap_define_urls_callback', $type);
    }
    return $items;
}

/**
 * Callback function. Adds url value to the passed item
 *
 * @param arrray $item
 * @param int    $key
 * @param string $type C|P|M|S|H
 */
function sitemap_define_urls_callback(&$item, $key, $type)
{
    if (isset($item['id'])) {
        $item['url'] = sitemap_get_url($type, $item['id']);
    }
}

/**
 * Recursevly build categories chain
 *
 * @param  int   $parentid
 * @return array
 */
function sitemap_get_categories_recurs($parentid)
{
    global $sql_tbl, $config;

    static $level = 0;
    $level++;

    $query = sprintf(sitemap_build_categories_query(), $parentid);
    $result = db_query($query);

    if (db_num_rows($result) > 0) {
        while ($row = db_fetch_array($result)) {
            $row['subs'] = call_user_func(__FUNCTION__, $row['id']);
            $row['subs'] = sitemap_define_urls($row['subs'], 'C');
            $row['products'] = sitemap_get_products_categories($row['id']);
            $items[] = $row;
            $level--;
        }
        return $items;
    } else {
        return false;
    }
}

/**
 * Get all products assigned for the specified category
 *
 * @param  int   $categoryid
 * @return array
 */
function sitemap_get_products_categories($categoryid)
{
    global $config;
    if ($config['Sitemap']['sitemap_display_products_categor'] == 'Y') {
        $categoryid = intval($categoryid);
        $query = sprintf(sitemap_build_products_query(), $categoryid);
        $items = func_query($query);
        return sitemap_define_urls($items, 'P');
    }
}

/**
 * Build db query for categories
 *
 * @return string
 */
function sitemap_build_categories_query()
{
    static $query = '';

    if (empty($query)) {
        global $sql_tbl, $config, $active_modules, $current_storefront;

        $select = $join = $from = $where = $order_by = array();

        // Category id
        $select[] = "$sql_tbl[categories].categoryid AS id";

        $select[] = "$sql_tbl[categories].parentid";

        // Category name
        if ($config['Sitemap']['sitemap_multilang'] == 'Y') {
            $code = $GLOBALS['shop_language'];
            $select[] = "IF ($sql_tbl[categories_lng].categoryid IS NOT NULL AND $sql_tbl[categories_lng].category != '', $sql_tbl[categories_lng].category, $sql_tbl[categories].category) AS name";
            $join[] = "$sql_tbl[categories_lng] USE INDEX (PRIMARY) ON $sql_tbl[categories_lng].code = '$code' AND $sql_tbl[categories_lng].categoryid = $sql_tbl[categories].categoryid";
        } else {
            $select[] = "$sql_tbl[categories].category AS name";
        }

        $from[] = "$sql_tbl[categories]";

        // Membership condition
        if ($config['Sitemap']['sitemap_membership'] == 'Y') {
            global $user_account;
            $join[] = "$sql_tbl[category_memberships] ON $sql_tbl[category_memberships].categoryid = $sql_tbl[categories].categoryid";
            $where[] = "($sql_tbl[category_memberships].membershipid IS NULL OR $sql_tbl[category_memberships].membershipid = '$user_account[membershipid]')";
        }

        // Storefronts condition
        if (!empty($active_modules['Multiple_Storefronts'])) {
            $where[] = "$sql_tbl[categories].storefrontid=$current_storefront";
        }
        
        $where[] = "$sql_tbl[categories].parentid = %d";
        $where[] = "$sql_tbl[categories].avail = 'Y'";

        if ($config['Sitemap']['sitemap_categories_order'] == 'ASC') {
            $order_by[] = 'name';
        } else {
            $order_by[] = "$sql_tbl[categories].order_by";
            $order_by[] = 'name';
        }

        $query = 'SELECT ' . implode(', ', $select) . ' FROM ' . implode(',', $from) . (!empty($join) ? ' LEFT JOIN ' . implode(' LEFT JOIN ', $join) : '') . ' WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . implode(', ', $order_by);
    }

    return $query;
}

/**
 * Build db query for products
 *
 * @param  bool   $for_category if false query to get all products will be build, instead for only for specified category
 * @return string
 */
function sitemap_build_products_query($for_category = true)
{
    static $query = '';

    if ($for_category != true) {
        $query = '';
    }

    if (empty($query)) {
        global $sql_tbl, $config;

        $select = $join = $from = $where = $order_by = array();

        // Product id
        $select[] = "$sql_tbl[products].productid AS id";

        // Product name
        if ($config['Sitemap']['sitemap_multilang'] == 'Y') {
            $code = $GLOBALS['shop_language'];
            $select[] = "IF ($sql_tbl[products_lng].productid IS NOT NULL AND $sql_tbl[products_lng].product != '', $sql_tbl[products_lng].product, $sql_tbl[products].product) AS name";
            $join[] = "$sql_tbl[products_lng] ON $sql_tbl[products_lng].code = '$code' AND $sql_tbl[products_lng].productid = $sql_tbl[products].productid";
        } else {
            $select[] = "$sql_tbl[products].product AS name";
        }

        // Membership condition
        if ($config['Sitemap']['sitemap_membership'] == 'Y') {
            global $user_account;
            $join[] = "$sql_tbl[product_memberships] ON $sql_tbl[product_memberships].productid = $sql_tbl[products].productid";
            $where[] = "($sql_tbl[product_memberships].membershipid IS NULL OR $sql_tbl[product_memberships].membershipid = '$user_account[membershipid]')";
        }

        // Avail condition
        if ($config['General']['unlimited_products'] == 'N') {
            $where[] = "$sql_tbl[products].avail > 0";
        }

        if (!empty($active_modules['Multiple_Storefronts'])) {
            $join[] = "$sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid";
            $where[] = "$sql_tbl[products_sf].sfid=$current_storefront";
        }

        if ($for_category == true) {
            $join[] = "$sql_tbl[products_categories] ON $sql_tbl[products].productid = $sql_tbl[products_categories].productid";
            $where[] = "$sql_tbl[products_categories].categoryid = %d";
        }

        $from[] = "$sql_tbl[products]";

        if ($config['Sitemap']['sitemap_products_order'] == 'ASC' || $for_category == false) {
            $order_by[] = 'name';
        } else {
            $order_by[] = "$sql_tbl[products_categories].orderby";
            $order_by[] = 'name';
        }

        $query = 'SELECT ' . implode(', ', $select) . ' FROM ' . implode(',', $from) . ' LEFT JOIN ' . implode(' LEFT JOIN ', $join) . (!empty($where) ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY ' . implode(', ', $order_by);
    }

    return $query;
}

/**
 * Add extra URL to db
 *
 * @param  array       $url
 * @return string|void error text
 */
function sitemap_extra_addurl($url)
{
    if (empty($url['name']) || empty($url['url'])) {
        return func_get_langvar_by_name('err_filling_form');
    }

    $insert = array(
        //'id' => intval($url['id']),
        'name' => $url['name'],
        'url' => trim(($url['url'])),
        'active' => ($url['active'] == 'Y' ? $url['active'] : 'N'),
        'orderby' => intval($url['orderby'])
    );
    func_array2insert('sitemap_extra', $insert);
}

/**
 * Remove extra URLs from db
 *
 * @param  array       $ids
 * @return string|void error text
 */
function sitemap_extra_delurls($ids)
{
    if (!is_array($ids) || empty($ids)) {
        return func_get_langvar_by_name('lbl_no_items_have_been_selected');
    } else {
        global $sql_tbl;
        db_query("DELETE FROM $sql_tbl[sitemap_extra] WHERE id IN ('" . implode("','", $ids) . "')");
    }
}

/**
 * Update extra urls
 *
 * @param  array       $urls
 * @return string|void error text
 */
function sitemap_extra_updateurls($urls)
{
    if (!is_array($urls)) {
        return func_get_langvar_by_name('lbl_permission_denied');
    }

    foreach ($urls as $id => $data) {
        $id = intval($id);
        if (0 > $id) {
            return func_get_langvar_by_name('lbl_permission_denied');
        }
        $update = array(
            'name' => $data['name'],
            'url' => trim(($data['url'])),
            'active' => ($data['active'] == 'Y' ? $data['active'] : 'N'),
            'orderby' => intval($data['orderby'])
        );
        func_array2update('sitemap_extra', $update, "id = '$id'");
    }
}

/**
 * Get all exrta URLs from db
 *
 * @return array
 */
function sitemap_extra_geturls()
{
    global $sql_tbl;
    $query = "SELECT $sql_tbl[sitemap_extra].* FROM $sql_tbl[sitemap_extra] ORDER BY $sql_tbl[sitemap_extra].orderby";
    $urls = func_query($query);
    return $urls;
}

/**
 * Generate page filename for html catalog
 *
 * @param  string $name
 * @return string
 */
function sitemap_filename($name)
{
    if (empty($name)) {
        return __FUNCTION__;
    } else {
        return $name;
    }
}

/**
 * Modify url to point to HTML pages of the catalog
 *
 * @param  array  $data current $additional_hc_data spec
 * @param  string $src page content
 * @return string
 */
function sitemap_process_page($data, $src)
{
    $pattern = '/(<a[^<>]+href[ ]*=[ ]*["\']?)([^"\']*' . $data['page_url'] . ')((#[^"\'>]+)?["\'>])/iUS';

    $GLOBALS['sitemap_page_name'] = $data['name_func_params'][0];

    $page_src = preg_replace_callback($pattern, 'sitemap_process_page_callback', $src);

    unset($GLOBALS['sitemap_page_name']);

    return $page_src;
}

/**
 * Callback function for sitemap_process_page
 *
 * @param  array $found generated by preg_replace_callback
 * @return url
 */
function sitemap_process_page_callback($found)
{
    global $hc_state;

    if (!func_is_current_shop($found[2])) {
        return $found[1] . $found[2] . '?' . $found[3];
    }

    $url = $found[1] . $hc_state['catalog']['webpath'] . $GLOBALS['sitemap_page_name'] . $found[3];

    return $url;
}
?>
