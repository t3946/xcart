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
# $Id: product_reports.php,v 1.0 2011/06/17 18:13:55 kate Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('mail', 'product');
x_session_register('selected_sess');
x_session_register('start_date_sess');

function func_product_report_sort_provider($a, $b) {
    if ($a['products_score'] > $b['products_score']) {
        return -1;
    } else if ($a['products_score'] < $b['products_score']) {
        return 1;
    }
    
    return 0;
}

if ($REQUEST_METHOD == 'POST') {
    if ($mode == 'send' && !empty($selected) && is_array($selected)) {
//        if ($Month) {
	if (!empty($posted_data["start_date"])){

//            $start_date = mktime(0, 0, 0, $Month, $Day, $Year);
//            $end_date = mktime(23, 59, 59, $Month, $Day, $Year);

#
##
###
              $start_date_arr = explode("/", $posted_data["start_date"]);
              $start_date = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);
              $end_date = mktime(23, 59, 59,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);
###
##
#

            $providers = func_query_hash('SELECT c.login, CONCAT(c.firstname, " ", c.lastname) as name, COUNT(p.productid) as add_products_number'
                . ' FROM ' . $sql_tbl['customers'] . ' as c'
                . ' LEFT JOIN ' . $sql_tbl['products'] . ' as p ON c.login = p.original_provider'
                . ' WHERE p.add_date BETWEEN "' . $start_date . '" AND "' . $end_date . '"'
                . ' GROUP BY p.original_provider ORDER BY add_products_number DESC, c.login', 'login', false, false);
            $_providers = func_query_hash('SELECT c.login, CONCAT(c.firstname, " ", c.lastname) as name, COUNT(p.productid) as mod_products_number'
                . ' FROM ' . $sql_tbl['customers'] . ' as c'
                . ' LEFT JOIN ' . $sql_tbl['products'] . ' as p ON c.login = p.provider'
                . ' WHERE p.add_date NOT BETWEEN "' . $start_date . '" AND "' . $end_date . '" AND p.mod_date BETWEEN "' . $start_date . '" AND "' . $end_date . '"'
                . ' GROUP BY p.provider ORDER BY mod_products_number DESC, c.login', 'login', false, false);
 
            // Merge add and update products
            foreach ($_providers as $k => $provider) {
                if (empty($providers[$k])) {
                    $providers[$k] = $provider;
                } else {
                    $providers[$k]['mod_products_number'] = $provider['mod_products_number'];
                }
            }

            if (!empty($active_modules['Multiple_Storefronts'])) {
                $ss_sql = array(
                    'field'     => ', IFNULL(s.domain, "' . MAIN_SF_DOMAIN . '") as domain',
                    'left_join' => ' LEFT JOIN ' . $sql_tbl['storefronts'] . ' as s ON c.storefrontid = s.storefrontid',
                );
            } else {
                $ss_sql = array(
                    'field'     => '',
                    'left_join' => '',
                );
            }

		$categories = func_query_hash('SELECT p.productid, pc.categoryid' . $ss_sql['field'] . ' FROM ' . $sql_tbl['products'] . ' as p'
                . ' LEFT JOIN ' . $sql_tbl['products_categories'] . ' as pc ON p.productid = pc.productid'
                . ' LEFT JOIN ' . $sql_tbl['categories'] . ' as c ON pc.categoryid = c.categoryid' . $ss_sql['left_join']
                . ' WHERE p.add_date BETWEEN "' . $start_date . '" AND "' . $end_date . '" OR p.mod_date BETWEEN "' . $start_date . '" AND "' . $end_date . '"', 'productid', true, false);
                
            $_products = db_query('SELECT p.productid, p.productcode, p.product, p.provider, p.original_provider, m.code, b.brand, b.brandid, p.source_sfid, p.add_date, p.mod_date FROM ' . $sql_tbl['products'] . ' as p'
                . ' LEFT JOIN ' . $sql_tbl['manufacturers'] . ' as m ON p.manufacturerid=m.manufacturerid'
                . ' LEFT JOIN ' . $sql_tbl['brands'] . ' as b ON p.brandid=b.brandid'
                . ' WHERE p.add_date BETWEEN "' . $start_date . '" AND "' . $end_date . '" OR p.mod_date BETWEEN "' . $start_date . '" AND "' . $end_date . '" ORDER BY p.provider, p.productcode');

            if (!empty($_products)) {
                $products = array();

                $totals = array('add_products_number' => 0, 'mod_products_number' => 0, 'products_score' => 0);
                foreach ($providers as $p => $v) {
                    $providers[$p]['add_products_number'] = intval($v['add_products_number']);
                    $providers[$p]['mod_products_number'] = intval($v['mod_products_number']);
                    $providers[$p]['products_score'] = $v['add_products_number'] + $v['mod_products_number'] / 2;
                    
                    $totals['add_products_number'] += $providers[$p]['add_products_number'];
                    $totals['mod_products_number'] += $providers[$p]['mod_products_number'];
                    $totals['products_score'] += $providers[$p]['products_score'];
                }

		uasort($providers, 'func_product_report_sort_provider');

		foreach ($providers as $k => $v) {
                    $products[strtolower($k)] = array();
                }

                while ($item = db_fetch_array($_products)) {
                    $item['categories'] = $categories[$item['productid']];
                    $item['links'] = func_get_product_link_sf($item['productid'], $item['source_sfid']);
                    $item['brand'] = '<a href="http://' . func_get_http_location_sf($item['source_sfid']) . '/brands.php?brandid=' . $item['brandid'] . '" title="">' . $item['brand'] . '</a>';
                    $item['is_new'] = $item['add_date'] >= $start_date && $item['add_date'] <= $end_date ? 'Y' : 'N';

                    $provider = $item['is_new'] == 'Y' ? $item['original_provider'] : $item['provider'];
                    $products[strtolower($provider)][$item['productid']] = $item;
                }

                if (is_array($products)) {
                    foreach ($products as $k => $p) {
                        if (empty($p)) {
                            unset($products[$k]);
                        }
                    }
                    $total = db_num_rows($_products);
                    $mail_smarty->assign('total', $total);
                }
                
                $mail_smarty->assign('products', $products);
                $mail_smarty->assign('providers', $providers);
                $mail_smarty->assign('totals', $totals);
                $mail_smarty->assign('start_date', $start_date);

                $error = false;
                foreach ($selected as $operator => $v) {
                    $operator = trim($operator);
                    if (func_check_email($operator)) {

                        func_send_mail($operator, 'mail/' . $prefix . 'product_report_subj.tpl', 'mail/product_report.tpl', $config['Company']['site_administrator'], true, true);
                    } else {
                        $error = true;
                    }
                }
            
                if ($error) {
                    $top_message['type'] = 'W';
                    $top_message['content'] = func_get_langvar_by_name('err_wrong_emails', null, false, true);
                } else {
                    $top_message['type'] = 'I';
                    $top_message['content'] = func_get_langvar_by_name('lbl_product_report_is_sent', null, false, true);
                    
                }
            }
        }
    }
    $selected_sess = $selected;
    $start_date_sess = $start_date;

    func_header_location('product_reports.php');
}

if ($user_account['flag'] != 'FS') {
    if (!empty($config['Email_Note']['eml_send_product_reports_to'])) {
        $operators = explode(',', $config['Email_Note']['eml_send_product_reports_to']);
        if (is_array($operators)) {
            foreach ($operators as $k => $op) {
                $op = trim($op);
            }

            if (!empty($operators)) {
                $smarty->assign('operators', $operators);
            }
        }
    } else {
        $top_message['type'] = 'E';
        $top_message['content'] = func_get_langvar_by_name('err_no_operators', null, false, true);
    }
}

if (!empty($selected_sess)) {
    $smarty->assign('selected', $selected_sess);
}
if (!empty($start_date_sess)) {
    $smarty->assign('start_date', $start_date_sess);
}
$smarty->assign('dialog_tools_data', $dialog_tools_data);
$smarty->assign('main', 'product_reports');
?>

