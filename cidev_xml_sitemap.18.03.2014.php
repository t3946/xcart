<?php /* MODIFIED: random:20889 [2010 Apr 21 14:23][Custom development (Google Base feed adjustment)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2008 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2008           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: cidev_xml_sitemap.php,v 1.42.2.29 2008/05/08 06:31:19 max Exp $
#

require "./auth.php";

x_session_register('active_modules');

x_load('backoffice','files','taxes', 'product', 'debug');

set_time_limit(0);

x_session_register("cidev_storefronts");
x_session_register('current_storefront');

// Db tables added by the module
$sql_tbl['xmlmap_extra']   = 'xcart_xmlmap_extra';
$sql_tbl['xmlmap_lastmod'] = 'xcart_xmlmap_lastmod';
// Config adjustment
$config['XML_Sitemap']['filename'] = 'sitemap.xml';



//func_print_r($storefronts);

if ($mode == "fcreate_dkcjhasdjfy823eishadfjky2386ugh"){
	$cidev_storefronts = $storefronts;

####################
//	foreach ($cidev_storefronts as $k => $v){
//		if ($v["storefrontid"] != "34"){
//			unset($cidev_storefronts[$k]);
//		}
//	}
####################

	$cidev_storefronts[0] = func_get_storefront_info(0);

}

if ($use_next_store_front == "Y"){
	$cidev_current_store_front = "-1"; // disable sf_info
}

//func_print_r($cidev_storefronts);

if ( (!empty($cidev_storefronts) && is_array($cidev_storefronts)) && ($use_next_store_front == "Y" || $mode == "fcreate_dkcjhasdjfy823eishadfjky2386ugh") ){
	foreach ($cidev_storefronts as $k => $v){
		$cidev_current_store_front = $v["storefrontid"];
		break;
	}
	unset($cidev_storefronts[$k]);
	x_session_save("cidev_storefronts");	
}

if ($cidev_current_store_front == "" && $cidev_current_store_front != "0"){
        $cidev_current_store_front = "-1"; // disable sf_info
}

//func_print_r($cidev_storefronts, $cidev_current_store_front);

$sf_info = func_get_storefront_info($cidev_current_store_front);

$current_storefront = $cidev_current_store_front;
x_session_save('current_storefront');

//func_print_r($sf_info);
//if ($use_next_store_front == "Y")
//die("123");

if (
	!empty($sf_info) &&
	(
		($REQUEST_METHOD == "GET" && ($mode == "fcreate_dkcjhasdjfy823eishadfjky2386ugh" || $mode == 'fcontinue_sdfh38rwhedjh2eyiwuhdoiuadr63') )
	)
) {
	print("<h1>" . $sf_info["domain"] . ": XML generation. </h1> <br />");

	$config['XML_Sitemap']['filename'] = 'sitemap.xml';

	if (!empty($active_modules['Multiple_Storefronts']) && $current_storefront) {
	    $xmlmap_location = (($HTTPS) ? 'https://' : 'http://') . func_get_http_location_sf($current_storefront) . $xcart_web_dir;
	} else {
	    $xmlmap_location = $http_location;
	}

	$sf_condition = "storefrontid=$current_storefront";
	

	$config['XML_Sitemap']['items']    = array(
        0 => array(
                'type'          => 'C',
                'lastmod'       => '',
                'changefreq'    => 'weekly',
                'priority'      => '0.8',
                'url_pattern'   => 'home.php?cat=',
                'items_query'   => "SELECT CONCAT('%s', $sql_tbl[categories].categoryid) as url, $sql_tbl[categories].categoryid as id,  IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[categories].categoryid AND $sql_tbl[xmlmap_lastmod].type = 'C' WHERE $sql_tbl[categories].avail='Y'" . ((empty($sf_condition)) ? '' : " AND $sql_tbl[categories].$sf_condition"),
                'multilanguage' => false,
        ),
        1 => array(
                'type'          => 'P',
                'lastmod'       => '',
                'changefreq'    => 'monthly',
                'priority'      => '0.6',
                'url_pattern'   => 'product.php?productid=',
                'multilanguage' => false,
        ),
/*
        2 => array(
                'type'          => 'M',
                'lastmod'       => '',
                'changefreq'    => 'weekly',
                'priority'      => '0.8',
                'url_pattern'   => 'manufacturers.php?manufacturerid=',
                'items_query'   => "SELECT CONCAT('%s', $sql_tbl[manufacturers].manufacturerid) as url, $sql_tbl[manufacturers].manufacturerid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[manufacturers].manufacturerid AND $sql_tbl[xmlmap_lastmod].type = 'M' WHERE $sql_tbl[manufacturers].avail='Y'" . ((empty($sf_condition)) ? '' : " AND $sql_tbl[manufacturers].$sf_condition"),
                'multilanguage' => false,
        ),
*/
        2 => array(
                'type'          => 'B',
                'lastmod'       => '',
                'changefreq'    => 'weekly',
                'priority'      => '0.8',
                'url_pattern'   => 'brands.php?brandid=',
                'multilanguage' => false,
        ),
        3 => array(
                'type'          => 'S',
                'lastmod'       => '',
                'changefreq'    => 'never',
                'priority'      => '0.2',
                'url_pattern'   => 'pages.php?pageid=',
                'items_query'   => "SELECT CONCAT('%s', $sql_tbl[pages].pageid) as url, $sql_tbl[pages].pageid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[pages] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[pages].pageid AND $sql_tbl[xmlmap_lastmod].type = 'S' WHERE $sql_tbl[pages].active='Y' AND $sql_tbl[pages].level='E'",
                'multilanguage' => false,
        ),
        4 => array(
                'type'          => 'E',
                'lastmod'       => '',
                'changefreq'    => 'monthly',
                'priority'      => '0.4',
                'url_pattern'   => '',
                'items_query'   => "%s SELECT url, '%s' as date FROM $sql_tbl[xmlmap_extra]" . ((empty($sf_condition)) ? '' : " WHERE $sf_condition"),
                'multilanguage' => false,
        ),
        5 => array(
                'type'          => 'H',
                'lastmod'       => '',
                'changefreq'    => 'daily',
                'priority'      => '1.0',
                'url_pattern'   => '',
                'items_query'   => "%s SELECT IF((SELECT value FROM $sql_tbl[config] WHERE name = 'xseo_xmlmap_use_root') = 'Y','$GLOBALS[http_location]','home.php') as url, '%s' as date",
                'multilanguage' => false,
        ),
	);

	$config['XML_Sitemap']['items'][1]['items_query'] = "SELECT CONCAT('%s', $sql_tbl[products].productid) as url, $sql_tbl[products].productid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date"
        . " FROM $sql_tbl[products] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[products].productid AND $sql_tbl[xmlmap_lastmod].type = 'P'"
        . " LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid"
        . " WHERE $sql_tbl[products].forsale='Y' AND $sql_tbl[products_sf].sfid = $current_storefront"
        . (($config['General']['unlimited_products'] == 'N') ? " AND $sql_tbl[products].avail > 0" : '');

	$config['XML_Sitemap']['items'][2]['items_query'] = "SELECT CONCAT('%s', $sql_tbl[brands].brandid) as url, $sql_tbl[brands].brandid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date"
        . " FROM $sql_tbl[brands] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[brands].brandid AND $sql_tbl[xmlmap_lastmod].type = 'B'"
        . " LEFT JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid = $sql_tbl[brands].brandid"
        . " WHERE $sql_tbl[brands].avail='Y' AND $sql_tbl[brands_sf].sfid = $current_storefront";

	xmlmap_generate();

	func_header_location("cidev_xml_sitemap.php?use_next_store_front=Y&mode=fcontinue_sdfh38rwhedjh2eyiwuhdoiuadr63");
}

die("DONE!");
?>
