<?php /* MODIFIED: random:20766 [2010 May 11 13:18][Custom development ("Additional categories" feature)] */ ?>
<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (Изменения для модуля UPS + Изменения в способ ввода Tracking numbers для заказов)] */ ?>
<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# $Id: init.php,v 1.31.2.20 2007/01/12 07:27:42 max Exp $
#
# X-Cart initialization
#

if (!defined('XCART_START')) { header("Location: index.php"); die("Access denied"); }

#
##
###
if (defined('CIDEV_CRON_START') && CIDEV_CRON_START == "CRON"){

        if (empty($_SERVER['HTTP_HOST'])){
//                $_SERVER['HTTP_HOST'] = "dev1.test.artistsupplysource.com";
              $_SERVER['HTTP_HOST'] = "www.artistsupplysource.com";
        }

        if (empty($_SERVER['REQUEST_URI'])){
                $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_FILENAME'];
        }
}
###
##
#


$cur_host = $_SERVER['HTTP_HOST'];
$cur_url = $_SERVER['REQUEST_URI'];
if ($cur_host == 'www.kolinskyartbrushes.com') {
    $new_url = ((!empty($HTTPS)) ? 'https://' : 'http://') . 'www.artistsupplysource.com' .  $cur_url;
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $new_url);
    exit();
}

@require_once $xcart_dir."/prepare.php";
@require_once $xcart_dir."/include/func/func.core.php";
x_load('db','files', 'debug', 'clean_urls');

if (func_version_compare(phpversion(), "5.3.0") >= 0) {
   define('X_PHP530_COMPAT', true);
}

if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
   @date_default_timezone_set(@date_default_timezone_get());

if (!@is_readable($xcart_dir."/config.php")) {
	echo "Can't read config!";
	exit;
}
@require_once $xcart_dir."/config.php";

@include_once $xcart_dir."/config.local.php";

$file_temp_dir = $var_dirs["tmp"];

#
# Product thumbnail properties
#

define('THUMB_BGCOLOR', 0xFFFFFF);
define('THUMB_QUALITY', 97);

