<?php

#
# X-Cart initialization
#

if (!defined('XCART_START')) {
    header("Location: index.php");
    die("Access denied");
}

if (empty($_SERVER['SERVER_NAME']) && !empty($_SERVER['HTTP_HOST'])) {
    $s_name_a               = explode(':', $_SERVER['HTTP_HOST']);
    $_SERVER['SERVER_NAME'] = $s_name_a[0];
}

@require_once $xcart_dir . "/prepare.php";

$bench1 = func_microtime();

if (func_version_compare(phpversion(), "5.3.0") >= 0) {
    define('X_PHP530_COMPAT', true);
}

if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set')) {
    @date_default_timezone_set(@date_default_timezone_get());
}

if (!@is_readable($xcart_dir . "/config.php")) {
    echo "Can't read config!";
    exit;
}
@require_once $xcart_dir . "/config.php";
@include_once $xcart_dir . "/config.local.php";

# Main storefront: domain
define('DEFAULT_SF_DOMAIN', 'www.artistsupplysource.com');

if (defined('LOCAL_SF_DOMAIN')) {
    define('MAIN_SF_DOMAIN', LOCAL_SF_DOMAIN);
}
else {
    define('MAIN_SF_DOMAIN', DEFAULT_SF_DOMAIN);
}

if (defined('CIDEV_CRON_START') && CIDEV_CRON_START == "CRON") {

    if (empty($_SERVER['HTTP_HOST'])) {
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] = MAIN_SF_DOMAIN;
    }

    if (empty($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_FILENAME'];
    }
}

$cur_host = $_SERVER['HTTP_HOST'];
$cur_url  = $_SERVER['REQUEST_URI'];
if ($cur_host == 'www.kolinskyartbrushes.com') {
    $new_url = ((!empty($HTTPS)) ? 'https://' : 'http://') . 'www.artistsupplysource.com' . $cur_url;
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $new_url);
    exit();
}


$settings_path = $xcart_dir .'/app/config/settings_admin.php';
if (!defined('AREA_TYPE') || AREA_TYPE == 'C') {
    $settings_path = $xcart_dir .'/app/config/settings.php';
}

\Xcart\App\Main\Xcart::init(include $settings_path);
\Xcart\App\Main\Xcart::app()->beforeRun();

#
# Initialize logging
#
@require_once $xcart_dir . "/include/logging.php";
$dieError = "Sorry, the shop is inaccessible temporarily. Please try again later.";
try {
    Xcart\Connection::getInstanceFromApp()->connect();
}
catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
    x_log_add('SQL', $e->getMessage(), true);
    die($dieError);
}
catch (\Exception $e) {
    x_log_add('php', $e->getMessage(), true);
    die($dieError);
}

$file_temp_dir = $var_dirs["tmp"];

#
# Product thumbnail properties
#

define('THUMB_BGCOLOR', 0xFFFFFF);
define('THUMB_QUALITY', 97);

