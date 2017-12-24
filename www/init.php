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

if (empty($XCART_APP_CONFIG)) {
    $settings_path = $xcart_dir .'/../app/config/settings_admin.php';
    if (!defined('AREA_TYPE') || AREA_TYPE == 'C') {
        $settings_path = $xcart_dir .'/../app/config/settings.php';
    }

    $app_settings = include $settings_path;
}
else {
    $app_settings = $XCART_APP_CONFIG;
}

\Xcart\App\Main\Xcart::init($app_settings);
\Xcart\App\Main\Xcart::app()->beforeRun();

if (defined('CIDEV_CRON_START') && CIDEV_CRON_START == "CRON") {

    if (empty($_SERVER['HTTP_HOST'])) {
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] = MAIN_SF_DOMAIN;
    }

    if (empty($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_FILENAME'];
    }
}

#
# Initialize logging
#
@require_once $xcart_dir . "/include/logging.php";
$dieError = "Sorry, the shop is inaccessible temporarily. Please try again later.";

global $sql_tbl;

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
    if (preg_match("/artistssupplysource.com/i", $HTTP_REFERER) || preg_match("/artistssupplysource.com/i", $last_current_location)) {
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

$PHP_SELF = empty($PHP_SELF) ? '' : $PHP_SELF;
$QUERY_STRING = empty($QUERY_STRING) ? '' : $QUERY_STRING;

#
# Create URL
#
$php_url = ["url" => "http" . ($HTTPS == "on" ? "s://" . $xcart_https_host : "://" . $xcart_http_host) . $PHP_SELF ?: '', "query_string" => $QUERY_STRING];

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

$smarty->assign("artss_manufacturerid", $artss_manufacturerid);
$smarty->assign("artss_code", $artss_code);
$smarty->assign("AREA_TYPE", AREA_TYPE);

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
//$mjsize = func_query_first("SHOW VARIABLES LIKE 'max_join_size'");
//if (intval($mjsize['Value']) < 1073741824) {
//    db_query("SET OPTION SQL_MAX_JOIN_SIZE=1073741824");
//}
//unset($mjsize);

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

$search_all_website = isset($search_all_website) ? $search_all_website : false;

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
$smarty->register_function('getPricingArray', ['Modules\Goods\Helpers\ProductHelper', 'getPricingArray']);

$smarty->assign('recaptcha_enable', $recaptcha_enable);
$smarty->assign('key_recaptcha_public', $key_recaptcha_public);
