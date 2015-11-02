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
# $Id: configuration.php,v 1.81.2.9 2007/01/16 09:06:31 twice Exp $
#

define('USE_TRUSTED_POST_VARIABLES',1);
define('USE_TRUSTED_SCRIPT_VARS',1);
$trusted_post_variables = array("gpg_key", "pgp_key", "xpc_private_key_password", "xpc_private_key", "xpc_public_key", 'code_below_thumb', 'search_products_box_code', 'search_products_result_code', 'cidev_tracking_code', 'cidev_main_page_code', 'cidev_footer_code', 'cidev_keywords', 'cidev_description', 'cidev_header_code', 'cidev_top_header_code', 'cidev_yandex_code_number', 'cidev_ga_code_number', 'cidev_google_adwords', 'ssl_seal', 'templates_for_communication', 'request_availability_options', 'fraud_domains_free_email_provider', 'fraud_checks', 'common_footer_code', 'message_body', 'thank_you_message_body', 'po_instructions', 'product_question_message_body_to_brand', 'product_question_message_body_to_customer', 'backorder_message_body_condition_no_stock_no_eta', 'backorder_message_body_condition_no_stock_defined_eta', 'backorder_message_body_condition_partially_in_stock_no_eta', 'backorder_message_body_condition_partially_in_stock_defined_eta', 'Reconciliations', 'po_missing_subject_line', 'po_missing_instructions', 'common_header_code', 'product_advantages_code', 'stop_words', 'excluded_char_sequences', 'po_message_body', 'backorder_message_body_condition_case_a', 'backorder_message_body_condition_case_b', 'backorder_message_body_condition_case_c', 'backorder_message_body_condition_case_d', 'backorder_message_body_condition_case_e', 'backorder_message_body_condition_case_f', 'reference_text', 'signature');

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','mail','order');

$options = func_query_column("SELECT category FROM $sql_tbl[config] WHERE category NOT IN ('UPS_OnLine_Tools', 'Taxes') AND category != '' AND category != 'Search_All' GROUP BY category");


//func_print_r($templates_for_communication, $_POST);
//die();


#
##
###
if (!empty($options) && is_array($options)){
        foreach ($options as $k => $v){
                if ($v == "CMPI"){
                        unset($options[$k]);
                        $options['-1'] = $v;
                }
        }
        ksort($options);
	$options = array_values($options);
}
###
##
#

$disabled_modules = func_query_column("SELECT module_name FROM $sql_tbl[modules] WHERE active != 'Y'");
if (!empty($disabled_modules)) {
	foreach ($disabled_modules as $mn) {
		if (in_array($mn, $options) && !in_array($mn, array_keys($active_modules))) {
			func_unset($options, array_search($mn, $options));
		}
	}
}
$modules_detected = false;
foreach ($options as $on) {
	if (!empty($active_modules[$on])) {
		$modules_detected = true;
		break;
	}
}

array_splice($options, intval(array_search('Logging', array_values($options))) + 1, 0, 'Filter_Presets');

if (!in_array($option, $options)) {
	$option = "General";
}

require $xcart_dir."/include/countries.php";
require $xcart_dir."/include/states.php";
#
# Update configuration variables
# these variables are for internal use in PHP scripts
#

$location[] = array(func_get_langvar_by_name("lbl_general_settings"), "configuration.php");

if ($REQUEST_METHOD=="POST") {
	require $xcart_dir."/include/safe_mode.php";
}

if (!empty($active_modules['Multiple_Storefronts']) && $option == 'Multiple_Storefronts') {
	include $xcart_dir . '/modules/Multiple_Storefronts/sf_configuration.php';
}

if ($option == 'XPayments_Connector') {
	include $xcart_dir . '/modules/XPayments_Connector/xpc_admin.php';
}

if ($option == 'Product_Page') {
	include $xcart_dir . '/include/product_page_options.php';
}

if ($option == "User_Profiles") {
	include "./user_profiles.php";
}
elseif ($option == "Contact_Us") {
    include "./contact_us_profiles.php";
}
elseif ($option == "Templates_OrderRelatedMessages") {
    include "./templates_order_related_messages.php";
}
elseif ($option == "Attention_tag_options") {
    include "./attention_tag_options.php";
}
elseif ($option == "Product_classification") {
    include "./product_classification.php";
}
elseif ($option == "Request_availability_options") {
    include "./request_availability_options.php";
}
elseif ($option == "Fraud_check") {
    include "./fraud_check_options.php";
}
elseif ($option == "OTRS_options") {
    include "./otrs_options.php";
}
elseif ($option == "Reconciliation") {
    include "./reconciliation_options.php";
}
elseif ($option == "currently_assigned_to_statuses") {
    include "./order_statuses.php";
}