#
# SQL tables aliases...
#
$sql_tbl = [
    "avs_codes"                               => "xcart_avs_codes",
    "benchmark_pages"                         => "xcart_benchmark_pages",
    "brands"                                  => "xcart_brands",
    "brands_lng"                              => "xcart_brands_lng",
    "categories"                              => "xcart_categories",
    "categories_parents"                      => "xcart_categories_parents",
    "categories_subcount"                     => "xcart_categories_subcount",
    "categories_lng"                          => "xcart_categories_lng",
    "category_memberships"                    => "xcart_category_memberships",
    "cc_gestpay_data"                         => "xcart_cc_gestpay_data",
    "cc_pp3_data"                             => "xcart_cc_pp3_data",
    "ccprocessors"                            => "xcart_ccprocessors",
    "chprocessors"                            => "xcart_chprocessors",
    "config"                                  => "xcart_config",
    "contact_fields"                          => "xcart_contact_fields",
    "contact_field_values"                    => "xcart_contact_field_values",
    "counters"                                => "xcart_counters",
    "counties"                                => "xcart_counties",
    "countries"                               => "xcart_countries",
    "country_currencies"                      => "xcart_country_currencies",
    "currencies"                              => "xcart_currencies",
    "customers"                               => "xcart_customers",
    "delivery"                                => "xcart_delivery",
    'departments'                             => 'xcart_departments',
    "discount_coupons"                        => "xcart_discount_coupons",
    "discount_coupons_login"                  => "xcart_discount_coupons_login",
    "discounts"                               => "xcart_discounts",
    "discount_memberships"                    => "xcart_discount_memberships",
    "download_keys"                           => "xcart_download_keys",
    "export_ranges"                           => "xcart_export_ranges",
    "extra_fields"                            => "xcart_extra_fields",
    "extra_fields_lng"                        => "xcart_extra_fields_lng",
    "extra_field_values"                      => "xcart_extra_field_values",
    "featured_products"                       => "xcart_featured_products",
    "fedex_rates"                             => "xcart_fedex_rates",
    "fedex_zips"                              => "xcart_fedex_zips",
    "filter_presets"                          => "xcart_filter_presets",
    "filter_preset_distributors"              => "xcart_filter_preset_distributors",
    "filter_preset_statuses"                  => "xcart_filter_preset_statuses",
    "gcheckout_orders"                        => "xcart_gcheckout_orders",
    "gcheckout_restrictions"                  => "xcart_gcheckout_restrictions",
    "ge_products"                             => "xcart_ge_products",
    "giftcerts"                               => "xcart_giftcerts",
    "images_A"                                => "xcart_images_A",
    "images_B"                                => "xcart_images_B",
    "images_C"                                => "xcart_images_C",
    "images_D"                                => "xcart_images_D",
    "images_F"                                => "xcart_images_F",
    "images_M"                                => "xcart_images_M",
    "images_P"                                => "xcart_images_P",
    "images_R"                                => "xcart_images_R",
    "images_S"                                => "xcart_images_S",
    "images_T"                                => "xcart_images_T",
    "images_W"                                => "xcart_images_W",
    "import_cache"                            => "xcart_import_cache",
    "languages"                               => "xcart_languages",
    "languages_alt"                           => "xcart_languages_alt",
    "login_history"                           => "xcart_login_history",
    "manufacturers"                           => "xcart_manufacturers",
    "manufacturers_lng"                       => "xcart_manufacturers_lng",
    "memberships"                             => "xcart_memberships",
    "memberships_lng"                         => "xcart_memberships_lng",
    "modules"                                 => "xcart_modules",
    "newsletter"                              => "xcart_newsletter",
    "newslist_subscription"                   => "xcart_newslist_subscription",
    "newslists"                               => "xcart_newslists",
    "old_passwords"                           => "xcart_old_passwords",
    "order_details"                           => "xcart_order_details",
    "order_groups"                            => "xcart_order_groups",
    "order_extras"                            => "xcart_order_extras",
    'order_statuses'                          => 'xcart_order_statuses',
    'order_status_notifications'              => 'xcart_order_status_notifications',
    "orders"                                  => "xcart_orders",
    "pages"                                   => "xcart_pages",
    "payment_methods"                         => "xcart_payment_methods",
    "pc_markup_memberships"                   => "xcart_pc_markup_memberships",
    "php_sessions"                            => "xcart_php_sessions",
    "pmethod_memberships"                     => "xcart_pmethod_memberships",
    "pricing"                                 => "xcart_pricing",
    "product_bookmarks"                       => "xcart_product_bookmarks",
    'product_files'                           => 'xcart_product_files',
    "product_links"                           => "xcart_product_links",
    "product_memberships"                     => "xcart_product_memberships",
    "product_reviews"                         => "xcart_product_reviews",
    "product_taxes"                           => "xcart_product_taxes",
    "product_votes"                           => "xcart_product_votes",
    "products"                                => "xcart_products",
    "products_categories"                     => "xcart_products_categories",
    "products_lng"                            => "xcart_products_lng",
    "quick_flags"                             => "xcart_quick_flags",
    "quick_prices"                            => "xcart_quick_prices",
    "referers"                                => "xcart_referers",
    'refund_groups'                           => 'xcart_refund_groups',
    'refunded_products'                       => 'xcart_refunded_products',
    "register_fields"                         => "xcart_register_fields",
    "register_field_values"                   => "xcart_register_field_values",
    'replacements'                            => 'xcart_replacements',
    'seed_categories'                         => 'xcart_seed_categories',
    "sessions_data"                           => "xcart_sessions_data",
    "setup_images"                            => "xcart_setup_images",
    "shipping"                                => "xcart_shipping",
    "shipping_cache"                          => "xcart_shipping_cache",
    "shipping_options"                        => "xcart_shipping_options",
    "shipping_rates"                          => "xcart_shipping_rates",
    "states"                                  => "xcart_states",
    "stats_adaptive"                          => "xcart_stats_adaptive",
    "stats_cart_funnel"                       => "xcart_stats_cart_funnel",
    "stats_customers_products"                => "xcart_stats_customers_products",
    "stats_pages"                             => "xcart_stats_pages",
    "stats_pages_paths"                       => "xcart_stats_pages_paths",
    "stats_pages_views"                       => "xcart_stats_pages_views",
    "stats_search"                            => "xcart_stats_search",
    "stats_shop"                              => "xcart_stats_shop",
    "subscription_customers"                  => "xcart_subscription_customers",
    "subscriptions"                           => "xcart_subscriptions",
    "tax_rate_memberships"                    => "xcart_tax_rate_memberships",
    "tax_rates"                               => "xcart_tax_rates",
    "taxes"                                   => "xcart_taxes",
    "temporary_data"                          => "xcart_temporary_data",
    "titles"                                  => "xcart_titles",
    "tracking_links"                          => "xcart_tracking_links",
    "wishlist"                                => "xcart_wishlist",
    "users_online"                            => "xcart_users_online",
    "zip_code_info"                           => "xcart_zip_code_info",
    "zone_element"                            => "xcart_zone_element",
    "zones"                                   => "xcart_zones",
    "geo_litecity_blocks"                     => "xcart_geo_litecity_blocks",
    "geo_litecity_location"                   => "xcart_geo_litecity_location",
    "clean_urls"                              => "xcart_clean_urls",
    "clean_urls_history"                      => "xcart_clean_urls_history",
    "Telephone_area_codes"                    => "xcart_Telephone_area_codes",
    "distributor_contacts"                    => "xcart_distributor_contacts",
    "info_pages_categories"                   => "xcart_info_pages_categories",
    "info_pages"                              => "xcart_info_pages",
    "info_pages_subcount"                     => "xcart_info_pages_subcount",
    "links_to_distributor_invoices"           => "xcart_links_to_distributor_invoices",
    "templates_for_communication"             => "xcart_templates_for_communication",
    "request_availability_options"            => "xcart_request_availability_options",
    "order_logs"                              => "xcart_order_logs",
    "fraud_check"                             => "xcart_fraud_check",
    "order_fraud_checks"                      => "xcart_order_fraud_checks",
    "filter_preset_fraud_statuses"            => "xcart_filter_preset_fraud_statuses",
    "order_fraud_statuses"                    => "xcart_order_fraud_statuses",
    "order_additional_fee"                    => "xcart_order_additional_fee",
    "distributor_return_address"              => "xcart_distributor_return_address",
    "notify_when_in_stock"                    => "xcart_notify_when_in_stock",
    "reconciliation_search_keyphrases"        => "xcart_reconciliation_search_keyphrases",
    "supplier_product_feeds"                  => "xcart_supplier_product_feeds",
    "filter_preset_ship_to_country"           => "xcart_filter_preset_ship_to_country",
    "seo_categories_keyphrases"               => "xcart_seo_categories_keyphrases",
    "reconciliations"                         => "xcart_reconciliations",
    "reconciliation_upload_info"              => "xcart_reconciliation_upload_info",
    "bpu_rows"                                => "xcart_bpu_rows",
    "bpu_result"                              => "xcart_bpu_result",
    "search_stats"                            => "xcart_search_stats",
    "attention_tags_values"                   => "xcart_attention_tags_values",
    "attention_tags_values_logins"            => "xcart_attention_tags_values_logins",
    "orders_additional_tags"                  => "xcart_orders_additional_tags",
    "filter_preset_attention_tag_statuses"    => "xcart_filter_preset_attention_tag_statuses",
    "approximation_shipping_rates"            => "xcart_approximation_shipping_rates",
    "pc_options"                              => "xcart_pc_options",
    "pc_terms"                                => "xcart_pc_terms",
    "pc_category_terms"                       => "xcart_pc_category_terms",
    "pc_locks"                                => "xcart_pc_locks",
    "pc_runs_log"                             => "xcart_pc_runs_log",
    "otrs_options"                            => "xcart_otrs_options",
    "shipping_quote_log"                      => "xcart_shipping_quote_log",
    "shipping_quote_products_log"             => "xcart_shipping_quote_products_log",
    "ab_testing_points"                       => "xcart_ab_testing_points",
    "ab_point_variants"                       => "xcart_ab_point_variants",
    "froogle_options"                         => "xcart_froogle_options",
    "backprocess_logs"                        => "xcart_backprocess_logs",
    "order_page_permissions"                  => "xcart_order_page_permissions",
    "inquiry_types"                           => "xcart_inquiry_types",
    "inquiries_attention_tags"                => "xcart_inquiries_attention_tags",
    "inquiries"                               => "xcart_inquiries",
    "inquirires_tags"                         => "xcart_inquirires_tags",
    "ground_map"                              => "xcart_ground_map",
    "supplier_feeds"                          => "xcart_supplier_feeds",
    "products_amz_fields"                     => "xcart_products_amz_fields",
    "cidev_amazon_order_raw"                  => "xcart_cidev_amazon_order_raw",
    "pbx_options"                             => "xcart_pbx_options",
    "product_question"                        => "xcart_product_question",
    "filter_preset_product_question_statuses" => "xcart_filter_preset_product_question_statuses",
    "filter_preset_storefronts"               => "xcart_filter_preset_storefronts",
    "cidev_amazon_fba_products"               => "xcart_cidev_amazon_fba_products",
    "off_hours_messages"                      => "xcart_off_hours_messages",
    "links_to_distributor_memos"              => "xcart_links_to_distributor_memos",
    "order_group_memos"                       => "xcart_order_group_memos",
    "order_group_invoices"                    => "xcart_order_group_invoices",
    "order_group_invoices_products"           => "xcart_order_group_invoices_products",
    "tracking_links_carrier"                  => "xcart_tracking_links_carrier",
    "cidev_surf_meta"                         => "xcart_cidev_surf_meta",
    "cidev_surf_path"                         => "xcart_cidev_surf_path",
    "related_objects_collector"               => "xcart_related_objects_collector",
    "cidev_related_objects"                   => "xcart_cidev_related_objects",
    "checks_deposited"                        => "xcart_checks_deposited",
    "checks_deposited_orders"                 => "xcart_checks_deposited_orders",
    "rmas"                                    => "xcart_rmas",
    "rma_statuses"                            => "xcart_rma_statuses",
    "rma_details"                             => "xcart_rma_details",
    "rma_would_like_variants"                 => "xcart_rma_would_like_variants",
    "manufacturer_feed_fields"                => "xcart_manufacturer_feed_fields",
    "cidev_updated_products"                  => "xcart_cidev_updated_products",
    "transaction_logs"                        => "xcart_transaction_logs",
    "order_transactions"                      => "xcart_order_transactions",
    "clone_products_queue"                    => "xcart_clone_products_queue",
    "product_verification_statuses"           => "xcart_product_verification_statuses",
    "product_verification_history"            => "xcart_product_verification_history",
    "product_htmlshot"                        => "xcart_product_htmlshot",
    "cidev_otrs_new_message_rules"            => "xcart_cidev_otrs_new_message_rules",
    "order_amazon_details"                    => "xcart_order_amazon_details",
    "products_external_marketplaces"          => "xcart_products_external_marketplaces",
    "products_disabled_marketplaces"          => "xcart_products_disabled_marketplaces",
    "storefronts_external_marketplaces"       => "xcart_storefronts_external_marketplaces",
    "po_pipeline"                             => "xcart_po_pipeline",
    "logs"                                    => "xcart_logs",
    "filter_preset_po_statuses"               => "xcart_filter_preset_po_statuses",
    "secure_data"                             => "xcart_secure_data",
    "secure_data_users"                       => "xcart_secure_data_users",
    "products_amazon_rates"                   => "xcart_products_amazon_rates",
    "external_verification_batches"           => "xcart_external_verification_batches",
    "external_verification_products"          => "xcart_external_verification_products",
    "external_verification_products_queue"    => "xcart_external_verification_products_queue",
    "cidev_gmc_quality_issues"                => "xcart_cidev_gmc_quality_issues",
    "cidev_issues_processing_rules"           => "xcart_cidev_issues_processing_rules",
    "products_upc_changes"                    => "xcart_products_upc_changes",
    "locks"                                   => "xcart_locks",
    "fba_missing_sku"                         => "xcart_fba_missing_sku",
    "storefronts_config"                      => "xcart_storefronts_config",
    "shipping_carrier"                        => "xcart_shipping_carrier",
    "shipping_cache_products"                 => "xcart_shipping_cache_products",
    "shipping_cache_simple"                   => "xcart_shipping_cache_simple",
    "fba_inventory_receipts"                  => "xcart_fba_inventory_receipts",
    "fba_roi_accounting"                      => "xcart_fba_roi_accounting",
    "order_cx_invoices"                       => "xcart_order_cx_invoices",
    "external_verification_feeds"             => "xcart_external_verification_feeds",
    "shipping_cache_quotes"                   => "xcart_shipping_cache_quotes",
    "images_splash"                           => "xcart_images_splash",
];

