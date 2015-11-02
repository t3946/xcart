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
# $Id: init.php,v 1.0 2010/12/02 12:31:24 kate Exp $
#

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

x_session_register('current_storefront');

//$storefronts = func_query_hash('SELECT s.storefrontid, s.storefrontid as id, s.domain, s.prefix, s.status, s.orderby, i.filename FROM ' . $sql_tbl['storefronts'] . ' as s LEFT JOIN ' . $sql_tbl['images_S'] . ' as i ON s.storefrontid=i.id ORDER BY s.orderby, s.domain', 'id', false, false);

$storefronts = func_query_hash('SELECT s.storefrontid, s.storefrontid as id, s.domain, s.prefix, s.status, s.orderby, i.filename, i.image_x, f.filename as favicon_filename, c.value as storefront_name FROM ' . $sql_tbl['storefronts'] . ' as s LEFT JOIN ' . $sql_tbl['images_S'] . ' as i ON s.storefrontid=i.id LEFT JOIN ' . $sql_tbl['images_F'] . ' as f ON s.storefrontid=f.id LEFT JOIN ' . $sql_tbl['storefronts_config'] . ' as c ON s.storefrontid=c.storefrontid WHERE c.name="company_name" ORDER BY s.orderby, s.domain', 'id', false, false);


//func_print_r($storefronts);

if ($storefronts) {
    $domains = func_get_column_from_array('domain', $storefronts);
	if (!empty($domains) && is_array($domains)) {
		foreach ($domains as $k => $d) {
			$domains[$k] = strtolower($d);
		}
	}
	$smarty->assign('storefronts', $storefronts);
}

if ($search_all_website) {
	return;
}

if (
    (empty($domains) 
    || !in_array($_SERVER['HTTP_HOST'], $domains)) 
    && $_SERVER['HTTP_HOST'] != MAIN_SF_DOMAIN
    && (empty($test_storefronts)                           // For testing purposes
    || !in_array($_SERVER['HTTP_HOST'], $test_storefronts)) // Set the test storefronts in the config.local.php
) {
	if (!@func_readfile($xcart_dir . DIRECTORY_SEPARATOR . $shop_closed_file, true)) {
		echo func_get_langvar_by_name('txt_shop_temporarily_unaccessible', false, false, true);
    }
	exit();
}

$smarty->assign('main_storefront', MAIN_SF_DOMAIN);

if (in_array(AREA_TYPE, array('A', 'P'))) {
    if ($REQUEST_METHOD == 'POST') {
    	if ($mode == 'change_storefront') {
    		$current_storefront = intval($cur_sf);
    		func_header_location($_SERVER['REQUEST_URI']);
        }
    }
    if (isset($sf)) {
        $current_storefront = intval($sf);
        $qs = explode('&', $QUERY_STRING);
        foreach ($qs as $k => $v) {
            if ($v == 'sf=' . $sf)  {
                unset($qs[$k]);
            }
        }
        $url = parse_url($_SERVER['REQUEST_URI']);
        func_header_location($url['path'] . '?' . implode('&', $qs));
		}
}

if (empty($current_storefront) && $current_storefront != 0 || AREA_TYPE == 'C') {
	$current_storefront_info = func_get_storefront_info($_SERVER['HTTP_HOST'], 'D');
} else {
	$current_storefront_info = func_get_storefront_info($current_storefront, 'ID');
}

#
##
###
if ($current_storefront > 0){
	if (!empty($current_storefront_info["domain"])){
		$site_domain = $current_storefront_info["domain"];
	}
	else {
		$site_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$insert_data[storefrontid]'");
	}
} else {
	$site_domain = "www.artistsupplysource.com";
}
$smarty->assign('site_domain', $site_domain);
###
##
#

if (!empty($current_storefront_info)) {
    
    # Main storefront: prefix
    define('MAIN_SF_PREFIX', $config['General']['opt_order_prefix']);
	
    $current_storefront = $current_storefront_info['storefrontid'];
	func_sf_substitute_config_values($current_storefront);

#
##
###
        if (!$HTTPS && $config["Appearance"]["Enable_CDN"] == "Y" && !empty($config["Appearance"]["CDN_domain"]) && strpos($config["Appearance"]["CDN_domain"], "://") === false && AREA_TYPE == "C"){
                $config["Appearance"]["CDN_domain"] = ($HTTPS ? "https://" : "http://").$config["Appearance"]["CDN_domain"];
        }
###
##
#

} else {
	$current_storefront = 0;
}

if (AREA_TYPE == 'C') {

	$sf_links = func_query_hash("SELECT l.storefront2, s.orderby, s.domain, c.name, c.value"
        . " FROM $sql_tbl[storefront_links] l"
        . " LEFT JOIN $sql_tbl[storefronts] s ON s.storefrontid=l.storefront2"
        . " LEFT JOIN $sql_tbl[storefronts_config] c ON c.storefrontid=l.storefront2"
        . " WHERE (s.status='E' OR s.status IS NULL) AND l.storefront1='$current_storefront'  AND (c.name IN ('company_website', 'company_name') OR c.name IS NULL) ORDER BY s.orderby", 
        array('storefront2', 'name'), false, false);

    foreach ($sf_links as $k => $v) {
        if ($k == 0) {
            continue;
        }
        
        $tmp = array('orderby' => $v['company_website']['orderby'],
            'company_website' => $v['company_website']['value'],
            'company_name' => $v['company_name']['value']
        );
        $sf_links[$k] = $tmp;
    }

	// Check default storefront
	if (!empty($sf_links) && !empty($sf_links[0])) {
		$sf_default = func_get_storefront_info(0, 'ID', true);
        $sf_links[0] = array('company_website' => $sf_default['config']['Company']['company_website'],
            'company_name' => $sf_default['config']['Company']['company_name'],
            'orderby' => $config['default_storefront_orderby']
        );
    }

    usort($sf_links, func_msf_sort_front_array);

	if (!empty($sf_links)) {
		$storefronts_per_column = ceil((count($sf_links) + 1) / $config['Appearance']['storefront_columns']);
		$smarty->assign('storefronts_per_column', $storefronts_per_column);
		$smarty->assign('sf_links', $sf_links);
		$smarty->assign('sf_column_percent', 100 / $config['Appearance']['storefront_columns']);

#
##
###
#		$new_storefronts_per_column = ceil(count($sf_links) / 4);
#		$smarty->assign('new_storefronts_per_column', $new_storefronts_per_column);
#		$sf_links_count = count($sf_links);
#		$smarty->assign('sf_links_count', $sf_links_count);
###
##
#


	}
}

$smarty->assign('current_storefront', $current_storefront);
$smarty->assign('current_storefront_info', $current_storefront_info);

#
##
###
if (!empty($current_storefront) && $current_storefront!="0"){
	$cidev_store_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$current_storefront'");
	$smarty->assign('cidev_store_domain', $cidev_store_domain);

        $cidev_store_name = func_query_first_cell("SELECT value FROM $sql_tbl[storefronts_config] WHERE storefrontid='$current_storefront' AND name='company_name'");
        $smarty->assign('cidev_store_name', $cidev_store_name);
}

$cidev_main_storefront_name = func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name='company_name'");
$smarty->assign('cidev_main_storefront_name', $cidev_main_storefront_name);

###
##
#

/*
if ($qqq=="qqq"){
        x_load("debug");
        func_print_r($current_storefront_info, $current_storefront, $cidev_store_domain);
}
*/

?>