elseif ($option == "Search_products") {
    include "./search_products_form.php";
} elseif ($option == 'Filter_Presets') {
	include './filter_presets.php';
} elseif ($REQUEST_METHOD == "POST") {
        func_array2update("config", array("value" => "N"), "type IN ('checkbox','multiselector') AND category='".$option."'");

        $var_properties = func_query_hash("SELECT name, type FROM $sql_tbl[config] WHERE category='$option'", "name", false, true);

	$section_data = array();
	foreach ($_POST as $key => $val) {
		if ($key == "periodic_logs") {
			if (!is_array($val)) {
				$val = '';
			}
			else {
				$val = implode(',',$val);
			}
		}

		if (isset($var_properties[$key])) {
			if ($var_properties[$key] == "numeric") {
				$val = doubleval(func_convert_numeric($val));

			}
			elseif ($var_properties[$key] == "multiselector") {
				$val = implode(";", $val);
			}
			elseif ($var_properties[$key] == "checkbox" && $val=="on") {
				$val = "Y";
			}

			func_array2update("config", array("value" => $val), "name='".$key."' AND category='".$option."'");
			$section_data[stripslashes($key)] = stripslashes($val);
		}
	}

	#
	# Change 'products_order' options value if 'display_productcode_in_list' is changed to 'dasable'
	#
	if (
		$option == 'Appearance' &&
		!isset($_POST['display_productcode_in_list']) &&
		$config['Appearance']['display_productcode_in_list'] == 'Y' &&
		$_POST['products_order'] == 'productcode'
	) {
		func_array2update("config", array("value" => 'orderby'), "name='products_order' AND category='".$option."'");
	}

	# Checking whether Blowfish encryption of order details using Merchant key is enabled
	if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[config] WHERE name = 'blowfish_enabled' AND category='$option'")) {
		$new_value = func_query_first_cell("SELECT value FROM $sql_tbl[config] WHERE name = 'blowfish_enabled' AND category='$option'");
		if ($new_value != $config['Security']['blowfish_enabled']) {
			if ($new_value == 'Y') {
				if (empty($config['mpassword'])) {
					db_query("UPDATE $sql_tbl[config] SET value='".$config['Security']['blowfish_enabled']."' where name='blowfish_enabled' AND category='$option'");
					func_header_location($xcart_catalogs['admin']."/change_mpassword.php?from_config=".$option);
				}
				else {
					func_data_recrypt();
				}
			}
			elseif ($new_value != 'Y') {
				if ($merchant_password) {
					func_data_decrypt();
					$merchant_password = '';
				}
				else {
					db_query("UPDATE $sql_tbl[config] SET value='".$config['Security']['blowfish_enabled']."' WHERE name='blowfish_enabled' AND category='$option'");
				}
			}
		}
	}

	#
	# Apply default values to "empty" fields
	#
	db_query("UPDATE $sql_tbl[config] SET value = defvalue WHERE TRIM(value) = ''");

	if (!empty($active_modules['Fancy_Categories'])) {
		include $xcart_dir."/modules/Fancy_Categories/admin_config.php";
	}

	if ($option == "Security") {
		func_pgp_remove_key();
		$config[$option] = $section_data; # no code after func_pgp_add_key() using these settings
		func_pgp_add_key();
	}

	func_header_location("configuration.php?option=$option");
}

#
# Select default options tab
#
if ($option == "Image_Verification") {
	
	include_once $xcart_dir."/modules/Image_Verification/spambot_requirements.php";
	$handle = @opendir($xcart_dir."/modules/Image_Verification/img_generators/");
	
	if ($handle) {
		while (($file = readdir($handle)) != false) { 
			if (is_dir($xcart_dir."/modules/Image_Verification/img_generators/$file") && $file != "." && $file != ".." && $file != "CVS") { 
			$img_generators[] = $file;
			} 
		}
		closedir($handle);
	}
	$smarty->assign("img_generators", $img_generators);

} elseif($option == "Appearance") {
	$date_formats = array(
		"%d-%m-%Y",
		"%d/%m/%Y",
		"%d.%m.%Y",
		"%m-%d-%Y",
		"%m/%d/%Y",
		"%Y-%m-%d",
		"%b %e, %Y",
		"%A, %B %e, %Y");
	$time_formats = array(
		"",
		"%H:%M:%S",
		"%H.%M.%S",
		"%I:%M:%S %p");

	$smarty->assign("gmnow", time()+$config["Appearance"]["timezone_offset"]);
	$smarty->assign("date_formats", $date_formats);
	$smarty->assign("time_formats", $time_formats);
	$date_formats_alt = array();
	$r_search = array("%d","%m","%Y","%b","%e", "%A", "%B");
	$r_replace = array("DD","MM","YYYY", "month", "day", "day of week", "month");
	foreach ($date_formats as $k=>$v) {
		$date_formats_alt[$k] = str_replace($r_search,$r_replace,$v);
	}

	$smarty->assign("date_formats_alt", $date_formats_alt);
}
elseif ($option == "XAffiliate" && !empty($active_modules['XAffiliate'])) {
	$partner_plans = func_query ("SELECT * FROM $sql_tbl[partner_plans] ORDER BY plan_id");
	$smarty->assign ("partner_plans", $partner_plans);
}
elseif ($option == 'Maintenance_Agent') {
	$periodical_log_labels = array();
	foreach (explode(',', $config['Maintenance_Agent']['periodic_logs']) as $k=>$v) {
		$periodical_log_labels[$v] = true;
	}

	$smarty->assign('periodical_log_labels', $periodical_log_labels);
	$smarty->assign('periodical_logs_names', x_log_get_names());
}
elseif ($option == "Gift_Certificates") {
	$smarty->assign('gc_templates', func_gc_get_templates($smarty->template_dir));
}