$price_details_names = ["net", "gst", "pst", "gross"];

define('FILTER_PRESET_PER_ROW', 5);

# Artist Supply Source distributor credentials

$artss_manufacturerid = 3;
$artss_code           = 'ART';

#
# Redefine error_reporting option
#
error_reporting($x_error_reporting);

#
# Multi Storefront
#
if (!empty($current_storefront)) {
    @include_once $xcart_dir . 'modules/Multiple_Storefronts/sf_config.php';
}

#
# Include functions
#

include_once($xcart_dir . "/include/bench.php");

#
# Connect to database
#
/*$db_connect_limit = 5;
while ($db_connect_limit-- > 0 && !@db_connect($sql_host, $sql_user, $sql_password)) { }
db_select_db($sql_db) || die("Sorry, the shop is inaccessible temporarily. Please try again later.");*/

$tmp = func_query_first("SHOW VARIABLES LIKE 'max_allowed_packet'");
$sql_max_allowed_packet = intval($tmp['Value']);
unset($tmp);

if (preg_match("/^(\d+\.\d+\.\d+)/", db_mysql_get_server_info(), $match)) {
        define("X_MYSQL_VERSION", $match[1]);

    if (func_version_compare(X_MYSQL_VERSION, "5.0.0") >= 0) {
        db_query("SET sql_mode = 'MYSQL40'");
    }

    if (func_version_compare(X_MYSQL_VERSION, "5.0.17") > 0) {
        define("X_MYSQL5_COMP_MODE", true);
    }

    if (func_version_compare(X_MYSQL_VERSION, "5.0.18") == 0) {
        define("X_MYSQL5018_COMP_MODE", true);
    }
}