#
# SQL tables aliases...
#
$sql_tbl = array (
	"avs_codes" => "xcart_avs_codes",
	"benchmark_pages" => "xcart_benchmark_pages",
# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
	"brands" => "xcart_brands",
	"brands_lng" => "xcart_brands_lng",
	"images_B" => "xcart_images_B",
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
	"categories" => "xcart_categories",
# START: random:20766 [2010 May 11 13:18] 
	"categories_parents" => "xcart_categories_parents",
# END: random:20766 [2010 May 11 13:18] 
	"categories_subcount" => "xcart_categories_subcount",
	"categories_lng" => "xcart_categories_lng",
	"category_memberships" => "xcart_category_memberships",
	"cc_gestpay_data" => "xcart_cc_gestpay_data",
	"cc_pp3_data" => "xcart_cc_pp3_data",
	"ccprocessors" => "xcart_ccprocessors",
	"chprocessors" => "xcart_chprocessors",
	"config" => "xcart_config",
	"contact_fields" => "xcart_contact_fields",
	"contact_field_values" => "xcart_contact_field_values",
	"counters" => "xcart_counters",
	"counties" => "xcart_counties",
	"countries" => "xcart_countries",
	"country_currencies" => "xcart_country_currencies",
	"currencies" => "xcart_currencies",
	"customers" => "xcart_customers",
	"delivery" => "xcart_delivery",
	'departments' => 'xcart_departments',
	"discount_coupons" => "xcart_discount_coupons",
	"discount_coupons_login" => "xcart_discount_coupons_login",
	"discounts" => "xcart_discounts",
	"discount_memberships" => "xcart_discount_memberships",
	"download_keys" => "xcart_download_keys",
	"export_ranges"	=> "xcart_export_ranges",
	"extra_fields" => "xcart_extra_fields",
	"extra_fields_lng" => "xcart_extra_fields_lng",
	"extra_field_values" => "xcart_extra_field_values",
	"featured_products" => "xcart_featured_products",
	"fedex_rates" => "xcart_fedex_rates",
	"fedex_zips" => "xcart_fedex_zips",
	"filter_presets" => "xcart_filter_presets",
	"filter_preset_distributors" => "xcart_filter_preset_distributors",
	"filter_preset_statuses" => "xcart_filter_preset_statuses",
	"gcheckout_orders" => "xcart_gcheckout_orders",
	"gcheckout_restrictions" => "xcart_gcheckout_restrictions",
	"ge_products" => "xcart_ge_products",
	"giftcerts" => "xcart_giftcerts",
	"images_A" => "xcart_images_A",
	"images_T" => "xcart_images_T",
	"images_P" => "xcart_images_P",
	"images_D" => "xcart_images_D",
	"images_C" => "xcart_images_C",
	"images_M" => "xcart_images_M",
	"import_cache" => "xcart_import_cache",
	"languages" => "xcart_languages",
	"languages_alt" => "xcart_languages_alt",
	"login_history" => "xcart_login_history",
	"manufacturers" => "xcart_manufacturers",
	"manufacturers_lng" => "xcart_manufacturers_lng",
	"memberships" => "xcart_memberships",
	"memberships_lng" => "xcart_memberships_lng",
	"modules" => "xcart_modules",
	"newsletter" => "xcart_newsletter",
	"newslist_subscription" => "xcart_newslist_subscription",
	"newslists" => "xcart_newslists",
	"old_passwords" => "xcart_old_passwords",
	"order_details" => "xcart_order_details",
# START: random:20341 [2010 Jul 29 14:46] 
	"order_groups" => "xcart_order_groups",
# END: random:20341 [2010 Jul 29 14:46] 
	"order_extras" => "xcart_order_extras",
    'order_statuses' => 'xcart_order_statuses',
    'order_status_notifications' => 'xcart_order_status_notifications',
	"orders" => "xcart_orders",
	"pages" => "xcart_pages",
	"payment_methods" => "xcart_payment_methods",
	"pc_markup_memberships" => "xcart_pc_markup_memberships",
	"php_sessions" => "xcart_php_sessions",
	"pmethod_memberships" => "xcart_pmethod_memberships",
	"pricing" => "xcart_pricing",
	"product_bookmarks" => "xcart_product_bookmarks",
	'product_files'	=> 'xcart_product_files',
	"product_links" => "xcart_product_links",
	"product_memberships" => "xcart_product_memberships",
	"product_reviews" => "xcart_product_reviews",
	"product_taxes" => "xcart_product_taxes",
	"product_votes" => "xcart_product_votes",
	"products" => "xcart_products",
	"products_categories" => "xcart_products_categories",
	"products_lng" => "xcart_products_lng",
	"quick_flags" => "xcart_quick_flags",
	"quick_prices" => "xcart_quick_prices",
	"referers" => "xcart_referers",
    'refund_groups' => 'xcart_refund_groups',
    'refunded_products' => 'xcart_refunded_products',
	"register_fields" => "xcart_register_fields",
	"register_field_values" => "xcart_register_field_values",
	'replacements' => 'xcart_replacements',
    'seed_categories'   => 'xcart_seed_categories',
	"sessions_data" => "xcart_sessions_data",
	"setup_images" => "xcart_setup_images",
	"shipping" => "xcart_shipping",
	"shipping_cache" => "xcart_shipping_cache",
	"shipping_options" => "xcart_shipping_options",
	"shipping_rates" => "xcart_shipping_rates",
	"shipping_cache" => "xcart_shipping_cache",
	"states" => "xcart_states",
	"stats_adaptive" => "xcart_stats_adaptive",
	"stats_cart_funnel" => "xcart_stats_cart_funnel",
	"stats_customers_products" => "xcart_stats_customers_products",
	"stats_pages" => "xcart_stats_pages",
	"stats_pages_paths" => "xcart_stats_pages_paths",
	"stats_pages_views" => "xcart_stats_pages_views",
	"stats_search" => "xcart_stats_search",
	"stats_shop" => "xcart_stats_shop",
	"subscription_customers" => "xcart_subscription_customers",
	"subscriptions" => "xcart_subscriptions",
	"tax_rate_memberships" => "xcart_tax_rate_memberships",
	"tax_rates" => "xcart_tax_rates",
	"taxes" => "xcart_taxes",
	"temporary_data" => "xcart_temporary_data",
	"titles" => "xcart_titles",
# START: random:18591_18598 [2009 Jul 29 10:36] 
	"tracking_links" => "xcart_tracking_links",
# END: random:18591_18598 [2009 Jul 29 10:36] 
	"wishlist" => "xcart_wishlist",
	"users_online" => "xcart_users_online",
	"zip_code_info" => "xcart_zip_code_info",
	"zone_element" => "xcart_zone_element",
	"zones" => "xcart_zones",
	
	"geo_litecity_blocks" => "xcart_geo_litecity_blocks",
	"geo_litecity_location" => "xcart_geo_litecity_location",

#
##
###
    "clean_urls"                        => "xcart_clean_urls",
    "clean_urls_history"                => "xcart_clean_urls_history",
###
##
#
    "Telephone_area_codes" => "xcart_Telephone_area_codes",

    "distributor_contacts" => "xcart_distributor_contacts",

    "info_pages_categories" => "xcart_info_pages_categories",
    "info_pages" => "xcart_info_pages",
    "info_pages_subcount" => "xcart_info_pages_subcount",
    "links_to_distributor_invoices" => "xcart_links_to_distributor_invoices",
    "templates_for_communication" => "xcart_templates_for_communication",
    "request_availability_options" => "xcart_request_availability_options",
    "order_logs" => "xcart_order_logs",
    "fraud_check" => "xcart_fraud_check",
    "order_fraud_checks" => "xcart_order_fraud_checks",
    "filter_preset_fraud_statuses" => "xcart_filter_preset_fraud_statuses",
    "order_fraud_statuses" => "xcart_order_fraud_statuses",
    "order_additional_fee" => "xcart_order_additional_fee",
    "distributor_return_address" => "xcart_distributor_return_address",
    "notify_when_in_stock" => "xcart_notify_when_in_stock",
    "reconciliation_search_keyphrases" => "xcart_reconciliation_search_keyphrases",
    "supplier_product_feeds" => "xcart_supplier_product_feeds",
    "filter_preset_ship_to_country" => "xcart_filter_preset_ship_to_country",
    "seo_categories_keyphrases" => "xcart_seo_categories_keyphrases",
    "reconciliations" => "xcart_reconciliations",
    "reconciliation_orderid" => "xcart_reconciliation_orderid",
    "reconciliation_upload_info" => "xcart_reconciliation_upload_info",
    "bpu_rows" => "xcart_bpu_rows",
    "bpu_result" => "xcart_bpu_result",
    "search_stats" => "xcart_search_stats",
    "attention_tags_values" => "xcart_attention_tags_values",
    "attention_tags_values_logins" => "xcart_attention_tags_values_logins",
    "orders_additional_tags" => "xcart_orders_additional_tags",
    "filter_preset_attention_tag_statuses" => "xcart_filter_preset_attention_tag_statuses",
    "approximation_shipping_rates" => "xcart_approximation_shipping_rates",
    "pc_options" => "xcart_pc_options",
    "pc_terms" => "xcart_pc_terms",
    "pc_category_terms" => "xcart_pc_category_terms",
    "pc_locks" => "xcart_pc_locks",
    "pc_runs_log" => "xcart_pc_runs_log",
    "otrs_options" => "xcart_otrs_options",
    "shipping_quote_log" => "xcart_shipping_quote_log",
    "shipping_quote_products_log" => "xcart_shipping_quote_products_log"
);