if (!empty($active_modules['Multiple_Storefronts']) && $option == 'Multiple_Storefronts') {
	include $xcart_dir . '/modules/Multiple_Storefronts/get_configuration.php';
} else if (!empty($active_modules['XPayments_Connector']) && $option == 'XPayments_Connector') {
	include $xcart_dir . '/modules/XPayments_Connector/get_configuration.php';
} else {
	$configuration = func_query("SELECT * from $sql_tbl[config] WHERE category='$option' ORDER BY orderby");
}

if (!is_array($configuration)) {
	$configuration = array();
}

if (is_array($options)) {
	#
	# Define data for the navigation within section
	#

	# Get the list of core options (w/o module options)...
	$modules_detected = false;
	$dt_general = $dt_modules = array();
	foreach ($options as $catname) {

		$option_title = func_get_langvar_by_name("option_title_$catname");
		if (empty($option_title))
			$option_title = str_replace("_", " ", $catname)." options";

		$highlighted = ($option == $catname) ? "hl" : "";

		$tmp = array(
			"link" => "configuration.php?option=$catname",
			"title" => $option_title,
			"name" => func_get_langvar_by_name("option_title_$catname", null, false, true),	
			"style" => $highlighted
		);

		if (empty($active_modules[$catname])) {
			$dt_general[] = $tmp;

		} else {
            if ($catname != 'Multiple_Storefronts') {
			$dt_modules[] = $tmp;
		}
	}
	}
	$dialog_tools_data["mc_left"][] = array("data" => $dt_general);
	if (!empty($dt_modules)) {
		function xseo_modules_sort($a, $b) {
			return strcmp($a['name'], $b['name']);
		}
		usort($dt_modules, 'xseo_modules_sort');
		$dialog_tools_data["mc_left"][] = array("data" => $dt_modules, "title" => func_get_langvar_by_name("option_title_Modules"));
	}
	$dialog_tools_data["left"] = array();
	$dialog_tools_data["columns"] = 3;
}

if (!empty($active_modules["Fancy_Categories"]) && $option == "Fancy_Categories") {
	include $xcart_dir."/modules/Fancy_Categories/admin_config.php";
}

# Postprocessing service array with configuration variables of the current section
if (!empty($configuration)) {
	foreach ($configuration as $k => $v) {
		switch ($v['name']) {
		case 'sns_script_extension':
			if (empty($sns_extensions)) {
				unset($configuration[$k]);
				continue;
			}

			$v['variants'] = "";
			foreach ($sns_extensions as $ek => $ev) {
				$v['variants'] .= $ek.":".$ev."\n";
			}

			break;
		case 'cmpi_currency':
			$currs = func_query_hash("SELECT code, name FROM $sql_tbl[currencies]", "code", false, true);
			if (empty($currs)) {
				unset($configuration[$k]);
				continue;
			}

			$v['variants'] = "";
			foreach ($currs as $ek => $ev) {
				$v['variants'] .= $ek.":($ek) ".$ev."\n";
			}

			break;
		}

		$configuration[$k]['variants'] = $v['variants'];

		# Define array with variable variants
		if (in_array($v['type'], array("selector","multiselector"))) {
			if (empty($v['variants'])) {
				unset($configuration[$k]);
				continue;
			}

			$vars = func_parse_str(trim($v['variants']), "\n", ":");
			$vars = func_array_map("trim", $vars);

			# Check variable data
			if ($v['type'] == "multiselector") {
				$configuration[$k]['value'] = $v['value'] = explode(";", $v['value']);
				foreach ($v['value'] as $vk => $vv) {
					if (!isset($vars[$vv]))
						unset($v['value'][$vk]);
				}

				$configuration[$k]['value'] = $v['value'] = array_values($v['value']);
			}

			$configuration[$k]['variants'] = array();
			foreach ($vars as $vk => $vv) {
				$configuration[$k]['variants'][$vk] = array("name" => $vv);
				if (strpos($vv, " ") === false) {
					$name = func_get_langvar_by_name($vv, NULL, false, true);
					if (!empty($name)) {
						$configuration[$k]['variants'][$vk] = array("name" => $name);
					}
				}

				if ($v['type'] == "selector") {
					$configuration[$k]['variants'][$vk]['selected'] = ($v['value'] == $vk);
				}
				else {
					$configuration[$k]['variants'][$vk]['selected'] = (in_array($vk, $v['value']));
				}
			}
		}

		$predefined_lng_variables[] = "opt_".$v['name'];
	}
}