#
## Set the session name here
###

$cidev_tmp_storefrontid = func_query_first_cell("SELECT storefrontid FROM xcart_storefronts WHERE domain='{$_SERVER['HTTP_HOST']}'");

if (empty($cidev_tmp_storefrontid)) {
    $cidev_tmp_storefrontid = '0';
}
else {
    $xcart_http_host  = $_SERVER["HTTP_HOST"];
    $xcart_https_host = $_SERVER["HTTP_HOST"];
}

//$XCART_SESSION_NAME = "xid" . $cidev_tmp_storefrontid;

#
# HTTP & HTTPS locations
#
$http_location  = "http://$xcart_http_host" . $xcart_web_dir;
$https_location = "https://$xcart_https_host" . $xcart_web_dir;

#
# Fix broken path for some hostings
#
$current_location = $HTTPS ? $https_location : $http_location;
if (isset($last_current_location) && $last_current_location) {
    if (preg_match("/kolinskyartbrushes.com/i", $HTTP_REFERER) || preg_match("/kolinskyartbrushes.com/i", $last_current_location)) {
        $current_location = str_replace($HTTPS ? $xcart_https_host : $xcart_http_host, "kolinskyartbrushes.com", $current_location);
    }
    elseif (preg_match("/artistssupplysource.com/i", $HTTP_REFERER) || preg_match("/artistssupplysource.com/i", $last_current_location)) {
        $current_location = str_replace($HTTPS ? $xcart_https_host : $xcart_http_host, "artistssupplysource.com", $current_location);
    }
}