# START: random:20341 [2010 Jul 29 14:46] 
$price_details_names = array("net", "gst", "pst", "gross");

define('FILTER_PRESET_PER_ROW', 5);

# Artist Supply Source distributor credentials

$artss_manufacturerid = 3;
$artss_code = 'ART'; 

# END: random:20341 [2010 Jul 29 14:46] 
#
# Redefine error_reporting option
#
error_reporting ($x_error_reporting);

#
# Multi Storefront
#
if (!empty($current_storefront)) {
	@include_once $xcart_dir.'modules/Multiple_Storefronts/sf_config.php';
}




#
# Include functions
#

include_once($xcart_dir."/include/bench.php");

#
# Connect to database
#
$db_connect_limit = 5;
while ($db_connect_limit-- > 0 && !@db_connect($sql_host, $sql_user, $sql_password)) { }
db_select_db($sql_db) || die("Sorry, the shop is inaccessible temporarily. Please try again later.");

$tmp = func_query_first("SHOW VARIABLES LIKE 'max_allowed_packet'");
$sql_max_allowed_packet = intval($tmp['Value']);
unset($tmp);

if (preg_match("/^(\d+\.\d+\.\d+)/", mysql_get_server_info(), $match)) {
        define("X_MYSQL_VERSION", $match[1]);

        if (func_version_compare(X_MYSQL_VERSION, "5.0.0") >= 0)
                db_query("SET sql_mode = 'MYSQL40'");

        if (func_version_compare(X_MYSQL_VERSION, "5.0.17") > 0)
                define("X_MYSQL5_COMP_MODE", true);

        if (func_version_compare(X_MYSQL_VERSION, "5.0.18") == 0)
                define("X_MYSQL5018_COMP_MODE", true);
}



