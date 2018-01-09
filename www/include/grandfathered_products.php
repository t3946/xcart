<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2011 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2011           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: grandfathered_products.php,v 1.0 2011/12/06 14:39:00 kate Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('product');

if (isset($navpage)) {
    $page = intval($nav_page);
}

if (!isset($page) || empty($navpage)) {
    $page = 1;
}

$max = func_query_first('SELECT MAX(acc_percent) AS perc, MAX(acc_per_trans) AS per_trans'
    . ' FROM ' . $sql_tbl['payment_methods']
    . ' WHERE active = "Y"');

if (empty($max)) {
    $max = array(
        'perc'      => 0,
        'per_trans' => 0,
    );
}

if ($REQUEST_METHOD == 'POST') {
    
    if ($mode == 'update') {
        if (isset($update) && !empty($update) && is_array($update)) {
            
            $costs = func_query_hash('SELECT productid, cost_to_us FROM ' . $sql_tbl['products']
                . ' WHERE productid IN ("' . implode('", "', array_keys($update)) . '")', 'productid', false, true);

            if (!is_array($costs)) {
                $costs = array();
            }
            
            foreach ($update as $gpid => $gp) {
                if (!isset($costs[$gpid])) {
                    $costs[$gpid] = 0;
                }

                $min_price = price_format(($costs[$gpid] + $max['per_trans']) / (1 - $max['perc'] / 100));

                $gp['price'] = floatval($gp['price']);

                if ($min_price <= $gp['price']) {
                    $where = 'productid = "' . $gpid . '" AND membershipid = 0 AND variantid = 0 AND quantity = 1';
                    func_array2update('pricing', array('price' => $gp['price']), $where);
                } else {
                    $error = 'min_price';
                }

                func_array2update('products', array('google_search_term' => $gp['google_search_term']), 'productid = "' . $gpid . '"');
            }
            
            $productids = array_keys($update);
            
            $prs = func_query_hash('SELECT p.productid, p.discount_slope, p.discount_table, pr.price'
                . ' FROM ' . $sql_tbl['products'] . ' AS p'
                . ' LEFT JOIN ' . $sql_tbl['pricing'] . ' AS pr ON p.productid = pr.productid AND pr.membershipid = ""'
                . ' AND pr.quantity = 1 AND pr.variantid = "0"'
                . ' WHERE p.productid IN ("' . implode('", "', $productids) . '")', 'productid');
            
            db_query('DELETE FROM ' . $sql_tbl['pricing']
                . ' WHERE productid IN ("' . implode('", "', $productids) . '") AND membershipid = ""'
                . ' AND quantity > 1 AND variantid = "0"');
            
            if (is_array($prs)) {
                foreach ($prs as $productid => $p) {
                    $discount_table = explode(',', $p[0]['discount_table']);
                    foreach ($discount_table as $v) {
                        if (intval($v)) {
                            $query_data = array(
                                'productid' => $productid,
                                'quantity' => intval($v),
                                'price' => (1 - $p[0]['discount_slope'] * log($v,2) / 100) * $p[0]['price'],
                                'membershipid' => ''
                            );
                            func_array2insert('pricing', $query_data);
                        }
                    }
                }
            }

            if (!$error) {
                $top_message['content'] = func_get_langvar_by_name('lbl_grandfathered_products_upd_succ');
                $top_message['type'] = 'I';
            } elseif ($error == 'min_price') {
                $top_message['content'] = func_get_langvar_by_name('lbl_grandfathered_products_min_price_err');
                $top_message['type'] = 'I';
            } else {
                $top_message['content'] = func_get_langvar_by_name('lbl_grandfathered_products_upd_err');
                $top_message['type'] = 'I';
            }
        }
    }

    func_header_location('grandfathered_products.php?page=' . $page);
}

$fields = array(
    'p.productid',
    'p.productcode',
    'p.product',
    'p.google_search_term',
    'p.cost_to_us',
    'pr.price',
);

$where = array();

if (!empty($active_modules['Multiple_Storefronts'])) {
    $fields[] = 'c.storefrontid AS sfid';
    $sf_joins = ' LEFT JOIN ' . $sql_tbl['products_categories'] . ' AS pc ON p.productid = pc.productid'
    . ' LEFT JOIN ' . $sql_tbl['categories'] . ' AS c ON c.categoryid = pc.categoryid';
    $where[] = ' AND pc.main = "Y"';
} else {
    $fields[] = '0 AS sfid';
    $sf_joins = '';
}

$query_base = ' FROM ' . $sql_tbl['products'] . ' AS p'
    . ' LEFT JOIN ' . $sql_tbl['pricing'] . ' AS pr ON p.productid = pr.productid' . $sf_joins
    . ' WHERE p.google_search_term <> "" AND pr.membershipid = 0 AND pr.variantid = 0 AND pr.quantity = 1'
    . ((!empty($where)) ? implode(' AND ', $where) : '')
    . ' GROUP BY pr.productid';
	
//db_query('SET OPTION SQL_BIG_SELECTS=1');
$_res = db_query('SELECT p.productid ' . $query_base);

$total_items = db_num_rows($_res);
db_free_result($_res);

if ($total_items > 0) {

    #
    # Prepare the page navigation
    #
    if (isset($objects_per_page)) {
        
        $objects_per_page = intval($objects_per_page);
        
        if ($objects_per_page <= 0) {
            unset($objects_per_page);
        }
    }

    if (!isset($objects_per_page)) {
        
        $objects_per_page = intval($config['Appearance']['products_per_page_admin']);

        if ($objects_per_page <= 0) {
            $objects_per_page = 10;
        }
    }

    $total_nav_pages = ceil($total_items / $objects_per_page) + 1;

    include $xcart_dir . '/include/navigation.php';

    $query = 'SELECT ' . implode(', ', $fields) . $query_base
        . ' ORDER BY p.productcode, p.product'
        . ' LIMIT ' . $first_page . ', ' . $objects_per_page;

    $grandfathered_products = func_query_hash($query, 'productid', false, false);

    if (!empty($grandfathered_products)) {

        if (is_array($grandfathered_products)) {
            foreach ($grandfathered_products as $gpid => $gp) {
                $grandfathered_products[$gpid]['links'] = func_get_product_link_sf($gpid, $gp['sfid']);
                $grandfathered_products[$gpid]['google_search_link'] = urlencode($gp['google_search_term']);
                $grandfathered_products[$gpid]['min_price'] = ($gp['cost_to_us'] + $max['per_trans']) / (1 - $max['perc'] / 100);
                $grandfathered_products[$gpid]['rec_price'] = (1.2 * $gp['cost_to_us'] + $max['per_trans']) / (1 - $max['perc'] / 100);

                $net_price = (1 - $max['perc'] / 100) * $gp['price'] - $max['per_trans'];
                $grandfathered_products[$gpid]['profit'] = price_format($net_price - $gp['cost_to_us']);
                $grandfathered_products[$gpid]['margin'] = $grandfathered_products[$gpid]['profit'] / $net_price * 100;
            }
        }
        
        $smarty->assign('navigation_script', 'grandfathered_products.php?');
        $smarty->assign('first_item', $first_page + 1);
        $smarty->assign('last_item', min($first_page + $objects_per_page, $total_items));
        $smarty->assign('grandfathered_products', $grandfathered_products);
    }
}

$smarty->assign('total_items',$total_items);