$_tmp          = parse_url($current_location);
$xcart_web_dir = empty($_tmp["path"]) ? "" : $_tmp["path"];

if ($HTTPS_RELAY) {

    # Fix wrong PHP_SELF for HTTPS relay
    $_tmp = parse_url($http_location);
    if (empty($_tmp['path'])) {
        $PHP_SELF = $xcart_web_dir . $PHP_SELF;
    }
    else {
        $PHP_SELF = $xcart_web_dir . preg_replace("/^" . preg_quote($_tmp['path'], "/") . "/", "", $PHP_SELF);
    }

    $_SERVER['PHP_SELF'] = $PHP_SELF;
}

$_tmp             = parse_url($https_location);
$xcart_https_host = $_tmp["host"];
unset($_tmp);
$_tmp            = parse_url($http_location);
$xcart_http_host = $_tmp["host"];
unset($_tmp);

#
# Create URL
#
$php_url = ["url" => "http" . ($HTTPS == "on" ? "s://" . $xcart_https_host : "://" . $xcart_http_host) . $PHP_SELF, "query_string" => $QUERY_STRING];

#
# Check internal temporary directories
#
$var_dirs_rules = [
    "cache"       => [
        ".htaccess" => "Deny from all\n<files \"*.js\">\nAllow from all\n</files>",
    ],
    "tmp"         => [
        ".htaccess" => "Deny from all",
    ],
    "templates_c" => [
        ".htaccess" => "Deny from all",
    ],
    "upgrade"     => [
        ".htaccess" => "Deny from all",
    ],
    "log"         => [
        ".htaccess" => "Deny from all",
    ],
];

foreach ($var_dirs as $k => $v) {
    if (!file_exists($v) || !is_dir($v)) {
        @unlink($v);
        @func_mkdir($v);
    }

    if (!is_writable($v) || !is_dir($v)) {
        echo "Can't write data to the temporary directory: <b>" . $v . "</b>.<br />Please check if it exists, and have writable permissions.";
        exit;
    }

    foreach ($var_dirs_rules[$k] as $f => $c) {
        if (file_exists($v . "/" . $f)) {
            continue;
        }

        if ($__fp = @fopen($v . "/" . $f, "w")) {
            @fwrite($__fp, $c);
            @fclose($__fp);
        }
    }
}



#
# Create Smarty object
#
if (!@include $xcart_dir . "/smarty.php") {
    echo "Can't launch template engine!";
    exit;
}

$smarty->assign('xcartApp',\Xcart\App\Main\Xcart::app());
#
# Init miscellaneous vars
#
$smarty->assign("skin_config", $skin_config_file);
$mail_smarty->assign("skin_config", $skin_config_file);

$smarty->assign("http_location", $http_location);
$mail_smarty->assign("http_location", $http_location);
$smarty->assign("https_location", $https_location);
$mail_smarty->assign("https_location", $https_location);
$smarty->assign("xcart_web_dir", $xcart_web_dir);
$smarty->assign("current_location", $current_location);
$smarty->assign("php_url", $php_url);

# START: random:20341 [2010 Jul 29 14:46] 
$smarty->assign("artss_manufacturerid", $artss_manufacturerid);
$smarty->assign("artss_code", $artss_code);

# END: random:20341 [2010 Jul 29 14:46] 
foreach ($var_dirs_web as $k => $v) {
    $var_dirs_web[$k] = $current_location . $v;
}

$smarty->assign_by_ref("var_dirs_web", $var_dirs_web);

$xcart_catalogs = [
    "admin"       => $current_location . DIR_ADMIN,
    "customer"    => $current_location . DIR_CUSTOMER,
    "provider"    => $current_location . DIR_PROVIDER,
    "verificator" => $current_location . DIR_VERIFICATOR,
    "partner"     => $current_location . DIR_PARTNER,
];

$xcart_catalogs_secure = [
    "admin"       => $https_location . DIR_ADMIN,
    "customer"    => $https_location . DIR_CUSTOMER,
    "provider"    => $https_location . DIR_PROVIDER,
    "verificator" => $https_location . DIR_VERIFICATOR,
    "partner"     => $https_location . DIR_PARTNER,
];

$smarty->assign("catalogs", $xcart_catalogs);
$smarty->assign("catalogs_secure", $xcart_catalogs_secure);
$mail_smarty->assign("catalogs", $xcart_catalogs);
$mail_smarty->assign("catalogs_secure", $xcart_catalogs_secure);

#
# Files directories
#
$files_dir_name      = $xcart_dir . $files_dir;
$files_http_location = $http_location . $files_webdir;
$smarty->assign("files_location", $files_dir_name);

$templates_repository = $xcart_dir . $templates_repository_dir;

#
# Set MySQL variable 'max_join_size'
#
$mjsize = func_query_first("SHOW VARIABLES LIKE 'max_join_size'");
if (intval($mjsize['Value']) < 1073741824) {
    db_query("SET OPTION SQL_MAX_JOIN_SIZE=1073741824");
}
unset($mjsize);