#
## Set the session name here
###
if (AREA_TYPE == 'C'){

	$cidev_tmp_storefrontid = func_query_first_cell("SELECT storefrontid FROM xcart_storefronts WHERE domain='$_SERVER[HTTP_HOST]'");

	if (empty($cidev_tmp_storefrontid)){
        	$cidev_tmp_storefrontid = '0';
	}
	else {
		$xcart_http_host = $_SERVER["HTTP_HOST"];
		$xcart_https_host = $_SERVER["HTTP_HOST"];

	}
}

$XCART_SESSION_NAME = "xid".$cidev_tmp_storefrontid;
###
##
#







#
# HTTP & HTTPS locations
#
$http_location = "http://$xcart_http_host".$xcart_web_dir;
$https_location = "https://$xcart_https_host".$xcart_web_dir;

#
# Fix broken path for some hostings
#
$current_location = $HTTPS ? $https_location : $http_location;
if (preg_match("/kolinskyartbrushes.com/i",$HTTP_REFERER) || preg_match("/kolinskyartbrushes.com/i",$last_current_location)) {
        $current_location = str_replace($HTTPS ?$xcart_https_host:$xcart_http_host, "kolinskyartbrushes.com",$current_location);
} elseif (preg_match("/artistssupplysource.com/i",$HTTP_REFERER) || preg_match("/artistssupplysource.com/i",$last_current_location)) {
        $current_location = str_replace($HTTPS ?$xcart_https_host:$xcart_http_host, "artistssupplysource.com",$current_location);
}
$_tmp = parse_url($current_location);
$xcart_web_dir = empty($_tmp["path"]) ? "" : $_tmp["path"];

if ($HTTPS_RELAY) {

	# Fix wrong PHP_SELF for HTTPS relay
	$_tmp = parse_url($http_location);
	if (empty($_tmp['path'])) {
		$PHP_SELF = $xcart_web_dir.$PHP_SELF;

	} else {
		$PHP_SELF = $xcart_web_dir.preg_replace("/^".preg_quote($_tmp['path'], "/")."/", "", $PHP_SELF);
	}

	$_SERVER['PHP_SELF'] = $PHP_SELF;

}

$_tmp = parse_url($https_location);
$xcart_https_host = $_tmp["host"];
unset($_tmp);
$_tmp = parse_url($http_location);
$xcart_http_host = $_tmp["host"];
unset($_tmp);


#
# Create URL
#
$php_url = array("url" => "http".($HTTPS=="on"?"s://".$xcart_https_host:"://".$xcart_http_host).$PHP_SELF, "query_string" => $QUERY_STRING);

#
# Check internal temporary directories
#
$var_dirs_rules = array (
	"cache" => array (
		".htaccess" => "Deny from all\n<files \"*.js\">\nAllow from all\n</files>"
	),
	"tmp" => array (
		".htaccess" => "Deny from all"
	),
	"templates_c" => array (
		".htaccess" => "Deny from all"
	),
	"upgrade" => array (
		".htaccess" => "Deny from all"
	),
	"log" => array (
		".htaccess" => "Deny from all"
	)
);

foreach ($var_dirs as $k=>$v) {
	if (!file_exists($v) || !is_dir($v)) {
		@unlink($v);
		@func_mkdir($v);
	}

	if (!is_writable($v) || !is_dir($v)) {
		echo "Can't write data to the temporary directory: <b>".$v."</b>.<br />Please check if it exists, and have writable permissions.";
		exit;
	}

	foreach ($var_dirs_rules[$k] as $f=>$c) {
		if (file_exists($v."/".$f))
			continue;

		if ($__fp = @fopen($v."/".$f, "w")) {
			@fwrite($__fp, $c);
			@fclose($__fp);
		}
	}
}

#
# Initialize logging
#
@require_once $xcart_dir."/include/logging.php";

#
# Create Smarty object
#
if (!@include $xcart_dir."/smarty.php") {
    echo "Can't launch template engine!";
    exit;
}

#
# Init miscellaneous vars
#
$smarty->assign("skin_config",$skin_config_file);
$mail_smarty->assign("skin_config",$skin_config_file);

$smarty->assign("http_location",$http_location);
$mail_smarty->assign("http_location",$http_location);
$smarty->assign("https_location",$https_location);
$mail_smarty->assign("https_location",$https_location);
$smarty->assign("xcart_web_dir",$xcart_web_dir);
$smarty->assign("current_location",$current_location);
$smarty->assign("php_url",$php_url);