#
##
###
if ($option == 'SEO') {


    $unallowed_dirs = array('payment');

    foreach (
        array(
            'ADMIN',
            'PROVIDER',
            'PARTNER',
        ) as $area
    ) {
        $area_directory = constant('DIR_' . $area);

        if (
            !zerolen($area_directory)
            && preg_match('/^\/.+/', $area_directory)
        ) {

            $unallowed_dirs[] = preg_quote(ltrim($area_directory, '/'), '/');

        }

    }

    $unallowed_dirs = join("|", $unallowed_dirs);

    $apache_401_issue = func_get_apache_401_issue();
    if (
        ($dirs = func_is_used_ssl_shared_cert($http_location, $https_location))
        && func_apache_check_module('setenv')
    ) {
        $_htaccess = <<<SHTACCESS
            RewriteCond %{HTTPS} on
            RewriteRule .* - [E=FULL_WEB_DIR:$dirs[https]]
            RewriteCond %{HTTPS} !on
            RewriteRule .* - [E=FULL_WEB_DIR:$dirs[http]]

            $apache_401_issue
            RewriteCond %{REQUEST_URI} !^%{ENV:FULL_WEB_DIR}/($unallowed_dirs)/
            RewriteCond %{REQUEST_FILENAME} !\.(gif|jpe?g|png|js|css|swf|php|ico)$
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteCond %{REQUEST_FILENAME} !-l
            RewriteRule ^(.*)$ %{ENV:FULL_WEB_DIR}/dispatcher.php [L]
SHTACCESS;
    } else {
        $rewrite_base = func_get_rewrite_base();
        $_htaccess = <<<SHTACCESS
            RewriteBase $rewrite_base

            $apache_401_issue
            RewriteCond %{REQUEST_URI} !^$rewrite_base($unallowed_dirs)/
            RewriteCond %{REQUEST_FILENAME} !\.(gif|jpe?g|png|js|css|swf|php|ico)$
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteCond %{REQUEST_FILENAME} !-l
            RewriteRule ^(.*)$ dispatcher.php [L]
SHTACCESS;
    }
    $_htaccess = preg_replace("/^[ ]*(?=[a-z#])/mi", "\t", $_htaccess);

    $clean_url_htaccess = <<<EHTACCESS
# Clean URLs [[[
Options +FollowSymLinks -MultiViews -Indexes
&lt;IfModule mod_rewrite.c&gt;
\tRewriteEngine On

$_htaccess
&lt;/IfModule&gt;
# /Clean URLs ]]]
EHTACCESS;

    $smarty->assign('clean_url_htaccess',         $clean_url_htaccess);
    $smarty->assign('clean_url_htaccess_path',     $xcart_dir . XC_DS . '.htaccess');
    $smarty->assign('clean_url_test_url',         $http_location . DIR_CUSTOMER . "/clean-url-test");

}
###
##
#

if ($option) {
	$predefined_lng_variables[] = "option_title_".$option;
}

if ($option == 'Shipping') {
	$is_realtime = (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[shipping] WHERE code != ''") > 0);
	if ($is_realtime)
		$smarty->assign("is_realtime", $is_realtime);
}

if ($option == 'Appearance' && $config["Appearance"]["display_productcode_in_list"] != 'Y') {
	foreach ($configuration as $k => $v) {
		if ($v['name'] == 'products_order' && $v['variants'])
			func_unset($configuration[$k]['variants'], 'productcode');
	}
	
}

$smarty->assign("configuration", array_values($configuration));
$smarty->assign("options", $options);
$smarty->assign("option", $option);
$smarty->assign("main","configuration");

# Assign the current location line
$smarty->assign("location", $location);

# Assign the section navigation data
$smarty->assign("dialog_tools_data", $dialog_tools_data);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