#
# Read config variables from Database
# This variables are used inside php scripts, not in smarty templates
#
$c_result = db_query("SELECT name, value, category FROM $sql_tbl[config] WHERE type != 'separator'");
$config   = [];
if ($c_result) {
    while ($row = db_fetch_row($c_result)) {
        if (!empty($row[2])) {
            $config[$row[2]][$row[0]] = $row[1];
        }
        else {
            $config[$row[0]] = $row[1];
        }
    }
}

db_free_result($c_result);

$config["Sessions"]["session_length"] = $use_session_length;

#
# Include data cache functionality
#
@include_once($xcart_dir . "/include/data_cache.php");

#
# Timezone offset (sec) = N hours x 60 minutes x 60 seconds
#
$config["Appearance"]["timezone_offset"] = intval($config["Appearance"]["timezone_offset"]) * 3600;

#
# Set timezone
#
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('EST');
}

#
# Define 'End year' for date selectors in the templates
#
$config["Company"]["end_year"] = date("Y", time() + $config["Appearance"]["timezone_offset"]);

#
# Last database backup date
#
if ($config["db_backup_date"]) {
    $config["db_backup_date"] += $config["Appearance"]["timezone_offset"];
}

$config['available_images']['A']  = "U";
$config['available_images']['T']  = "U";
$config['available_images']['P']  = "U";
$config['available_images']['C']  = "U";
$config['available_images']['R']  = "M";
$config['substitute_images']['P'] = "T";

$httpsmod_active = null;
if (!defined("QUICK_START")) {
    if (empty($config["Appearance"]["thumbnail_width"])) {
        $config["Appearance"]["thumbnail_width"] = 0;
    }

    if (empty($config["Appearance"]["date_format"])) {
        $config["Appearance"]["date_format"] = "%d-%m-%Y";
    }

    $config["Appearance"]["datetime_format"]
        = $config["Appearance"]["date_format"] . " " . $config["Appearance"]["time_format"];
}

#
# Prepare session
#
@include_once $xcart_dir . "/include/sessions.php";
@include_once $xcart_dir . "/include/unallowed_request.php";

if (!defined('QUICK_START')) {
    @include_once($xcart_dir . "/include/blowfish.php");

    #
    # Start Blowfish class
    #
    $blowfish = new ctBlowfish();
}

$t                      = parse_url($config['Search_All']['search_all_website_url']);
$search_all_website_url = $t['host'];
$search_all_website_url = ltrim($search_all_website_url, 'www.');
$cur_host               = ltrim($cur_host, 'www.');
if (!$search_all_website && strcasecmp($cur_host, $search_all_website_url) == 0 && AREA_TYPE == 'C') {
//    func_header_location('index.php');
    include_once $xcart_dir . "/index.php";
    die();
}

#
# Prepare number variables
#
@include_once $xcart_dir . "/include/number_conv.php";