# START: random:20341 [2010 Jul 29 14:46] 
$smarty->assign("artss_manufacturerid", $artss_manufacturerid);
$smarty->assign("artss_code", $artss_code);

# END: random:20341 [2010 Jul 29 14:46] 
foreach ($var_dirs_web as $k=>$v) {
	$var_dirs_web[$k] = $current_location.$v;
}

$smarty->assign_by_ref("var_dirs_web", $var_dirs_web);

$xcart_catalogs = array (
	"admin" => $current_location.DIR_ADMIN,
	"customer" => $current_location.DIR_CUSTOMER,
	"provider" => $current_location.DIR_PROVIDER,
	"partner" => $current_location.DIR_PARTNER
);

$xcart_catalogs_secure = array (
	"admin" => $https_location.DIR_ADMIN,
	"customer" => $https_location.DIR_CUSTOMER,
	"provider" => $https_location.DIR_PROVIDER,
	"partner"=>$https_location.DIR_PARTNER
);

$smarty->assign("catalogs", $xcart_catalogs);
$smarty->assign("catalogs_secure", $xcart_catalogs_secure);
$mail_smarty->assign("catalogs", $xcart_catalogs);
$mail_smarty->assign("catalogs_secure", $xcart_catalogs_secure);

#
# Files directories
#
$files_dir_name = $xcart_dir.$files_dir;
$files_http_location = $http_location.$files_webdir;
$smarty->assign("files_location",$files_dir_name);

$templates_repository = $xcart_dir.$templates_repository_dir;

/*
#
# Include functions
#

include_once($xcart_dir."/include/bench.php");

#
# Connect to database
#
$db_connect_limit = 5;
while ($db_connect_limit-- > 0 && !@db_connect($sql_host, $sql_user, $sql_password)) { }
db_select_db($sql_db) || die("Sorry, the shop is inaccessible temporarily. Please try again later.");

$tmp = func_query_first("SHOW VARIABLES LIKE 'max_allowed_packet'");
$sql_max_allowed_packet = intval($tmp['Value']);
unset($tmp);

if (preg_match("/^(\d+\.\d+\.\d+)/", mysql_get_server_info(), $match)) {
	define("X_MYSQL_VERSION", $match[1]);

	if (func_version_compare(X_MYSQL_VERSION, "5.0.0") >= 0)
		db_query("SET sql_mode = 'MYSQL40'");

	if (func_version_compare(X_MYSQL_VERSION, "5.0.17") > 0)
		define("X_MYSQL5_COMP_MODE", true);

	if (func_version_compare(X_MYSQL_VERSION, "5.0.18") == 0)
		define("X_MYSQL5018_COMP_MODE", true);
}



#
## Set the session name here
###
$cidev_tmp_storefrontid = func_query_first_cell("SELECT storefrontid FROM xcart_storefronts WHERE domain='$_SERVER[HTTP_HOST]'");
if (empty($cidev_tmp_storefrontid)){
	$cidev_tmp_storefrontid = '0';
}

$XCART_SESSION_NAME = "xid".$cidev_tmp_storefrontid;
###
##
#
*/



#
# Set MySQL variable 'max_join_size'
#
$mjsize = func_query_first("SHOW VARIABLES LIKE 'max_join_size'");
if (intval($mjsize['Value']) < 1073741824)
	db_query("SET OPTION SQL_MAX_JOIN_SIZE=1073741824");
unset($mjsize);

#
# Read config variables from Database
# This variables are used inside php scripts, not in smarty templates
#
$c_result = db_query("SELECT name, value, category FROM $sql_tbl[config] WHERE type != 'separator'");
$config = array();
if ($c_result) {
	while ($row = db_fetch_row($c_result)) {
		if (!empty($row[2]))
			$config[$row[2]][$row[0]] = $row[1];
		else
			$config[$row[0]] = $row[1];
	}
}

db_free_result($c_result);


/**
 * Schema to test .htaccess file if some configuration variables are on.
 */
$schemaTestHtaccess = array(
    array(
        'config' => array(
            'SEO',
            'clean_urls_enabled',
        ),
        'htaccessWord' => 'dispatcher.php [L]',
    ),
);

$htaccessWarning = array();

foreach ($schemaTestHtaccess as $schemaUnit) {
    if (
        'Y' == $config[$schemaUnit['config'][0]][$schemaUnit['config'][1]]
        && !func_test_htaccess($schemaUnit['htaccessWord'])
    ) {
        if (
            defined('AREA_TYPE')
            && 'C' == constant('AREA_TYPE')
        ) {

//            $config[$schemaUnit['config'][0]][$schemaUnit['config'][1]] = 'N';

        } else {

            $htaccessWarning[$schemaUnit['config'][0]] = "Y";

        }
    }
}

