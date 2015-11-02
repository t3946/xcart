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
# $Id: index.php,v 1.8 2006/03/30 12:23:04 max Exp $
#

require "./top.inc.php";

$search_all_website = true;
$current_area = 'C';
$page_pos = 500;

require "./init.php";


if (
    isset($pageid)
    && !empty($pageid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
    && !func_is_ajax_request()
    && $pageid != "42"
) {
    func_clean_url_permanent_redirect('S', intval($pageid));
}


require "./include/get_language.php";

if ($config['Search_All']['search_all_website_close'] == 'Y') {
    func_header_location('shop_closed.html');
}

if (strcasecmp($cur_host, $search_all_website_url) != 0) {
    func_header_location($xcart_web_dir . DIR_CUSTOMER . '/home.php'); 
}

$location = array(array($config['Search_All']['search_all_website_name'], ''));

if ($mode == 'check_all' && !empty($sku)) {
    include "./product.php";
}

if ($mode == 'search') {
    $search_data = array();
    $search_data['products'] = array('by_sku' => 1,
                                     'forsale' => 'Y',
                                     'substring' => trim($sku)
                               );
    
    include $xcart_dir . '/include/search.php';

    $smarty->assign('mode', 'search');
    $smarty->assign('search_all_website', 'Y');
} else {
    if ($pageid) {

#
##
###
	if ($pageid == "42"){

		x_load('crypt');

		$secure_check = text_decrypt($s);
		$om = $o.$m;


		if ($secure_check == $om && !empty($s) && !empty($o) && !empty($m)){
			$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$o' AND manufacturerid='$m'");
			if ($current_dc_status == "C"){
				db_query("UPDATE $sql_tbl[order_groups] SET dc_status='L' WHERE orderid = '$o' AND manufacturerid='$m'");
			} else {
				func_header_location($xcart_web_dir . DIR_CUSTOMER . '/index.php');
			}
		} else {
			func_header_location($xcart_web_dir . DIR_CUSTOMER . '/index.php');
		}
	}
###
#3
#

        $pages_dir = $smarty->template_dir . "/pages/$store_language/";
        $page_data = func_query_first("SELECT * FROM $sql_tbl[pages] WHERE pageid='$pageid' AND level='E' AND orderby>'$page_pos'");
        if ($page_data["language"] != $store_language) {
            $page_data = func_query_first("SELECT * FROM $sql_tbl[pages] WHERE filename='$page_data[filename]' $preview AND level='E' AND language='$store_language'");
        }

        if ($page_data) {
            $filename = $pages_dir.$page_data["filename"];
            $page_content = func_file_get($filename, true);
            
            if ($page_content === false) {
                $page_content = func_get_langvar_by_name("lbl_page_not_found", array(), false, true);
            }
            $smarty->assign("page_data", $page_data);
            $smarty->assign("page_content", $page_content);

            $location[] = array($page_data["title"], "");
        }
    } else {
        // Build storefronts links
//        $sfid_links = func_query("SELECT s.storefrontid, s.domain FROM $sql_tbl[storefronts_config] sc INNER JOIN $sql_tbl[storefronts] s ON s.storefrontid=sc.storefrontid WHERE sc.name='search_all_website_show' AND sc.value='Y' AND s.status='E' ORDER BY s.orderby");
        $sfid_links = func_query("SELECT s.storefrontid, s.domain FROM $sql_tbl[storefronts_config] sc INNER JOIN $sql_tbl[storefronts] s ON s.storefrontid=sc.storefrontid WHERE sc.name='search_all_website_show' AND sc.value='Y' ORDER BY s.orderby");
        $sfid_name = func_query_hash("SELECT storefrontid, value FROM $sql_tbl[storefronts_config] WHERE name='company_name' ORDER BY value", 'storefrontid', false, true);
        foreach ($sfid_links as $k => $v) {
            $sfid_links[$k]['name'] = $sfid_name[$v['storefrontid']];
        }
        if (empty($sfid_links)) {
            $sfid_links = array();
        }
        if ($config['Search_All']['search_all_website_show'] == 'Y') {
            $url_parts = parse_url($config['Company']['company_website']);
            unset($url_parts['scheme']);
            $company_website = implode('', $url_parts);
            array_unshift($sfid_links, array('storefrontid' => 0, 'name' => $config['Company']['company_name'], 'domain' => $company_website));
        }
        usort($sfid_links, func_msf_sort_front_array_by_name);
        l($sfid_links, '$sfid_links'); 

/*
if ($qqq == "qqq" ){
x_load("debug");
func_print_r($sfid_links);
}
*/

        $smarty->assign('sf_links', $sfid_links);
        $elements_number = count($sfid_links) + 1;
        if ($config['Search_All']['search_all_website_number_columns'] > 0) {
            $items_per_column = ceil($elements_number / $config['Search_All']['search_all_website_number_columns']);
            $whole_columns = intval($elements_number / $items_per_column);
            $excess_elements = $elements_number % $items_per_column;
            if ($excess_elements > 0) {
                $whole_columns += 1;
            }
        } else {
            $items_per_column = $elements_number;
            $whole_columns = 1;
        }
        $smarty->assign('items_per_column', $items_per_column);
        $smarty->assign('sf_column_percent', 100 / max(1, $whole_columns));
    }
}

// Build tab links
$tabs = func_query("SELECT title, pageid FROM $sql_tbl[pages] WHERE active='Y' AND level='E' AND orderby > '$page_pos' ORDER BY orderby");

if (!empty($tabs)){
	foreach ($tabs as $k => $v){
		$clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='S' AND resource_id='$v[pageid]'");
		$tabs[$k]["link"] = $clean_url_link."/";
	}
}	

$smarty->assign('tabs', $tabs);

//func_print_r($tabs);

$smarty->assign('location', $location);

func_display("customer/index.tpl",$smarty);

?>