if (!defined("QUICK_START")) {
    #
    # Define default user profile fields
    #
    $default_user_profile_fields = [
        "title"       => ["avail" => "Y", "required" => "Y"],
        "firstname"   => ["avail" => "Y", "required" => "Y"],
        "lastname"    => ["avail" => "Y", "required" => "Y"],
        "company"     => ["avail" => "Y", "required" => "N"],
        "ssn"         => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'Y', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'Y', "C" => 'N', "H" => "N"],
        ],
        "tax_number"  => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'Y', "H" => "Y"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "b_title"     => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "b_firstname" => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "b_lastname"  => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "b_address"   => ["avail" => "Y", "required" => "Y"],
        "b_address_2" => ["avail" => "Y", "required" => "N"],
        "b_city"      => ["avail" => "Y", "required" => "Y"],
        "b_county"    => ["avail" => "Y", "required" => "Y"],
        "b_state"     => ["avail" => "Y", "required" => "Y"],
        "b_country"   => ["avail" => "Y", "required" => "Y"],
        "b_zipcode"   => ["avail" => "Y", "required" => "Y"],
        "s_title"     => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "s_firstname" => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "s_lastname"  => [
            "avail"    => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
            "required" => ["A" => 'N', "P" => 'N', "B" => 'N', "C" => 'N', "H" => "N"],
        ],
        "s_address"   => ["avail" => "Y", "required" => "N"],
        "s_address_2" => ["avail" => "Y", "required" => "N"],
        "s_city"      => ["avail" => "Y", "required" => "N"],
        "s_county"    => ["avail" => "Y", "required" => "N"],
        "s_state"     => ["avail" => "Y", "required" => "N"],
        "s_country"   => ["avail" => "Y", "required" => "N"],
        "s_zipcode"   => ["avail" => "Y", "required" => "N"],
        "phone"       => ["avail" => "Y", "required" => "Y"],
        "email"       => ["avail" => "Y", "required" => "Y"],
        "fax"         => ["avail" => "Y", "required" => "N"],
        "url"         => ["avail" => "Y", "required" => "N"],
    ];

    #
    # Define default contact us fields
    #
    $default_contact_us_fields = [
        "username"    => ["avail" => "Y", "required" => "Y"],
        "title"       => ["avail" => "Y", "required" => "Y"],
        "firstname"   => ["avail" => "Y", "required" => "Y"],
        "lastname"    => ["avail" => "Y", "required" => "Y"],
        "company"     => ["avail" => "Y", "required" => "N"],
        "b_address"   => ["avail" => "Y", "required" => "Y"],
        "b_address_2" => ["avail" => "Y", "required" => "N"],
        "b_city"      => ["avail" => "Y", "required" => "Y"],
        "b_county"    => ["avail" => "Y", "required" => "Y"],
        "b_state"     => ["avail" => "Y", "required" => "Y"],
        "b_country"   => ["avail" => "Y", "required" => "Y"],
        "b_zipcode"   => ["avail" => "Y", "required" => "Y"],
        "phone"       => ["avail" => "Y", "required" => "Y"],
        "email"       => ["avail" => "Y", "required" => "Y"],
        "fax"         => ["avail" => "Y", "required" => "N"],
        "url"         => ["avail" => "Y", "required" => "N"],
        'department'  => ['avail' => 'Y', 'required' => 'Y'],
    ];

    if ($config["General"]["use_counties"] != "Y") {
        #
        # Disable county usage
        #
        $default_user_profile_fields["b_county"]["avail"]    = "N";
        $default_user_profile_fields["b_county"]["required"] = "N";
        $default_user_profile_fields["s_county"]["avail"]    = "N";
        $default_user_profile_fields["s_county"]["required"] = "N";
        $default_contact_us_fields["b_county"]["avail"]      = "N";
        $default_contact_us_fields["b_county"]["required"]   = "N";
    }

    $taxes_units = [
        "ST"  => "lbl_subtotal",
        "DST" => "lbl_discounted_subtotal",
        "SH"  => "lbl_shipping_cost",
    ];

    #
    # Unserialize & Assign Right-to-Left languages
    #
    if ($config["r2l_languages"]) {
        $config["r2l_languages"] = unserialize($config["r2l_languages"]);
    }

    #
    # Unserialize & Assign card types
    #
    if ($config["card_types"]) {
        $config["card_types"] = unserialize($config["card_types"]);
    }

    $smarty->assign("card_types", $config["card_types"]);

    #
    # Include webmaster mode
    #
//    @include_once($xcart_dir . "/include/webmaster.php");
//
//    x_session_register("editor_mode");
//    if ($config["General"]["enable_debug_console"] == "Y" || $editor_mode == 'editor') {
//        $smarty->debugging = true;
//    }

    #
    # IP addresses
    #
    $smarty->assign("PROXY_IP", $PROXY_IP);
    $smarty->assign("CLIENT_IP", $CLIENT_IP);
    $smarty->assign("REMOTE_ADDR", $REMOTE_ADDR);
    $mail_smarty->assign("PROXY_IP", $PROXY_IP);
    $mail_smarty->assign("CLIENT_IP", $CLIENT_IP);
    $mail_smarty->assign("REMOTE_ADDR", $REMOTE_ADDR);

    // Disable Clean URLs functionality if a request is performed by the HTML Catalog generator script.
    if (defined('IS_ROBOT') && defined('ROBOT') && constant('ROBOT') == 'X-Cart Catalog Generator') {
        $config['SEO']['clean_urls_enabled'] = 'N';
    }

    $smarty->assign("is_robot", $is_robot);

    #
    # Adaptives section
    #
    @include_once($xcart_dir . "/include/adaptives.php");
}

#
# Read Modules and put in into $active_modules
#
$import_specification = [];
$active_modules       = func_data_cache_get("modules");

$addons        = [];
$body_onload   = "";
$tbl_demo_data = $tbl_keys = [];
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

            if ($active_module == "Xcart_Mobile") {

                if (empty($cidev_tmp_storefrontid)) {
                    $cidev_tmp_Enable_Mobile_skin = $config["Appearance"]["Enable_Mobile_skin"];
                }
                else {
                    $cidev_tmp_Enable_Mobile_skin = func_query_first_cell("SELECT value FROM $sql_tbl[storefronts_config] WHERE name='Enable_Mobile_skin' AND storefrontid='$cidev_tmp_storefrontid'");
                }

                if ($cidev_tmp_Enable_Mobile_skin != "Y") {
                    continue;
                }
            }

            if (file_exists($xcart_dir . "/modules/" . $active_module . "/config.php")) {
                include $xcart_dir . "/modules/" . $active_module . "/config.php";
            }

            if (file_exists($xcart_dir . "/modules/" . $active_module . "/func.php")) {
                include $xcart_dir . "/modules/" . $active_module . "/func.php";
            }
        }
    }
}


if (empty($active_modules["CIDEV_Best_Search_Filter"]) && $current_area != 'C') {
    include $xcart_dir . "/modules/CIDEV_Best_Search_Filter/config.php";
}

$smarty->assign_by_ref("active_modules", $active_modules);
$mail_smarty->assign_by_ref("active_modules", $active_modules);