$smarty->assign('htaccess_warning', $htaccessWarning);


$config["Sessions"]["session_length"] = $use_session_length;

#
# Include data cache functionality
#
@include_once($xcart_dir."/include/data_cache.php");

#
# Timezone offset (sec) = N hours x 60 minutes x 60 seconds
#
$config["Appearance"]["timezone_offset"] = intval($config["Appearance"]["timezone_offset"])*3600;

#
# Set timezone
#
if (function_exists('date_default_timezone_set')) {
	date_default_timezone_set('EST');
}

#
# Define 'End year' for date selectors in the templates
#
$config["Company"]["end_year"] = date("Y", time()+$config["Appearance"]["timezone_offset"]);

#
# Last database backup date
#
if ($config["db_backup_date"])
	$config["db_backup_date"] += $config["Appearance"]["timezone_offset"];

$config['available_images']['A'] = "U";
$config['available_images']['T'] = "U";
$config['available_images']['P'] = "U";
$config['available_images']['C'] = "U";

$config['substitute_images']['P'] = "T";

$httpsmod_active = NULL;
if (!defined("QUICK_START")) {
	if (empty($config["Appearance"]["thumbnail_width"]))
		$config["Appearance"]["thumbnail_width"] = 0;

	if (empty($config["Appearance"]["date_format"]))
		$config["Appearance"]["date_format"] = "%d-%m-%Y";

	$config["Appearance"]["datetime_format"] =
		$config["Appearance"]["date_format"]." ".$config["Appearance"]["time_format"];
}

#
##
###
@include_once($xcart_dir."/include/bots_before_sessions.php");
###
##
#


#
# Prepare session
#
@include_once $xcart_dir."/include/sessions.php";

@include_once $xcart_dir."/include/unallowed_request.php";

if (!defined('QUICK_START')) {
	@include_once($xcart_dir."/include/blowfish.php");

	#
	# Start Blowfish class
	#
	$blowfish = new ctBlowfish();
}

$t = parse_url($config['Search_All']['search_all_website_url']);
$search_all_website_url = $t['host'];
$search_all_website_url = ltrim($search_all_website_url, 'www.');
$cur_host = ltrim($cur_host, 'www.');
if (!$search_all_website && strcasecmp($cur_host, $search_all_website_url) == 0 && AREA_TYPE == 'C') {
//    func_header_location('index.php');
	include_once $xcart_dir."/index.php";
	die();
}

#
# Prepare number variables
#
@include_once $xcart_dir."/include/number_conv.php";

if (!defined("QUICK_START")) {
	#
	# Define default user profile fields
	#
	$default_user_profile_fields = array(
		"title"     => array("avail"=>"Y","required"=>"Y"),
		"firstname" => array("avail"=>"Y","required"=>"Y"),
		"lastname"  => array("avail"=>"Y","required"=>"Y"),
		"company"   => array("avail"=>"Y","required"=>"N"),
		"ssn" => array (
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'Y',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'Y',"C" => 'N',"H" => "N")),
		"tax_number" => array (
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'Y',"H" => "Y"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"b_title" => array (
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"b_firstname" => array (
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"b_lastname" => array (
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"b_address"   => array("avail"=>"Y","required"=>"Y"),
		"b_address_2" => array("avail"=>"Y","required"=>"N"),
		"b_city"      => array("avail"=>"Y","required"=>"Y"),
		"b_county"    => array("avail"=>"Y","required"=>"Y"),
		"b_state"     => array("avail"=>"Y","required"=>"Y"),
		"b_country"   => array("avail"=>"Y","required"=>"Y"),
		"b_zipcode"   => array("avail"=>"Y","required"=>"Y"),
		"s_title" => array(
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"s_firstname" 	=> array(
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"s_lastname" => array(
			"avail"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N"),
			"required"=> array("A" => 'N',"P" => 'N',"B" => 'N',"C" => 'N',"H" => "N")),
		"s_address"   => array("avail"=>"Y","required"=>"N"),
		"s_address_2" => array("avail"=>"Y","required"=>"N"),
		"s_city"      => array("avail"=>"Y","required"=>"N"),
		"s_county"    => array("avail"=>"Y","required"=>"N"),
		"s_state"     => array("avail"=>"Y","required"=>"N"),
		"s_country"   => array("avail"=>"Y","required"=>"N"),
		"s_zipcode"   => array("avail"=>"Y","required"=>"N"),
		"phone"       => array("avail"=>"Y","required"=>"Y"),
		"email"       => array("avail"=>"Y","required"=>"Y"),
		"fax"         => array("avail"=>"Y","required"=>"N"),
		"url"         => array("avail"=>"Y","required"=>"N")
	);

	#
	# Define default contact us fields
	#
	$default_contact_us_fields = array(
		"username"    => array("avail"=>"Y","required"=>"Y"),
		"title"       => array("avail"=>"Y","required"=>"Y"),
		"firstname"   => array("avail"=>"Y","required"=>"Y"),
		"lastname"    => array("avail"=>"Y","required"=>"Y"),
		"company"     => array("avail"=>"Y","required"=>"N"),
		"b_address"   => array("avail"=>"Y","required"=>"Y"),
		"b_address_2" => array("avail"=>"Y","required"=>"N"),
		"b_city"      => array("avail"=>"Y","required"=>"Y"),
		"b_county"    => array("avail"=>"Y","required"=>"Y"),
		"b_state"     => array("avail"=>"Y","required"=>"Y"),
		"b_country"   => array("avail"=>"Y","required"=>"Y"),
		"b_zipcode"   => array("avail"=>"Y","required"=>"Y"),
		"phone"       => array("avail"=>"Y","required"=>"Y"),
		"email"       => array("avail"=>"Y","required"=>"Y"),
		"fax"         => array("avail"=>"Y","required"=>"N"),
		"url"         => array("avail"=>"Y","required"=>"N"),
		'department'  => array('avail'=>'Y','required'=>'Y'),
	);

	if ($config["General"]["use_counties"] != "Y") {
		#
		# Disable county usage
		#
		$default_user_profile_fields["b_county"]["avail"] = "N";
		$default_user_profile_fields["b_county"]["required"] = "N";
		$default_user_profile_fields["s_county"]["avail"] = "N";
		$default_user_profile_fields["s_county"]["required"] = "N";
		$default_contact_us_fields["b_county"]["avail"] = "N";
		$default_contact_us_fields["b_county"]["required"] = "N";
	}

	$taxes_units = array(
		"ST"  => "lbl_subtotal",
		"DST" => "lbl_discounted_subtotal",
		"SH"  => "lbl_shipping_cost"
	);

	#
	# Unserialize & Assign Right-to-Left languages
	#
	if ($config["r2l_languages"])
		$config["r2l_languages"] = unserialize ($config["r2l_languages"]);

	#
	# Unserialize & Assign card types
	#
	if ($config["card_types"])
		$config["card_types"] = unserialize ($config["card_types"]);

	$smarty->assign ("card_types", $config["card_types"]);

	#
	# Include webmaster mode
	#
	@include_once($xcart_dir."/include/webmaster.php");

	x_session_register("editor_mode");
	if($config["General"]["enable_debug_console"]=="Y" || $editor_mode=='editor')
		$smarty->debugging=true;

	#
	# IP addresses
	#
	$smarty->assign("PROXY_IP",$PROXY_IP);
	$smarty->assign("CLIENT_IP",$CLIENT_IP);
	$smarty->assign("REMOTE_ADDR",$REMOTE_ADDR);
	$mail_smarty->assign("PROXY_IP",$PROXY_IP);
	$mail_smarty->assign("CLIENT_IP",$CLIENT_IP);
	$mail_smarty->assign("REMOTE_ADDR",$REMOTE_ADDR);


    // Disable Clean URLs functionality if a request is performed by the HTML Catalog generator script.
    if (defined('IS_ROBOT') && defined('ROBOT') && constant('ROBOT') == 'X-Cart Catalog Generator') {
        $config['SEO']['clean_urls_enabled'] = 'N';
    }

	#
	# Search engine bots & spiders identificator
	#

	@include_once($xcart_dir."/include/bots.php");

	#
	# Adaptives section
	#
	@include_once($xcart_dir."/include/adaptives.php");

}

#
# Read Modules and put in into $active_modules
#
$import_specification = array();
$active_modules = func_data_cache_get("modules");

$addons = array();
$body_onload = "";
$tbl_demo_data = $tbl_keys = array();
if ($active_modules) {
    if (!empty($active_modules['Multiple_Storefronts'])) {
		if (file_exists($xcart_dir . '/modules/Multiple_Storefronts/config.php')) {
			include $xcart_dir . '/modules/Multiple_Storefronts/config.php';
        }

		if (file_exists($xcart_dir . '/modules/Multiple_Storefronts/func.php')) {
			include $xcart_dir . '/modules/Multiple_Storefronts/func.php';
        }
    }
	foreach ($active_modules as $active_module => $tmp) {
        if ($active_module != 'Multiple_Storefronts') {
    		if (file_exists($xcart_dir."/modules/".$active_module."/config.php"))
	    		include $xcart_dir."/modules/".$active_module."/config.php";

		    if (file_exists($xcart_dir."/modules/".$active_module."/func.php"))
			    include $xcart_dir."/modules/".$active_module."/func.php";
        }
	}
}

#
##
###
if (empty($active_modules["CIDEV_Best_Search_Filter"]) && $current_area != 'C'){
	include $xcart_dir."/modules/CIDEV_Best_Search_Filter/config.php";

}
###
##
#

$smarty->assign_by_ref("active_modules", $active_modules);
$mail_smarty->assign_by_ref("active_modules", $active_modules);

$config['setup_images'] = func_data_cache_get("setup_images");
foreach ($config['available_images'] as $k => $v) {
	if (isset($config['setup_images'][$k]))
		continue;

	$config['setup_images'][$k] = array (
		"itype" => $k,
		"location" => "DB",
		"save_url" => "",
		"size_limit" => 0,
		"md5_check" => "",
		"default_image" => "./default_image.gif"
	);
}

#
# If Antibot turned off after it was loaded
#
if (empty($active_modules['Image_Verification'])) {
	x_session_unregister("antibot_validation_val");
}
if (!defined("QUICK_START")) {

	#
	# Assign config array to smarty
	#
	$smarty->assign("config",$config);
	$mail_smarty->assign("config",$config);

	#
	# Assign Smarty delimiters
	#
	$smarty->assign("ldelim","{");
	$mail_smarty->assign("ldelim","{");
	$smarty->assign("rdelim","}");
	$mail_smarty->assign("rdelim","}");

	if ((isset($_GET['delimiter']) && $_GET['delimiter'] == 'tab') || (isset($_POST['delimiter']) && $_POST['delimiter'] == 'tab')) {
		$delimiter = "\t";
    }

	// Assign email regular expression
    $smarty->assign('clean_url_validation_regexp',     func_clean_url_validation_regexp());
}

#
# Init modules
#
if (is_array($active_modules)) {
	foreach ($active_modules as $__k=>$__v) {
		if (file_exists($xcart_dir."/modules/".$__k."/init.php")) {
			include $xcart_dir."/modules/".$__k."/init.php";
	}
	}
}

#
# UPC/EAN/ISBN length
#

define('UPC_LENGTH', 12);
$smarty->assign('UPC_LENGTH', UPC_LENGTH);

define('ISBN_LENGTH', 10);
$smarty->assign('ISBN_LENGTH', ISBN_LENGTH);

define('EAN_ISBN_LENGTH', 13);
$smarty->assign('EAN_ISBN_LENGTH', EAN_ISBN_LENGTH);

#
# Accounting columns
#
define('ACC_NET', 0);
define('ACC_COST_TO_US', 1);
define('ACC_SHIPPING', 2);
define('ACC_REF_TO_CUST', 3);
$smarty->assign('ACC_REF_TO_CUST', ACC_REF_TO_CUST);
define('ACC_REF_TO_US', 4);

#
# "Purchase Order" payment method id
#
define('PURCHASE_ORDER_PAYMENTID', 2);

#
# Froogle title length
#

define('FROOGLE_TITLE_LENGTH', 70);
$smarty->assign('FROOGLE_TITLE_LENGTH', FROOGLE_TITLE_LENGTH);

#
# Prepare map bridge text
#
$smarty->assign('map_bridge_mouseover_text', str_replace("\r", '<br />', $config['Product_Page']['map_bridge_mouseover_text']));

#
# Clean temporary data
#
if ((rand() % 10) == 0) {
	db_query("DELETE FROM $sql_tbl[temporary_data] WHERE expire<UNIX_TIMESTAMP(NOW())");
}

#
# Remember visitor for a long time period
#
$remember_user = true;

#
# Time period for which user info should be stored (days)
#
$remember_user_days = 30;

#
##
###
$linked_out_category_indexes = array("1","2","3","4","5","6");
$smarty->assign("linked_out_category_indexes", $linked_out_category_indexes);
###
##
#

#
# WARNING !
# Please ensure that you have no whitespaces / empty lines below this message.
# Adding a whitespace or an empty line below this line will cause a PHP error.
#
?>