/* speed optimizations */
$config['setup_images'] = func_data_cache_get("setup_images");
foreach ($config['available_images'] as $k => $v) {
    if (isset($config['setup_images'][$k])) {
        continue;
    }

    $config['setup_images'][$k] = [
        "itype"         => $k,
        "location"      => "DB",
        "save_url"      => "",
        "size_limit"    => 0,
        "md5_check"     => "",
        "default_image" => "./default_image.gif",
    ];
}
#
# If Antibot turned off after it was loaded
#
if (empty($active_modules['Image_Verification'])) {
    x_session_unregister("antibot_validation_val");
}

if (
    $is_robot == "Y"
    || defined("IS_ROBOT")
    || (empty($$XCART_SESSION_NAME) && empty($XCARTSESSID))
) {
    $config["Appearance"]["products_per_page"] = 100;
}

if (!defined("QUICK_START")) {

    #
    # Assign config array to smarty
    #
    $smarty->assign("config", $config);
    $mail_smarty->assign("config", $config);

    #
    # Assign Smarty delimiters
    #
    $smarty->assign("ldelim", "{");
    $mail_smarty->assign("ldelim", "{");
    $smarty->assign("rdelim", "}");
    $mail_smarty->assign("rdelim", "}");

    if ((isset($_GET['delimiter']) && $_GET['delimiter'] == 'tab') || (isset($_POST['delimiter']) && $_POST['delimiter'] == 'tab')) {
        $delimiter = "\t";
    }

    // Assign email regular expression
    $smarty->assign('clean_url_validation_regexp', func_clean_url_validation_regexp());
}

#
# Init modules
#
if (is_array($active_modules)) {
    foreach ($active_modules as $__k => $__v) {
        if (file_exists($xcart_dir . "/modules/" . $__k . "/init.php")) {
            include $xcart_dir . "/modules/" . $__k . "/init.php";
        }
    }
}

if (defined('CIDEV_CRON_START') && CIDEV_CRON_START == "CRON") {

    if (empty($_SERVER['HTTP_HOST'])) {
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] = MAIN_SF_DOMAIN;
    }

    if (empty($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_FILENAME'];
    }
}

#
## Working hours
###
$working_days["monday"]["type"]    = $config["working_hours_monday"];
$working_days["tuesday"]["type"]   = $config["working_hours_tuesday"];
$working_days["wednesday"]["type"] = $config["working_hours_wednesday"];
$working_days["thursday"]["type"]  = $config["working_hours_thursday"];
$working_days["friday"]["type"]    = $config["working_hours_friday"];
$working_days["saturday"]["type"]  = $config["working_hours_saturday"];
$working_days["sunday"]["type"]    = $config["working_hours_sunday"];

foreach ($working_days as $kw => $vw) {
    $working_days[$kw]["from"] = $config["working_hours_" . $kw . "_from"];
    $working_days[$kw]["to"]   = $config["working_hours_" . $kw . "_to"];
}

$smarty->assign("working_days", $working_days);

#
## Google Analitics checkout step names
###
$ga_checkout_step_names = [
    "1" => "Step 1",
    "2" => "Step 2",
    "3" => "Step 3",
#        "4" => "Step 4", // Debug on LIVE site
#        "5" => "Final step"
    "4" => "Final step",
];
$smarty->assign("ga_checkout_step_names", $ga_checkout_step_names);

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

define('FROOGLE_TITLE_LENGTH', 150);
$smarty->assign('FROOGLE_TITLE_LENGTH', FROOGLE_TITLE_LENGTH);

#
# Prepare map bridge text
#
$smarty->assign('map_bridge_mouseover_text', str_replace("\r", '<br />', $config['Product_Page']['map_bridge_mouseover_text']));

#
# Clean temporary data
#
if ((rand() % 100) == 0) {
    db_query("DELETE FROM $sql_tbl[temporary_data] WHERE expire<UNIX_TIMESTAMP(NOW())");
}

#
# Remember visitor for a long time period
#
$remember_user = true;

#
# Time period for which user info should be stored (days)
#
$remember_user_days = 180;

$linked_out_category_indexes = ["1", "2", "3", "4", "5", "6"];
$smarty->assign("linked_out_category_indexes", $linked_out_category_indexes);
$bench2 = func_microtime();

if (false && !function_exists('fn_shutdown')) {
    function fn_shutdown()
    {
        $error = error_get_last();
        x_log_flag('log_debug_messages', 'debug', $error['message'] . ' in file ' . $error['file'], true, 1);
    }

    register_shutdown_function('fn_shutdown');
}

$smarty->register_function('getBanners', ['Xcart\Helpers\Banners', 'getBannerSmarty']);
$smarty->register_function('getSliderData', ['Xcart\Helpers\SliderData', 'getSliderDataSmarty']);

$smarty->assign('recaptcha_enable', $recaptcha_enable);
$smarty->assign('key_recaptcha_public', $key_recaptcha_public);


if (defined("SET_EXPIRE")) {
    header("Expires: " . gmdate("D, d M Y H:i:s", SET_EXPIRE) . " GMT");
}
else {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
#       header("Expires: ".gmdate("D, d M Y H:i:s", time() + 600)." GMT");
}

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

if (defined("SET_EXPIRE")) {
    header("Cache-Control: public");
}
elseif ($HTTPS) {
    header("Cache-Control: private, must-revalidate");
}
else {
    header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    header("Pragma: no-cache");
}

header("Vary: User-Agent");