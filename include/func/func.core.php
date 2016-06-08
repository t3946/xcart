<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
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
# $Id: func.core.php,v 1.52.2.43 2007/01/15 08:18:26 twice Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

#
# Use this function to load code of functions on demand (include/func/func.*.php)
#
function x_load() {
	global $xcart_dir;

	$names = func_get_args();
	foreach ($names as $n) {
		$n = str_replace("..", "", $n);
		$f = $xcart_dir."/include/func/func.$n.php";

		if (file_exists($f)) {
			require_once $f;
		}
	}
}

/*
#
# This function replaced standard PHP function header("Location...")
#
function func_header_location($location, $keep_https = true) {
	global $XCART_SESSION_NAME, $XCARTSESSID, $_COOKIE;
	global $use_sessions_type, $is_location;
	global $_SERVER;
	global $config, $HTTPS, $REQUEST_METHOD;

	$is_location = 'Y';
	x_session_save();

	if ($use_sessions_type < 3) {
		session_write_close();
	}

	$added = array();

	if (!empty($XCARTSESSID) && (!isset($_COOKIE[$XCART_SESSION_NAME]) || defined('SESSION_ID_CHANGED')) && !preg_match("/$XCART_SESSION_NAME=/i", $location) && !defined('IS_ROBOT')) {
		$added[] = $XCART_SESSION_NAME."=".$XCARTSESSID;
	}

	if ($keep_https && $REQUEST_METHOD == "POST" && $HTTPS && strpos($location,'keep_https=yes') === false && $config["Security"]["dont_leave_https"] == "Y") {
		$added[] = "keep_https=yes";
		# this block is necessary (in addition to https.php) to prevent appearance of secure alert in IE
	}

	if (!empty($added)) {
		$hash = "";
		if (preg_match("/^(.+)#(.+)$/", $location, $match)) {
			$location = $match[1];
			$hash = $match[2];
		}

		$location .= (strpos($location, "?") === false ? "?" : "&").implode("&", $added);
		if (!empty($hash))
			$location .= "#".$hash;
	}

	# Opera 8.51 (8.x ?) notes:
	# 1. Opera requires both headers - "Location" & "Refresh". Without "Location" it displays
	#    HTML code for META redirect
	# 2. 'Refresh' header is required when ansvering on POST request

	if (!@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) {
		# Microsoft IIS handle "Location:" headers internaly and repeat request
		# so this can lead to infinite loops
		@header("Location: ".$location);
	}

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false
	|| @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) {
		@header("Refresh: 0; URL=".$location);
	}

	echo "<br /><br />".func_get_langvar_by_name("txt_header_location_note", array("time" => 2, "location" => $location), false, true, true);
	echo "<meta http-equiv=\"Refresh\" content=\"0;URL=$location\" />";

	func_flush();
	exit();
}
*/

/**
 * This function replaced standard PHP function header('Location...')
 */
function func_header_location($location, $keep_https = true, $http_error_code = 302, $allow_error_redirect = false, $show_note = true)
{
    global $XCART_SESSION_NAME, $XCARTSESSID, $is_location, $config, $HTTPS, $REQUEST_METHOD, $xcart_catalogs;

    $is_location = 'Y';

    $is_error_script = strpos($location, 'error_message.php');

    if ($is_error_script !== false) {

        $is_relative_link = false;

        if ($is_error_script == 0 || $is_error_script == 1) {
            $is_relative_link = true;
        }

        $link_to_area = null;
        $area_type = defined('AREA_TYPE') ? AREA_TYPE : 'C';

        if (
            !empty($xcart_catalogs)
            && is_array($xcart_catalogs)
            && $is_relative_link
        ) {
            $link_to_area = func_get_area_catalog($area_type);

            if ($is_error_script == 0) {
                $link_to_area .= '/';
            }

            $location = $link_to_area . $location;
        }
    }

    // You cannot redirect from the error message page.

    if (defined('IS_ERROR_MESSAGE') && !$allow_error_redirect) {
        global $id;
        if (isset($id))
            func_show_error_page("Sorry, the shop is inaccessible temporarily. Please try again later.", "Error code: " . $id);
    }

    if (function_exists('x_session_save')) {
        x_session_save();
    }

    func_ajax_finalize();

    $added = array();

    $supported_http_redirection_codes = array(
        '301' => "301 Moved Permanently",
        '302' => "302 Found",
        '303' => "303 See Other",
        '304' => "304 Not Modified",
        '305' => "305 Use Proxy",
        '307' => "307 Temporary Redirect"
    );

    $location = preg_replace('/[\x00-\x1f].*$/sm', '', $location);
    $location_info = @parse_url($location);

    if (
        !empty($XCARTSESSID)
        && (
            !isset($_COOKIE[$XCART_SESSION_NAME])
            || defined('SESSION_ID_CHANGED')
        )
        && !preg_match('/' . preg_quote($XCART_SESSION_NAME, '/') . "=/i", $location)
        && !defined('IS_ROBOT')
        && (
            empty($location_info)
            ||  !empty($location_info['host'])
        )
        && !defined('IS_ERROR_MESSAGE')
    ) {
        $added[] = $XCART_SESSION_NAME."=".$XCARTSESSID;
    }

    if (
        $keep_https
        && $REQUEST_METHOD == 'POST'
        && $HTTPS
        && strpos($location, 'keep_https=yes') === false
        && $config['Security']['leave_https'] != 'Y'
    ) {
        $added[] = "keep_https=yes";
        // this block is necessary (in addition to https.php) to prevent appearance of secure alert in IE
    }

    if (!empty($added)) {
        $hash = '';

        if (preg_match("/^(.+)#(.+)$/", $location, $match)) {#nolint
            $location = $match[1];#nolint
            $hash = $match[2];#nolint
        }

        $location .= (strpos($location, "?") === false ? "?" : "&") . implode("&", $added);

        if (!empty($hash))
            $location .= "#" . $hash;
    }

    // Opera 8.51 (8.x ?) notes:
    // 1. Opera requires both headers - 'Location' & 'Refresh'. Without 'Location' it displays
    //    HTML code for META redirect
    // 2. 'Refresh' header is required when answering to a POST request

    if (
        !empty($http_error_code)
        && in_array($http_error_code, array_keys($supported_http_redirection_codes))
    ) {
        @header("HTTP/1.1 " . $supported_http_redirection_codes[$http_error_code]);
    }

    @header("Location: " . $location);

    if (
        strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')!==false
        || preg_match("/Microsoft|WebSTAR|Xitami/", getenv('SERVER_SOFTWARE'))
    ) {
        @header("Refresh: 0; URL=" . $location);
    }

    if ($show_note) {
        echo "<br /><br />"
            . func_get_langvar_by_name(
                'txt_header_location_note',
                array(
                    'time' => 2,
                    'location' => func_convert_amp($location),
                ),
                false,
                true,
                true
            );
    }

    echo "<meta http-equiv=\"Refresh\" content=\"0;URL=" . $location ."\" />";

    func_flush();
    exit();
}


/**
 * Check - is background JSON-based request or not
 */
function func_is_ajax_json_request()
{
    return
        func_is_ajax_request()
        && isset($_SERVER['HTTP_ACCEPT'])
        && preg_match('/application\/json/Ss', $_SERVER['HTTP_ACCEPT']);
}

/**
 * Register AJAX message
 */
function func_register_ajax_message($msg, $params = array())
{
    global $ajax_messages;

    if (!func_is_ajax_request())
        return false;

    if (!is_array($ajax_messages))
        $ajax_messages = array();

    $ajax_messages[] = array(
        'name'         => $msg,
        'params'     => is_array($params) ? $params : array(),
    );

    return true;
}

/**
 * Prepare AJAX messages for displaying
 */
function func_prepare_ajax_messages()
{
    global $ajax_messages, $default_charset;

    $strings = array();

    if (is_array($ajax_messages) && count($ajax_messages) > 0) {

        foreach ($ajax_messages as $m) {

            $strings[] = '<div class="ajax-internal-message" style="display: none;">' . $m['name'] . ':' . func_json_encode(func_convert_encoding($m['params'], $default_charset, 'UTF-8')) . '</div>';

        }

    }

    return implode("\n", $strings);
}


/**
 * AJAX request finalization
 */
function func_ajax_finalize()
{
    if (!func_is_ajax_request()) {
        return false;
    }

    $messages = func_prepare_ajax_messages();

    if ($messages) {

        func_flush($messages);

    }

    exit;
}


#
# Calculates weight from user units to grams
#
function func_weight_in_grams($weight) {
	global $config;

	return $weight*$config["General"]["weight_symbol_grams"];
}

#
# Get county by code
#
function func_get_county ($countyid) {
	global $sql_tbl;

	$county_name = func_query_first_cell("SELECT county FROM $sql_tbl[counties] WHERE countyid='$countyid'");

	return ($county_name ? $county_name : $countyid);
}
#
# Get state by code
#
function func_get_state ($state_code, $country_code) {
	global $sql_tbl;

	$state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='$country_code' AND code='".addslashes($state_code)."'");

	return ($state_name ? $state_name : $state_code);
}

#
# Get country by code
#
function func_get_country ($country_code, $force_code = '') {
	global $sql_tbl, $shop_language;

	$code = (empty($force_code)?$shop_language:$force_code);
	$country_name = func_query_first_cell("SELECT value as country FROM $sql_tbl[languages] WHERE name='country_$country_code' AND code = '$code'");
	return ($country_name ? $country_name : $country_code);
}

# START: random:20341 [2010 Jul 29 14:46] 
function func_manufacturerid_for_group($shipping_freight, $product_manufacturerid) {
	global $artss_manufacturerid;
//	return ($shipping_freight != 0) ? $product_manufacturerid : $artss_manufacturerid;
	return $product_manufacturerid;
}

# END: random:20341 [2010 Jul 29 14:46] 
#
# Convert price to "XXXXX.XX" format
#
function price_format($price) {
	return sprintf("%.2f", round((double)$price+0.00000000001, 2));
}

#
# Return number of available products
#
function insert_productsonline() {
	global $sql_tbl;

	return func_query_first_cell("SELECT COUNT(productid) FROM $sql_tbl[products] WHERE forsale!='N'");
}

#
# Return number of available items
#
function insert_itemsonline() {
	global $sql_tbl;

	return func_query_first_cell("SELECT SUM(avail) FROM $sql_tbl[products] WHERE forsale!='N'");
}

#
# This function returns true if $cart is empty
#
function func_is_cart_empty($cart) {
	return empty($cart) || empty($cart["products"]) && empty($cart["giftcerts"]);
}

#
# Get value of language variable by its name and usertype
#
function func_get_langvar_by_name($lang_name, $replace_to=NULL, $force_code = false, $force_output = false, $cancel_wm=false) {
	global $sql_tbl, $current_area, $config, $shop_language, $editor_mode;
	global $smarty, $user_agent;
	global $predefined_lng_variables;

	$language_code = $shop_language;

	if ($force_code !== false)
		$language_code = $force_code;

	if (!$force_output && $editor_mode == 'editor')
		$force_output = true;

	if ($force_output === false) {
		$predefined_lng_variables[] = $lang_name;
		if ($force_code === false)
			$language_code = "  ";

		$tmp = "";
		if (is_array($replace_to) && !empty($replace_to)) {
			foreach($replace_to as $k => $v) {
				$tmp .= "$k>$v<<<";
			}

			$tmp = substr($tmp, 0, -3);
		}

		return "~~~~|".$lang_name."|".$language_code."|".$tmp."|~~~~";
	}

	$result = func_query_first_cell("SELECT value FROM $sql_tbl[languages] WHERE code='$language_code' AND name='$lang_name'");
	if (empty($result)) {
		$_language_code = ($current_area == "C" ? $config["default_customer_language"] : $config["default_admin_language"]);
		if ($_language_code != $language_code) {
			$result = func_query_first_cell("SELECT value FROM $sql_tbl[languages] WHERE code='$_language_code' AND name='$lang_name'");
		}
		elseif ($language_code != 'US') {
			$result = func_query_first_cell("SELECT value FROM $sql_tbl[languages] WHERE code='US' AND name='$lang_name'");
		}
	}

	if (is_array($replace_to)) {
		foreach ($replace_to as $k=>$v)
			$result = str_replace("{{".$k."}}", $v, $result);
	}

	if ($smarty->webmaster_mode && !$cancel_wm)
		$result = func_webmaster_label($user_agent, $lang_name, $result);

	return $result;
}

#
# Flush output
#
function func_flush($s = NULL) {
	if (!is_null($s))
		echo $s;

	if (preg_match("/Apache(.*)Win/S", getenv("SERVER_SOFTWARE")))
		echo str_repeat(" ", 2500);
	elseif (preg_match("/(.*)MSIE(.*)\)$/S", getenv("HTTP_USER_AGENT")))
		echo str_repeat(" ", 256);

	if (function_exists('ob_flush')) {
		# for PHP >= 4.2.0
		ob_flush();
	}
	else {
		# for PHP < 4.2.0
		if (ob_get_length() !== FALSE){
//			@header('Content-Encoding: none;');
			ob_end_flush();
		}
	}

	flush();
}

#
# This function added the ability to redirect a user to another page using HTML meta tags
# (without using header() function or Javascript)
#
function func_html_location($url, $time=3) {
	x_session_save();

	if ($use_sessions_type < 3) {
		session_write_close();
	}

	echo "<br /><br />".func_get_langvar_by_name("txt_header_location_note", array("time" => $time, "location" => $url),false,true);
	echo "<meta http-equiv=\"Refresh\" content=\"$time;URL=$url\" />";
	func_flush();

	exit;
}

#
# This function returns the language variable value by name and language code
#
function func_get_languages_alt($name, $lng_code = false, $force_get = false) {
	global $sql_tbl, $shop_language, $config, $current_area;

	if ($lng_code === false)
		$lng_code = $shop_language;

	if ($force_get) {
		# Force get language variable(s) content
		$is_array = is_array($name);
		if (!$is_array)
			$name = array($name);

		if ($current_area == 'C' || $current_area == 'B') {
			$lngs = array($lng_code, $config['default_customer_language'], $config['default_admin_language'], false);
		} else {
			$lngs = array($lng_code, $config['default_admin_language'], $config['default_customer_language'], false);
		}
		$lngs = array_unique($lngs);

		$hash = array();
		foreach ($lngs as $lng_code) {
			$where = '';
			if ($lng_code !== false)
				$where = " AND code = '$lng_code'";

			$res = func_query_hash("SELECT name, value FROM $sql_tbl[languages_alt] WHERE name IN ('".implode("','", $name)."')".$where, "name", false, true);

			if (empty($res))
				continue;

			foreach($res as $n => $l) {
				if (!isset($hash[$n])) {
					$hash[$n] = $l;
					$idx = array_search($n ,$name);
					if ($ids !== false)
						unset($name[$idx]);
				}
			}

			if (empty($name))
				break;
		}

		return !$is_array ? array_shift($hash) : $hash;
	}

	if (is_array($name)) {
		return func_query_hash("SELECT name, value FROM $sql_tbl[languages_alt] WHERE code='$lng_code' AND name IN ('".implode("','", $name)."')", "name", false, true);
	}

	return func_query_first_cell("SELECT value FROM $sql_tbl[languages_alt] WHERE code='$lng_code' AND name='$name'");
}

#
# This function quotes arguments for shell command according
# to the host operation system
#
function func_shellquote() {
	static $win_s = '!([\t \&\<\>\?]+)!S';
	static $win_r = '"\\1"';
	$result = "";
	$args = func_get_args();
	foreach ($args as $idx=>$arg)
		$args[$idx] = X_DEF_OS_WINDOWS ? (preg_replace($win_s,$win_r,$arg)) : (escapeshellarg($arg));

	return implode(' ', $args);
}

#
# This function checks the user passwords with default values
#
function func_check_default_passwords($uname=false) {
	global $sql_tbl, $active_modules;

	x_load('crypt');

	$default_accounts = array();
	$default_accounts["P"] = array("provider", "master", "root");

	if (empty($active_modules["Simple_Mode"]))
		$default_accounts["A"] = array("admin");

	$return = array();

	if (!empty($uname)) {
		#
		# Check password security for specified user name
		#
		$account = func_query_first("SELECT login, password FROM $sql_tbl[customers] WHERE login='$uname'");
		if (is_array($account) && $account["login"] == text_decrypt($account["password"]))
			$return[] = $account["login"];
	}
	else {
		#
		# Check password security for all default user names
		#
		foreach ($default_accounts as $usertype=>$accounts) {
			foreach ($accounts as $login_) {
				if (!empty($uname) && $uname != $login_)
					continue;

				$account = func_query_first("SELECT login, password FROM $sql_tbl[customers] WHERE login='$login_' AND usertype='$usertype'");
				if (empty($account) || !is_array($account))
					continue;

				if ($account["login"] == text_decrypt($account["password"]))
					$return[] = $account["login"];
			}
		}
	}

	return $return;
}

function func_constant($constant)
{
    if (defined($constant))
        return constant($constant);
    else
        return false;
}

/**
 * Function that determines whether the script being pointed to by the link
 * pertains to the shop from which the function is run.
 */
function func_is_current_shop($href)
{
    global $http_location, $https_location;

    $href = trim($href);

    if (preg_match("/^http:\/\//S", $href)) {
        return (substr($href, 0, strlen($http_location)) == $http_location);
    }

    if (preg_match("/^https:\/\//S", $href)) {
        return (substr($href, 0, strlen($https_location)) == $https_location);
    }

    if (preg_match("/^\//S", $href)) {

        $http_parsed = @parse_url($http_location);
        $https_parsed = @parse_url($http_location);

        return (
            substr($href, 0, strlen($http_parsed['path'])) == $http_parsed['path']
            || substr($href, 0, strlen($https_parsed['path'])) == $https_parsed['path']
        );
    }

    return true;
}

/**
 * Convert & to &amp;
 */
function func_convert_amp($str)
{
    // Do not convert html entities like &thetasym; &Omicron; &euro; &#8364; &#8218;
    return preg_replace('/&(?![a-zA-Z0-9#]{1,8};)/Ss', '&amp;', $str);
}

function func_qs_combine($arr, $qappend = true)
{
    if (empty($arr) || !is_array($arr)) {
        return '';
    }

    $qs = array();

    foreach ($arr as $k => $v) {

#
## Filter
###
	if (!empty($v) && is_array($v)){

		foreach ($v as $kk=> $vv){
			$qs[] = urlencode($k).'['. urlencode($kk) .']='.urlencode($vv);
		}

	} else 
###
##
#
	        $qs[] = urlencode($k) . '=' . urlencode($v);
    }

    return ($qappend ? '?' : '') . join("&amp;", $qs);
}

#
# Smarty->display wrapper
#
function func_display($tpl, &$templater, $to_display = true) {
	global $config;
	global $predefined_lng_variables, $override_lng_code, $shop_language, $user_agent, $__smarty_time, $__smarty_size;
	global $xcart_dir;
	global $__X_LNG;

	x_load('templater');

	__add_mark_smarty();
	if (!empty($config['Security']['compiled_tpl_check_md5']) && $config['Security']['compiled_tpl_check_md5'] == 'Y') {
		$templater->compile_check_md5 = true;
	}
	else {
		$templater->compile_check_md5 = false;
	}

	if (!empty($predefined_lng_variables)) {
		$lng_code = $override_lng_code;
		if (empty($lng_code)) {
			$lng_code = $shop_language;
		}

		if (!empty($predefined_lng_variables)) {
			$predefined_lng_variables = array_flip($predefined_lng_variables);
			$predefined_vars = array();
			func_get_lang_vars_extra($lng_code, $predefined_lng_variables, $predefined_vars);
			if ($templater->webmaster_mode)
				$result = func_webmaster_convert_labels($predefined_vars);

			$templater->_tpl_vars['lng'] = func_array_merge($templater->_tpl_vars['lng'], $predefined_vars);

			if (!isset($__X_LNG[$shop_language])) {
				$__X_LNG[$shop_language] = $predefined_vars;
			} else {
				$__X_LNG[$shop_language] = func_array_merge($__X_LNG[$shop_language], $predefined_vars);
			}

			unset($predefined_vars);
		}
		unset($predefined_lng_variables);
	}

	$templater->register_postfilter("func_tpl_add_hash");

	if (isset($templater->webmaster_mode) && $templater->webmaster_mode) {
		$templater->force_compile = true;
		$templater->register_postfilter("func_webmaster_filter");
		$templater->register_outputfilter("func_tpl_webmaster");
	}

	$templater->register_postfilter("func_tpl_postfilter");
	$templater->register_outputfilter("func_convert_lang_var");


    if (func_constant('AREA_TYPE') == 'C') {
        if ($config['SEO']['clean_urls_enabled'] == 'Y')
            $templater->register_outputfilter('func_clean_url_filter_output');

        if ($config['General']['use_cached_templates'] != 'Y')
            $templater->register_prefilter('func_tpl_remove_include_cache');
    }


	if($to_display == true) {
		$templater->display($tpl);
		$ret = "";
	} else {
		$ret = $templater->fetch($tpl);
	}

	__add_mark_smarty($tpl);

	if ($to_display == true) {
		# Display page content
		func_flush();

		# Update tracking statistics
		if (AREA_TYPE == 'C')
			include_once $xcart_dir."/include/atracking.php";
	}

	return $ret;
}

#
# Function for fetching language variables values for one code
#
function func_get_lang_vars($code, &$variables, &$lng) {
	global $sql_tbl;

	$labels = db_query("SELECT name, value FROM $sql_tbl[languages] WHERE code = '$code' AND name IN ('".implode("','", array_keys($variables))."')");
	if ($labels) {
		while ($v = db_fetch_array($labels)) {
			$lng[$v['name']] = $v['value'];
			unset($variables[$v['name']]);
		}

		db_free_result($labels);
	}
}

#
# Extra version of func_get_lang_vars(): try to fetch values of language variables
# using all possible language codes
#
function func_get_lang_vars_extra($prefered_lng_code, &$variables, &$lng) {
	global $current_area, $config;

	if (empty($variables))
		return;

	func_get_lang_vars($prefered_lng_code, $variables, $lng);
	if (empty($variables))
		return;

	$default_language = ($current_area == 'C' ? $config['default_customer_language'] : $config['default_admin_language']);
	if ($default_language != $prefered_lng_code) {
		func_get_lang_vars($default_language, $variables, $lng);
		if (empty($variables))
			return;
	}

	if ($default_language != 'US')
		func_get_lang_vars('US', $variables, $lng);
}

#
# Check CC processor's transaction type
#
function func_check_cc_trans ($module_name, $type, $hash = array()) {
	global $sql_tbl;

	$return = false;
	if (empty($hash) && is_array($hash))
		$hash = array("P" => "P", "C" => "C", "R" => "R");

	if (empty($type))
		$type = 'P';

	if ($type == 'P') {
		$return = $hash[$type];
	}
	elseif ($type == 'C') {
		if (func_query_first_cell("SELECT is_check FROM $sql_tbl[ccprocessors] WHERE module_name = '$module_name'"))
			$return = $hash[$type];
	}
	elseif ($type == 'R') {
		if (func_query_first_cell("SELECT is_refund FROM $sql_tbl[ccprocessors] WHERE module_name = '$module_name'"))
			$return = $hash[$type];
	}

	if (empty($return) && $return !== false)
		$return = false;

	return $return;
}

#
# Parse string to hash array like:
# x=1|y=2|z=3
# where:
#	str 	= x=1|y=2|z=3
#	delim 	= |
# convert to:
# array('x' => 1, 'y' => 2, 'z' => 3)
#
function func_parse_str($str, $delim = '&', $pair_delim = '=', $value_filter=false) {
	if (empty($str))
		return array();

	$arr = explode($delim, $str);
	$return = array();
	for ($x = 0; $x < count($arr); $x++) {
		$pos = strpos($arr[$x], $pair_delim);
		if ($pos === false) {
			$return[$arr[$x]] = false;
		}
		elseif ($pos >= 0) {
			$v = substr($arr[$x], $pos+1);
			if (!empty($value_filter))
				$v = $value_filter($v);

			$return[substr($arr[$x], 0, $pos)] = $v;
		}
	}

	return $return;
}

#
# Remove parameters from QUERY_STRING by name
#
function func_qs_remove($qs) {
    if (func_num_args() <= 1)
        return $qs;

    $args = func_get_args(); 
	array_shift($args);

	if (count($args) == 0 || (strpos($qs, "=") === false && strpos($qs, "?") === false))
		return $qs;

	# Get scheme://domain/path part
	if (strpos($qs, '?') !== false)
		list($main, $qs) = explode("?", $qs, 2);

	# Get #hash part
	if (strrpos($qs, "#") !== false) {
		$hash = substr($qs, strrpos($qs, "#")+1);
		$qs = substr($qs, 0, strrpos($qs, "#"));
	}

	# Parse query string
	$arr = func_parse_str($qs);

	# Filter query string
	foreach ($args as $param_name) {
		if (empty($param_name) || !is_string($param_name))
			continue;

		$reg = "/".preg_quote($param_name, "/")."(?:\[[^\]]*\])?/S";
		foreach ($arr as $ak => $av) {
			if (preg_match($reg, $ak) || empty($ak)) {
				unset($arr[$ak]);
				break;
			}
		}
	}

	# Assembly return string
	foreach ($arr as $ak => $av)
		$arr[$ak] = $ak."=".$av;

	$qs = implode("&", $arr);

	if (isset($main))
		$qs = $main.(empty($qs) ? "" : ("?".$qs));

	if (isset($hash))
		$qs .= "#".$hash;

	return $qs;
}

#
# Get default field's name
#
function func_get_default_field($name) {
	$prefix = substr($name, 0, 2);
	if ($prefix == "s_" || $prefix == "b_") {
		$name = substr($name, 2);
	}

	$name = str_replace(
		array("firstname","lastname","zipcode"),
		array("first_name","last_name","zip_code"),
		$name);

	return func_get_langvar_by_name("lbl_".$name, false, false, true);
}

#
# Get memberships list
#
function func_get_memberships($area = 'C', $as_hash = false) {
	global $sql_tbl, $shop_language;

	$query_string = "SELECT $sql_tbl[memberships].membershipid, IFNULL($sql_tbl[memberships_lng].membership, $sql_tbl[memberships].membership) as membership FROM $sql_tbl[memberships] LEFT JOIN $sql_tbl[memberships_lng] ON $sql_tbl[memberships].membershipid = $sql_tbl[memberships_lng].membershipid AND $sql_tbl[memberships_lng].code = '$shop_language' WHERE $sql_tbl[memberships].active = 'Y' AND $sql_tbl[memberships].area = '$area' ORDER BY $sql_tbl[memberships].orderby";

	if ($as_hash) {
		return func_query_hash($query_string, "membershipid", false);
	} else {
		return func_query($query_string);
	}
}

#
# Detect membershipid by membership name
#
function func_detect_membership($membership = "", $type = false) {
	global $sql_tbl;

	if (empty($membership))
		return 0;

	$where = "";
	if ($type != false)
		$where = " AND area = '$type'";

	$membership = addslashes($membership);
	$id = func_query_first_cell("SELECT membershipid FROM $sql_tbl[memberships] WHERE membership = '$membership'".$where);

	return $id ? $id : 0;
}

#
# The function is merging arrays by keys
# Ex.:
# array(5 => "y") = func_array_merge_assoc(array(5 => "x"), array(5 => "y"));
#
function func_array_merge_assoc() {
	if (!func_num_args())
		return array();

	$args = func_get_args();

	$result = array();
	foreach ($args as $val) {
		if (!is_array($val) || empty($val))
			continue;

		foreach ($val as $k => $v)
			$result[$k] = $v;
	}

	return $result;
}

function func_membership_update($type, $id, $membershipids, $field = false) {
	global $sql_tbl;

	$tbl = $sql_tbl[$type."_memberships"];
	if (empty($tbl) || empty($id))
		return false;

	if ($field === false)
		$field = $type."id";

	db_query("DELETE FROM $tbl WHERE $field = '$id'");

	if (!empty($membershipids)) {
		if (!in_array(-1, $membershipids)) {
			foreach ($membershipids as $v) {
				db_query("INSERT INTO $tbl VALUES ('$id','$v')");
			}
		}
	}

	return true;
}

function func_get_titles() {
	global $sql_tbl;

	$titles = func_query("SELECT * FROM $sql_tbl[titles] WHERE active = 'Y' ORDER BY orderby, title");
	if (!empty($titles)) {
		foreach ($titles as $k => $v) {
			$name = func_get_languages_alt("title_".$v['titleid']);
			$titles[$k]['title_orig'] = $v['title'];
			if (!empty($name)) {
				$titles[$k]['title'] = $name;
			}
		}
	}

	return $titles;
}

function func_detect_title($title) {
	global $sql_tbl;

	if (empty($title))
		return false;

	return func_query_first_cell("SELECT titleid FROM $sql_tbl[titles] WHERE title = '$title'");
}

function func_get_title($titleid, $code = false) {
	global $sql_tbl, $shop_language;

	if (empty($titleid))
		return false;

	$title = func_get_languages_alt("title_".$titleid, $code);
	if (empty($title)) {
		$title = func_query_first_cell("SELECT title FROM $sql_tbl[titles] WHERE titleid = '$titleid'");
	}

	return $title;
}

#
# Detect price
#
function func_is_price($price, $cur_symbol = '$', $cur_symbol_left = true) {
	if (is_numeric($price))
		return true;

	$price = trim($price);
	$cur_symbol = preg_quote($cur_symbol, "/");
	if ($cur_symbol_left) {
		$price = preg_replace("/^".$cur_symbol."/S", "", $price);
	} else {
		$price = preg_replace("/".$cur_symbol."$/S", "", $price);
	}

	return func_is_numeric($price);
}

#
# Convert price
#
function func_detect_price($price, $cur_symbol = '$', $cur_symbol_left = true) {

	if (!is_numeric($price)) {
		$price = trim($price);
		$cur_symbol = preg_quote($cur_symbol, "/");
		if ($cur_symbol_left) {
			$price = preg_replace("/^".$cur_symbol."/S","", $price);
		} else {
			$price = preg_replace("/".$cur_symbol."$/S","", $price);
		}
		$price = func_convert_number($price);
	}

	return doubleval($price);
}

#
# Detect number
#
function func_is_numeric($var, $from = NULL) {
	global $config;

	if (is_numeric($var))
		return true;

	if (strlen(@$var) == 0)
		return false;

	if (empty($from))
		$from = $config['Appearance']['number_format'];

	if (empty($from))
		$from = "2.,";

	$var = str_replace(" ", "", str_replace(substr($from, 1, 1), ".", str_replace(substr($from, 2, 1), "", $var)));

	return is_numeric($var);
}

#
# Convert local number format to inner number format
#
function func_convert_number($var, $from = NULL) {
	global $config;

	if (strlen(@$var) == 0)
		return $var;

	if (empty($from))
		$from = $config['Appearance']['number_format'];

	if (empty($from))
		$from = "2.,";

	return round(func_convert_numeric($var, $from), intval(substr($from, 0, 1)));
}

#
# Convert local number format (without precision) to inner number format
#
function func_convert_numeric($var, $from = NULL) {
	global $config;

	if (strlen(@$var) == 0)
		return $var;

	$var = trim($var);
	if (preg_match("/^\d+$/S", $var))
		return doubleval($var);

	if (empty($from))
		$from = $config['Appearance']['number_format'];

	if (empty($from))
		$from = "2.,";

	return doubleval(str_replace(" ", "", str_replace(substr($from, 1, 1), ".", str_replace(substr($from, 2, 1), "", $var))));
}

#
# Format price according to 'Input and display format for floating comma numbers' option
#
function func_format_number($price, $thousand_delim = NULL, $decimal_delim = NULL, $precision = NULL) {
	global $config;

	if (strlen(@$price) == 0)
		return $price;

	$format = $config['Appearance']['number_format'];

	if (empty($format)) $format = "2.,";

	if (is_null($thousand_delim) || $thousand_delim === false)
		$thousand_delim = substr($format,2,1);

	if (is_null($decimal_delim) || $decimal_delim === false)
		$decimal_delim = substr($format,1,1);

	if (is_null($precision) || $precision === false)
		$precision = intval(substr($format,0,1));

	return number_format(round((double)$price+0.00000000001, $precision), $precision, $decimal_delim, $thousand_delim);
}

#
# Convert string to use in custom javascript code
#
function func_js_escape($string) {
	return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
}

#
# Generate product flags (stored in statis service array - xcart_quick_flags table)
# work for all/selected products
#
function func_build_quick_flags($id = false, $tick = 0) {
	global $sql_tbl, $active_modules;

	$where = "";
	if ($id !== false && !is_array($id)) {
		$where = " WHERE $sql_tbl[products].productid = '$id'";
		db_query("DELETE FROM $sql_tbl[quick_flags] WHERE productid = '$id'");

	} elseif (is_array($id) && !empty($id)) {
		$where = " WHERE $sql_tbl[products].productid IN ('".implode("','", $id)."')";
		db_query("DELETE FROM $sql_tbl[quick_flags] WHERE productid IN ('".implode("','", $id)."')");

	} else {
		db_query("DELETE FROM $sql_tbl[quick_flags]");
	}

	if ($tick > 0)
		func_display_service_header("lbl_rebuild_quick_flags");

	$image_fields = "$sql_tbl[images_T].image_path AS image_path_T";

	if (empty($active_modules['Product_Options'])) {
		$sd = db_query("SELECT $sql_tbl[products].productid, '' AS is_variants, '' AS is_product_options, IF($sql_tbl[product_taxes].productid IS NULL, '', 'Y') AS is_taxes, $image_fields  FROM $sql_tbl[products] LEFT JOIN $sql_tbl[product_taxes] ON $sql_tbl[product_taxes].productid = $sql_tbl[products].productid LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[images_T].id = $sql_tbl[products].productid $where GROUP BY $sql_tbl[products].productid");

	} else {
		$sd = db_query("SELECT $sql_tbl[products].productid, IF($sql_tbl[variants].variantid IS NULL, '', IF(MAX($sql_tbl[variants].avail) = 0, 'E', 'Y')) AS is_variants, IF($sql_tbl[classes].productid IS NULL, '', 'Y') AS is_product_options, IF($sql_tbl[product_taxes].productid IS NULL, '', 'Y') AS is_taxes, $image_fields FROM $sql_tbl[products] LEFT JOIN $sql_tbl[variants] ON $sql_tbl[variants].productid = $sql_tbl[products].productid LEFT JOIN $sql_tbl[classes] ON $sql_tbl[classes].productid = $sql_tbl[products].productid LEFT JOIN $sql_tbl[product_taxes] ON $sql_tbl[product_taxes].productid = $sql_tbl[products].productid LEFT JOIN $sql_tbl[images_T] ON $sql_tbl[images_T].id = $sql_tbl[products].productid $where GROUP BY $sql_tbl[products].productid");
	}

	$updated = 0;

	if ($sd) {
		while ($row = db_fetch_array($sd)) {

			func_array2insert("quick_flags", func_addslashes($row), true, true);

			$updated++;
			if ($tick > 0 && $updated % $tick == 0) {
				echo ". ";
				if (($updated/$tick) % 100 == 0)
					echo "\n";
				func_flush();
			}
		}

		db_free_result($sd);
	}

	return $updated;
}

#
# Generate matrix: MIN(product price) x membershipid (stored in statis service array - xcart_quick_prices table)
# (with variantid)
# work for all/selected products
#
function func_build_quick_prices($id = false, $tick = 0) {
	global $sql_tbl, $config, $active_modules;


#
## https://basecamp.com/2070980/projects/1577907/messages/52260744
###
if ($id !== false && !is_array($id)) {
	$i = func_generate_discounts (array($id), $tick);
}
elseif (is_array($id) && !empty($id)) {
	$i = func_generate_discounts ($id, $tick);
}

return $i; 
###
##
#

	# Define product condition
	$where = "";
	if ($id !== false && !is_array($id)) {
		$where = " AND $sql_tbl[products].productid = '$id'";
		db_query("DELETE FROM $sql_tbl[quick_prices] WHERE productid = '$id'");
	}
	elseif (is_array($id) && !empty($id)) {
		$where = " AND $sql_tbl[products].productid IN ('".implode("','", $id)."')";
		db_query("DELETE FROM $sql_tbl[quick_prices] WHERE productid IN ('".implode("','", $id)."')");
	}
	else {
		db_query("DELETE FROM $sql_tbl[quick_prices]");
	}

	if ($tick > 0)
		func_display_service_header("lbl_rebuild_quick_prices");

	# Get common data
	if (empty($active_modules['Product_Options'])) {

//		$res = db_query("SELECT $sql_tbl[products].productid, MIN(CONCAT($sql_tbl[pricing].price,'/',$sql_tbl[pricing].membershipid, '/', $sql_tbl[pricing].priceid)) as priceid, $sql_tbl[pricing].membershipid FROM $sql_tbl[products], $sql_tbl[pricing] WHERE $sql_tbl[pricing].productid = $sql_tbl[products].productid AND $sql_tbl[pricing].variantid = 0 AND $sql_tbl[pricing].quantity = 1 $where GROUP BY $sql_tbl[products].productid, $sql_tbl[pricing].membershipid");

                $res = db_query("
SELECT
        xcart_products.productid, 
        CONCAT(xcart_pricing.price,'/',0, '/', xcart_pricing.priceid) as priceid
FROM xcart_pricing, xcart_products 
        
WHERE xcart_pricing.productid = xcart_products.productid 
        AND xcart_pricing.variantid = 0 
        $where
        AND xcart_pricing.quantity<=xcart_products.min_amount 

ORDER By xcart_pricing.quantity DESC
LIMIT 1");



	} else {
//		$res = db_query("SELECT $sql_tbl[products].productid, MIN(CONCAT($sql_tbl[pricing].price,'/',$sql_tbl[pricing].membershipid, '/', $sql_tbl[pricing].priceid)) as priceid, $sql_tbl[pricing].membershipid FROM $sql_tbl[pricing], $sql_tbl[products] LEFT JOIN $sql_tbl[variants] ON $sql_tbl[products].productid = $sql_tbl[variants].productid WHERE $sql_tbl[pricing].productid = $sql_tbl[products].productid AND $sql_tbl[pricing].variantid = 0 AND $sql_tbl[pricing].quantity = 1 AND $sql_tbl[variants].productid IS NULL $where GROUP BY $sql_tbl[products].productid, $sql_tbl[pricing].membershipid");

		$res = db_query("
SELECT
        xcart_products.productid, 
        CONCAT(xcart_pricing.price,'/',0, '/', xcart_pricing.priceid) as priceid
FROM xcart_pricing, xcart_products 
        LEFT JOIN xcart_variants ON xcart_products.productid = xcart_variants.productid
        
WHERE xcart_pricing.productid = xcart_products.productid 
        AND xcart_pricing.variantid = 0 
        AND xcart_variants.productid IS NULL 
	$where
        AND xcart_pricing.quantity<=xcart_products.min_amount 

ORDER By xcart_pricing.quantity DESC
LIMIT 1");

	}

	if ($res) {
		$i = 0;
		while ($arr = db_fetch_array($res)) {

			$i++;
			list($tmp1, $arr['membershipid'], $arr['priceid']) = explode("/", $arr['priceid'], 3);

			func_array2insert("quick_prices", func_addslashes($arr), true);
			if ($tick > 0 && $i % $tick == 0) {
				echo ". ";
				if (($i/$tick) % 100 == 0)
					echo "\n";
				func_flush();
			}
		}

		db_free_result($res);
	}

	if (empty($active_modules['Product_Options']))
		return $i;

	# Get variants' prices
	$res = db_query("SELECT $sql_tbl[products].productid, $sql_tbl[products].min_amount FROM $sql_tbl[products], $sql_tbl[variants] WHERE $sql_tbl[variants].productid = $sql_tbl[products].productid $where GROUP BY $sql_tbl[products].productid");
	if (!$res)
		return $i;

	while ($arr = db_fetch_array($res)) {

		$productid = $arr['productid'];
		$min_amount = $arr['min_amount'];
		$variantid = func_get_default_variantid($productid);
		if (empty($variantid))
			continue;

//		$prices = func_query_hash("SELECT membershipid, priceid FROM $sql_tbl[pricing] WHERE variantid = '$variantid' AND quantity = 1 ORDER BY price", "membershipid", false, true);
		$prices = func_query_hash("SELECT priceid FROM $sql_tbl[pricing] WHERE variantid = '$variantid' AND quantity = 1 ORDER BY price", "membershipid", false, true);
		if (empty($prices))
			continue;

		foreach ($prices as $mid => $priceid) {
			$i++;
			$query_data = array(
				"productid" => $productid,
				"priceid" => $priceid,
//				"membershipid" => $mid,
				"membershipid" => 0,
				"variantid" => $variantid
			);
			func_array2insert("quick_prices", $query_data, true);

			if ($tick > 0 && $i % $tick == 0) {
				func_flush(". ");
			}
		}
	}

	db_free_result($res);

//die("===");
	return $i;
}

#
# Get data cache content and regenerate cache file on demand
#
function func_data_cache_get($name, $params = array(), $force_rebuild = false) {
	global $data_caches, $var_dirs, $xcart_dir, $data_caches_no_save;

	if (!isset($data_caches[$name]) || empty($data_caches[$name]['func']) || !function_exists($data_caches[$name]['func']))
		return false;

	$path = $var_dirs["cache"]."/".$name;
	if (!empty($params)) {
		$path .= ".".implode(".", $params);
	}

	$path .= ".php";
	$no_save = defined("BLOCK_DATA_CACHE_".strtoupper($name));

	if (file_exists($path) && !$force_rebuild && defined("USE_DATA_CACHE") && constant("USE_DATA_CACHE") && !$no_save) {
		if (!@include($path))
			return false;

		return $$name;

	} else {
		$data = call_user_func_array($data_caches[$name]['func'], $params);
		if (defined("USE_DATA_CACHE") && constant("USE_DATA_CACHE") && is_writable($var_dirs["cache"]) && is_dir($var_dirs["cache"]) && !$no_save) {
			@unlink($path);
			$fp = @fopen($path, "w");
			$is_unlink = false;
			if ($fp) {
				if (@fwrite($fp, "<?php\nif (!defined('XCART_START')) { header('Location: ../../'); die('Access denied'); }\n") === false)
					$is_unlink = true;
				if (!$is_unlink && !func_data_cache_write($fp, '$'.$name, $data))
					$is_unlink = true;
				if (!$is_unlink && @fwrite($fp, "?>") === false)
					$is_unlink = true;

				fclose($fp);
			}

			if ($is_unlink)
				@unlink($path);
		}

		return $data;
	}

	return "";
}

#
# Write array to data cache file
#
function func_data_cache_write($fp, $prefix, $data) {
	if (!is_array($data)) {
		fwrite($fp, $prefix.'=');
		if (is_bool($data)) {
			if (@fwrite($fp, ($data ? "true" : "false").";\n") === false)
				return false;
		}
		elseif (is_int($data) || is_float($data)) {
			if (@fwrite($fp, $data.";\n") === false)
				return false;
		}
		else {
			if (@fwrite($fp, '"'.str_replace('"','\"',$data)."\";\n") === false)
				return false;
		}
	} else {
		foreach ($data as $key => $value) {
			if (!func_data_cache_write($fp, $prefix."['".str_replace("'", "\'", $key)."']", $value))
				return false;
		}
	}

	return true;
}

#
# Clear data cache
#
function func_data_cache_clear($name = false) {
	global $data_caches, $var_dirs, $xcart_dir;

    if ($name !== false && (!isset($data_caches[$name]) || empty($data_caches[$name]['func']) || !function_exists($data_caches[$name]['func'])))
        return false;

    $path = $var_dirs["cache"];

	$dir = opendir($path);
	if (!$dir)
		return false;

	while ($file = readdir($dir)) {
		if ($file != '.' && $file != '..' && (($name === false && preg_match("/\.php$/S", $file)) || ($name !== false && strpos($file, $name.".") === 0))) {
			@unlink($path.DIRECTORY_SEPARATOR.$file);
		}
	}

	closedir($dir);

	return true;
}

#
# Erase service array (Group editing of products functionality)
#
function func_ge_erase($geid = false) {
	global $sql_tbl, $XCARTSESSID;

	if (!empty($geid)) {
		db_query("DELETE FROM $sql_tbl[ge_products] WHERE geid = '$geid'");
	} else {
		db_query("DELETE FROM $sql_tbl[ge_products] WHERE sessid = '$XCARTSESSID'");
	}
}

#
# Store temporary data in database for some reason
#
function func_db_tmpwrite($data, $ttl=600) {
	$id = md5(microtime());

	$hash = array (
		'id' => addslashes($id),
		'data' => addslashes(serialize($data)),
		'expire' => time() + $ttl
	);

	func_array2insert('temporary_data', $hash, true);
	return $id;
}

#
# Read previously stored temporary data
#
function func_db_tmpread($id, $destroy=false) {
	global $sql_tbl;

	$tmp = func_query_first_cell("SELECT data FROM $sql_tbl[temporary_data] WHERE id='".addslashes($id)."' LIMIT 1");
	if ($tmp === false)
		return false;

	if ($destroy) {
		db_query("DELETE FROM $sql_tbl[temporary_data] WHERE id='".addslashes($id)."'");
	}

	return unserialize($tmp);
}

#
# Display service page header
#
function func_display_service_header($title = "", $as_text = false) {
	global $smarty;

	if (!defined("BENCH_BLOCK"))
		define("BENCH_BLOCK", true);

	if (!defined("SERVICE_HEADER")) {
		define("SERVICE_HEADER", true);
		set_time_limit(86400);

		func_display("main/service_header.tpl", $smarty);
		func_flush();

		if (!defined("NO_RSFUNCTION"))
			register_shutdown_function("func_display_service_footer");
	}

	if (!empty($title)) {
		if (!$as_text) {
			$title = func_get_langvar_by_name($title, null, false, true);
			if (empty($title))
				return;
		}
		func_flush($title.": ");
	}
}

#
# Display service page footer
#
function func_display_service_footer() {
	global $smarty;

	if (defined("SERVICE_HEADER")) {
		func_display("main/service_footer.tpl", $smarty);
		func_flush();
	}
}

#
# Close current window through JS-code
#
function func_close_window() {
?>
<script type="text/javascript">
<!--
window.close();
-->
</script>
<?php
	exit;
}

#
# This function check user name for belonging to anonymous customers
#
function func_is_anonymous($username) {
	global $anonymous_username_prefix;

	return !strncmp($username, $anonymous_username_prefix, strlen($anonymous_username_prefix));
}

#
# Get value from array with presence check and default value
#
function get_value($array, $index, $default=false) {
	if (isset($array[$index]))
		return $array[$index];

	return $default;
}

#
# Get default image URL
#
function func_get_default_image($type) {
	global $config, $xcart_dir, $xcart_web_dir, $current_area, $HTTPS;

	if (!isset($config['available_images'][$type]) || empty($config['setup_images'][$type]['default_image']))
		return false;

	$default_image = $config['setup_images'][$type]['default_image'];
	if (is_url($default_image)) {
		return $default_image;
	}

	$default_image = func_realpath($default_image);
	if (!strncmp($xcart_dir, $default_image, strlen($xcart_dir)) && @file_exists($default_image)) {
		$default_image = str_replace($xcart_dir, $xcart_web_dir, $default_image);
		if (X_DEF_OS_WINDOWS)
			$default_image = str_replace("\\", "/", $default_image);

	        if ($current_area == "C" && !$HTTPS && !empty($default_image) && !empty($config["Appearance"]["CDN_domain"]) && $config["Appearance"]["Enable_CDN"] == "Y" && strpos($default_image, "default_image.gif")!== false && strpos($default_image, $config["Appearance"]["CDN_domain"]) ===  false){
	                $default_image = $config["Appearance"]["CDN_domain"].$default_image;
	        }

		return $default_image;
	}

	return '';
}

#
# Convert EOL symbols to BR tags
# if content hasn't any tags
#
function func_eol2br($content) {
	return ($content == strip_tags($content)) ? str_replace("\n", "<br />", $content) : $content;
}

#
# Insert the trademark to string (used for shipping methods name)
#
function func_insert_trademark($string, $empty=false, $use_alt=false) {
	$reg = $sm = $tm = "";

	if (!empty($empty)) {
		$reg = "&#174;";
		if (empty($use_alt)) {
			$sm = "<sup>SM</sup>";
			$tm = "<sup>TM</sup>";
		}
		else {
			$sm = " (SM)";
			$tm = " (TM)";
		}
	}

	$result = preg_replace("/##R##/", $reg, $string);
	$result = preg_replace("/##SM##/", $sm, $result);
	$result = preg_replace("/##TM##/", $tm, $result);

	return $result;
}

function func_trim_value(&$value) 
{ 
	$value = mysql_real_escape_string(htmlentities(trim($value))); 
}

function func_json_encode($a = false) {

	if (function_exists('json_encode')) {
		return json_encode($a);
	}
	    
	if (is_null($a)) {
		return 'null';
	}
    
	if ($a === false) {
		return 'false';
	}
    
	if ($a === true) {
		return 'true';
	}

	if (is_scalar($a)) {
		if (is_float($a)) {
        	// Always use "." for floats.
   		    return floatval(str_replace(',', '.', strval($a)));
		}
		if (is_string($a)) {
			static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
			return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
		} else {
			return $a;
		}
		$isList = true;
		$length = count($a);
		for ($i = 0, reset($a); $i < $length; $i++, next($a)) {
			if (key($a) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if ($isList) {
			foreach ($a as $v) {
				$result[] = func_json_encode($v);
			}
			return '[' . join(',', $result) . ']';
		} else {
			foreach ($a as $k => $v) {
				$result[] = func_json_encode($k).':'.func_json_encode($v);
			}
			return '{' . join(',', $result) . '}';
		}
	}
	if (is_array($a)) {
		$result = array();
		foreach ($a as $k => $v) {
			$result[] = '"' . $k . '": ' . func_json_encode($v);
		}
		return '{ '.implode(', ', $result).' }';
	}
}

function func_trim($arr) {
	foreach ($arr as $k => $v) {
		$arr[$k] = trim($v);
	}
	return $arr;
}

#
# Sort array by orderby column
#

function func_sort_arr_by_orderby($a, $b) {
    
    if (!isset($a['orderby']) || !isset($b['orderby'])) {
        return 0;
    }

    if ($a['orderby'] == $b['orderby']) {
        return 0;
    }
    
    return ($a['orderby'] < $b['orderby']) ? -1 : 1;
}

function func_sort_arr_by_orderby_desc($a, $b) {

    if (!isset($a['orderby']) || !isset($b['orderby'])) {
        return 0;
    }

    if ($a['orderby'] == $b['orderby']) {
        return 0;
    }
   
    return ($a['orderby'] < $b['orderby']) ? +1 : -1;
}


function cidev_e404()
{
    header ("HTTP/1.0 404 Not Found", true, 404);
    echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
    <html><head>
    <title>404 Not Found</title>
    </head><body>
    <h1>Not Found</h1>
    <p>The requested URL '.$_SERVER['REQUEST_URI'].' was not found on this server.</p>
    <hr>
    '.$_SERVER['SERVER_SIGNATURE'].'
    <p>'.$_SERVER['HTTP_USER_AGENT'].'</p>
    </body></html>';
    die();
}


/**
 * Check if the word is in the .htaccess file body
 *
 * @param string $word word string
 *
 * @return boolean
 * @see    ____func_see____
 * @since  1.0.0
 */
function func_test_htaccess($word)
{
    global $xcart_dir;
    static $xcart_htaccess = NULL;

    if (is_null($xcart_htaccess)) {
        if (!is_readable($xcart_dir . XC_DS . '.htaccess'))
            return false;

        $xcart_htaccess = @file_get_contents($xcart_dir . XC_DS . '.htaccess');
        $xcart_htaccess = (string)$xcart_htaccess;
    }

    $word = (string)$word;
    if (strpos($xcart_htaccess, $word) === false)
        return false;

    return true;
}

/**
 * Return RewriteBase value for .htaccess
 *
 * @return string
 * @see    ____func_see____
 * @since  1.0.0
 */
function func_get_rewrite_base()
{
    global $http_location;

    $http_location_info = @parse_url($http_location);

    return (
        empty($http_location_info['path'])
            ? '/'
            : rtrim($http_location_info['path'], '/')
        . '/'
    );
}

/**
 * Return Apache 401 issue string for .htaccess
 *
 * @return string
 * @see    ____func_see____
 * @since  1.0.0
 */
function func_get_apache_401_issue()
{
    return
        substr(php_sapi_name(), 0, 6) == 'apache'
        ? 'RewriteCond %{ENV:REDIRECT_STATUS} !^401$'
        : '';
}

/**
 * Translate characters with diacritics to translit
 */
function func_translit($str, $charset = false, $subst_symbol = '_')
{
    global $config;

    static $tr = 0;

    if ($tr === 0) {
        $transl = array (
          '!' => '161',
          '"' => '1066,1098,8220,8221,8222',
          "'" => '1068,1100,8217,8218',
          '\'\'' => '147,148',
          '(R)' => '174',
          '(TM)' => '153,8482',
          '(c)' => '169',
          '+-' => '177',
          $subst_symbol => '32,47,92,172,173,8211', # Replace spaces/slashes by the $subst_symbol('_' by default)
          '.' => '183',
          '...' => '8230',
          '0/00' => '8240',
          '<' => '8249',
          '<<' => '171',
          '>' => '8250',
          '>>' => '187',
          '?' => '191',
          'A' => '192,193,194,195,196,197,256,258,260,1040,7840,7842,7844,7846,7848,7850,7852,7854,7856,7858,7860,7862',
          'AE' => '198',
          'B' => '1041,1042',
          'C' => '199,262,264,266,268,1062',
          'CH' => '1063',
          'Cx' => '264',
          'D' => '208,270,272,1044',
          'D%' => '1026',
          'DS' => '1029',
          'DZ' => '1039',
          'E' => '200,201,202,203,274,276,278,280,282,1045,7864,7866,7868,7870,7872,7874,7876,7878',
          'EUR' => '128,8364',
          'F' => '1060',
          'G' => '284,286,288,290,1043',
          'G%' => '1027',
          'G3' => '1168',
          'Gx' => '284',
          'H' => '292,294,1061',
          'Hx' => '292',
          'I' => '204,205,206,207,296,298,300,302,304,1048,7880,7882',
          'IE' => '1028',
          'II' => '1030',
          'IO' => '1025',
          'J' => '308,1049',
          'J%' => '1032',
          'Jx' => '308',
          'K' => '310,1050',
          'KJ' => '1036',
          'L' => '163,313,315,317,319,321,1051',
          'LJ' => '1033',
          'M' => '1052',
          'N' => '209,323,325,327,330,1053',
          'NJ' => '1034',
          'No.' => '8470',
          'O' => '164,210,211,212,213,214,216,332,334,336,416,467,1054,7884,7886,7888,7890,7892,7894,7896,7898,7900,7902,7904,7906',
          'OE' => '140,338',
          'P' => '222,1055',
          'R' => ',340,342,344,1056',
          'S' => '138,346,348,350,352,1057',
          'SCH' => '1065',
          'SH' => '1064',
          'Sx' => '348',
          'T' => '354,356,358,1058',
          'Ts' => '1035',
          'U' => '217,218,219,220,360,362,364,366,368,370,431,1059,7908,7910,7912,7914,7916,7918,7920',
          'Ux' => '364',
          'V' => '1042',
          'V%' => '1038',
          'W' => '372',
          'Y' => '159,221,374,376,1067,7922,7924,7926,7928',
          'YA' => '1071',
          'YI' => '1031',
          'YU' => '1070',
          'Z' => '142,377,379,381,1047',
          'ZH' => '1046',
          '`' => '8216',
          '`E' => '1069',
          '`e' => '1101',
          'a' => '224,225,226,227,228,229,257,259,261,1072,7841,7843,7845,7847,7849,7851,7853,7855,7857,7859,7861,7863',
          'ae' => '230',
          'b' => '1073,1074',
          'c' => '162,231,263,265,267,269,1094',
          'ch' => '1095',
          'cx' => '265',
          'd' => '271,273,1076',
          'd%' => '1106',
          'ds' => '1109',
          'dz' => '1119',
          'e' => '232,233,234,235,275,277,279,281,283,1077,7865,7867,7869,7871,7873,7875,7877,7879',
          'f' => '131,402,1092',
          'g' => '285,287,289,291,1075',
          'g%' => '1107',
          'g3' => '1169',
          'gx' => '285',
          'h' => '293,295,1093',
          'hx' => '293',
          'i' => '236,237,238,239,297,299,301,303,305,1080,7881,7883',
          'ie' => '1108',
          'ii' => '1110',
          'io' => '1105',
          'j' => '309,1081',
          'j%' => '1112',
          'jx' => '309',
          'k' => '311,312,1082',
          'kj' => '1116',
          'l' => '314,316,318,320,322,1083',
          'lj' => '1113',
          'm' => '1084',
          'mu' => '181',
          'n' => '241,324,326,328,329,331,1085',
          'nj' => '1114',
          'o' => '186,176,242,243,244,245,246,248,333,335,337,417,449,1086,7885,7887,7889,7891,7893,7895,7897,7899,7901,7903,7905,7907',
          'oe' => '156,339',
          'p' => '167,182,254,1087',
          'r' => '341,343,345,1088',
          's' => '154,347,349,351,353,1089',
          'sch' => '1097',
          'sh' => '1096',
          'ss' => '223',
          'sx' => '349',
          't' => '355,357,359,1090',
          'ts' => '1115',
          'u' => '249,250,251,252,361,363,365,367,369,371,432,1091,7909,7911,7913,7915,7917,7919,7921',
          'ux' => '365',
          'v' => '1074',
          'v%' => '1118',
          'w' => '373',
          'y' => '253,255,375,1099,7923,7925,7927,7929',
          'ya' => '1103',
          'yen' => '165',
          'yi' => '1111',
          'yu' => '1102',
          'z' => '158,378,380,382,1079',
          'zh' => '1078',
          '|' => '166',
          '~' => '8212',
        );

        $tr = array();

        foreach ($transl as $letter => $set) {

            $letters = explode(",", $set);

            foreach ($letters as $v) {

                if ($v < 256) $tr[chr($v)] = $letter;

                $tr["&#" . $v . ";"] = $letter;

            }

        }

        // Add ASCII symbols not mentioned above
        for ($i = 0; $i < 256; $i++) {
            if (empty($tr["&#" . $i . ";"]))
                $tr["&#" . $i . ";"] = chr($i);
        }
    }

    if ($charset === false) {
        $charset = isset($config['db_charset'])
            ? $config['db_charset']
            : "ISO-8859-1";
    }

    if (
        strtolower($charset) != "iso-8859-1"
        && function_exists('mb_encode_numericentity')
    ) {
        $str = @mb_encode_numericentity($str, array (0x0, 0xffff, 0, 0xffff), $charset);

    } elseif (
        strtolower($charset) != "iso-8859-1"
        && function_exists('iconv')
    ) {
        $str = @iconv($charset, "ISO-8859-1//TRANSLIT", $str);
    }

    // Cannot be translited
    if (empty($str))
        return $str;

    return strtr($str, $tr);
}

/**
 * Check - is background request or not
 */
function func_is_ajax_request()
{
    return (
            isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
        ) || (
            isset($_GET['is_ajax'])
            && $_GET['is_ajax'] == 'Y'
        );
}

/**
 * Display 404 page using skin/common_files/404/404_<language prefix>.html file depending on current store language
 */
function func_page_not_found()
{
    global $_COOKIE, $xcart_dir, $xcart_web_dir, $xcart_http_host, $xcart_https_host, $smarty_skin_dir, $HTTPS, $alt_skin_dir;

    if (
        isset($_COOKIE['multiskin'])
        && !empty($_COOKIE['multiskin'])
        && defined('DEMO_MODE')
    ) {
        $smarty_skin_dir = '/' . $_COOKIE["multiskin"];
    }

    $dir_404 = XC_DS . '404' . XC_DS;

    $store_language = isset($_COOKIE['store_language']) ? $_COOKIE['store_language'] : 'en';

    $is_found = false;

    $filename404 = $dir_404 . '404_' . $store_language . '.html';


    $alt_skin_dir = $xcart_dir."/skin1_kolin";
    $smarty_skin_dir = "/skin1_kolin";

    if (
        !empty($alt_skin_dir)
        && is_dir($alt_skin_dir . $dir_404)
    ) {
        $dir_404 = $alt_skin_dir . $dir_404;
        $filename404 = $alt_skin_dir . $filename404;
    } else {
        $dir_404 = $xcart_dir . $smarty_skin_dir . $dir_404;
        $filename404 = $xcart_dir. $smarty_skin_dir . $filename404;
    }

    $is_found = true;

//func_print_r($filename404);

    if (!is_file($filename404)) {

        $skin_dir404 = @opendir($dir_404);

        $is_found = false;

        while (
            $skin_dir404
            && (false !== ($file = readdir($skin_dir404)))
            && !$is_found
        ) {

            if (
                is_file($dir_404 . $file)
                && preg_match('/404_/', $file)
            ) {

                $is_found = true;

                $filename404 = $dir_404 . $file;

            }

        }

    }

    if ($HTTPS)
        $base_replacement = '<base href="https://' . $xcart_https_host . $xcart_web_dir . '/"';
    else
        $base_replacement = '<base href="http://' . $xcart_http_host . $xcart_web_dir . '/"';

    @header("HTTP/1.0 404 Not Found");

    if ($is_found) {

        echo preg_replace('/<base\s+href=".*"/USs', $base_replacement , implode("", file($filename404)));
    }

    exit;
}

/**
 * Returns location of the certain area by area type
 *
 * @param string $area  Area (User type)
 * @param mixed  $https HTTPS side flag
 *
 * @return void
 * @see    ____func_see____
 */
function func_get_area_catalog($area = 'C', $https = null)
{
    global $xcart_catalogs, $xcart_catalogs_insecure, $xcart_catalogs_secure, $active_modules;

    $catalogs = array (
        'A' => 'admin',
        'P' => 'provider',
        'C' => 'customer',
        'B' => 'partner',
    );

    if (!empty($active_modules['Simple_Mode']) && $area == 'P') {
        // TODO: by flag? or remove this condition

        $area = 'A';
    }

    if (!in_array($area, array('A','P','C','B'))) {
        $area = 'C';
    }

    $result = $xcart_catalogs[$catalogs[$area]];

    if (true === $https) {

        $result = $xcart_catalogs_secure[$catalogs[$area]];

    } elseif (false === $https) {

        $result = $xcart_catalogs_insecure[$catalogs[$area]];

    }

    return $result;
}

/**
 * Display error page using message.html file
 */
function func_show_error_page($title, $message = '', $extra_info = "")
{
    global $xcart_dir;

    $xcart_home = func_get_xcart_home();

    $output = func_file_get($xcart_dir . '/message.html', true);

    $output = (is_string($output) && strstr($output,"%MESSAGE%"))
        ? str_replace(
            array(
                "%XCART_HOME%",
                "%TITLE%",
                "%MESSAGE%",
                "%EXTRA_INFO%",
            ),
            array(
                $xcart_home,
                $title,
                $message,
                $extra_info,
            ),
            $output
        )
        : "<h1>" . $title . "</h1>\n<p>" . $message . "</p>\n<p>" . $extra_info . "</p>";

    die($output);
}

/**
 * Calculate the root web-directory of X-Cart
 */
function func_get_xcart_home()
{
    global $PHP_SELF;

    $xcart_home = substr($PHP_SELF, 0, strrpos($PHP_SELF,'/'));

    $removeCase = array(
        'C'    => DIR_CUSTOMER,
        'A'    => DIR_ADMIN,
        'P'    => DIR_PROVIDER,
        'B'    => DIR_PARTNER,
    );

    if (defined('AREA_TYPE') && isset($removeCase[constant('AREA_TYPE')]))
        $remove = $removeCase[constant('AREA_TYPE')];
    else
        $remove = '';

    if (!empty($remove)) {
        $xcart_home = preg_replace("/\/*" . preg_quote($remove, '/') . "\/*$/", '', $xcart_home);
    }

    return $xcart_home;
}

function my_array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function func_log_order($orderid, $type, $log, $login="") {
	global $sql_tbl;

	db_query("INSERT INTO $sql_tbl[order_logs] (orderid, type, date, login, log) VALUES ('$orderid', '$type', '".time()."', '".addslashes($login)."', '".addslashes($log)."')");
//	db_query("INSERT INTO $sql_tbl[order_logs] (orderid, type, date, login, log) VALUES ('$orderid', '$type', '".time()."', '".addslashes($login)."', '".$log."')");
}

function func_log_order_groups($query_data, $orderid, $manufacturerid, $type, $login="") {
	global $sql_tbl;

        $log = "";
        $log_name = array("shipping_net", "shippingid", "shipping", "bd_status", "cb_status", "dc_status", "acc_paymentid", "po_status", "ru_status", "accounting_net_0", "accounting_gst_0", "accounting_pst_0", "accounting_gross_0", "accounting_net_1_cost_to_us", "accounting_gst_1_cost_to_us", "accounting_pst_1_cost_to_us", "accounting_gross_1_cost_to_us", "accounting_net_2_shipping", "accounting_gst_2_shipping", "accounting_pst_2_shipping", "accounting_gross_2_shipping", "accounting_net_3_ref_to_cust", "accounting_gst_3_ref_to_cust", "accounting_pst_3_ref_to_cust", "accounting_gross_3_ref_to_cust", "accounting_net_4_ref_to_us", "accounting_gst_4_ref_to_us", "accounting_pst_4_ref_to_us", "accounting_gross_4_ref_to_us", "accounting_net_5_profit", "accounting_gst_5_profit", "accounting_pst_5_profit", "accounting_gross_5_profit");
        $order_statuses_arr = array("bd_status", "cb_status", "dc_status", "po_status", "ru_status");
	$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

        $insert_log = false;
        foreach ($log_name as $field_in_db){
	    if (isset($query_data[$field_in_db])){
	        $current = func_query_first_cell("SELECT $field_in_db FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");

		$new_value = $query_data[$field_in_db];

		if (in_array($field_in_db, array("accounting_net_0", "accounting_gst_0", "accounting_pst_0", "accounting_gross_0", "accounting_net_1_cost_to_us", "accounting_gst_1_cost_to_us", "accounting_pst_1_cost_to_us", "accounting_gross_1_cost_to_us", "accounting_net_2_shipping", "accounting_gst_2_shipping", "accounting_pst_2_shipping", "accounting_gross_2_shipping", "accounting_net_3_ref_to_cust", "accounting_gst_3_ref_to_cust", "accounting_pst_3_ref_to_cust", "accounting_gross_3_ref_to_cust", "accounting_net_4_ref_to_us", "accounting_gst_4_ref_to_us", "accounting_pst_4_ref_to_us", "accounting_gross_4_ref_to_us", "accounting_net_5_profit", "accounting_gst_5_profit", "accounting_pst_5_profit", "accounting_gross_5_profit"))){
			$new_value = price_format($new_value);
		}

//                if ($current != $query_data[$field_in_db])
                if ($current != $new_value){

                        if (in_array($field_in_db, $order_statuses_arr)){
	                        $current = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current'");
                                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$new_value'");
                        }

			if ($field_in_db == "acc_paymentid"){
				$current = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$current'");
				$new_value = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$new_value'");
			}

                        $log .= "<B>".$code.":</B> ".$field_in_db.": ".$current." -> ".$new_value."<br />";
                        $insert_log = true;
                }
	    }
	}

        if ($insert_log){
                func_log_order($orderid, $type, $log, $login);
	}
}

function func_log_order_refunded_groups($query_data, $orderid, $manufacturerid, $type, $login="") {
        global $sql_tbl;

        $log = "";
        $log_name = array("shipping_net", "shippingid", "shipping", "ref_ship");
        $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

        $insert_log = false;
        foreach ($log_name as $field_in_db){
	    if (isset($query_data[$field_in_db])){
                $current = func_query_first_cell("SELECT $field_in_db FROM $sql_tbl[refund_groups] WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");
                if ($current != $query_data[$field_in_db]){
                        $new_value = $query_data[$field_in_db];

                        $log .= "<B>".$code.":</B> (refund) ".$field_in_db.": ".$current." -> ".$new_value."<br />";
                        $insert_log = true;
                }
	    }
        }

        if ($insert_log){
                func_log_order($orderid, $type, $log, $login);
        }
}

/**
 * Items is a numeric array where value is an assoc array of the following options:
 * - type          - can be P(product)|C(category)|M(manufacturer)|S(static page)|H(home page)|E(extra URL)
 * - lastmod       - this value will be used _if_ item contain no entry in the xcart_xmlmap_lastmod table. Value should utilize ISO 8601 format: YYYY-MM-DDThh:mmTZD. If empty, the sitemap generation time will be used.
 * - changefreq    - can be always|hourly|daily|weekly|monthly|yearly|never.
 * - priority      - from 1.0 (extremely important) to 0.1 (not important at all).
 * - url_pattern   -
 * - items_query   - query to the database which will return list of items for the defined type
 * - multilanguage - can be true|false
 * @link http://www.google.com/support/webmasters/bin/answer.py?answer=71936
 */

function func_XML_Sitemap_items_arr($sf_condition){
        global $config, $xcart_catalogs, $sql_tbl;

        $config_XML_Sitemap_items = array(
        0 => array(
                'type'          => 'C',
                'lastmod'       => '',
                'changefreq'    => 'weekly',
                'priority'      => '0.6',
                'url_pattern'   => 'home.php?cat=',
                'items_query'   => "SELECT SQL_NO_CACHE CONCAT('%s', $sql_tbl[categories].categoryid) as url, $sql_tbl[categories].categoryid as id,  IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[categories].categoryid AND $sql_tbl[xmlmap_lastmod].type = 'C' WHERE $sql_tbl[categories].avail='Y'" . ((empty($sf_condition)) ? '' : " AND $sql_tbl[categories].$sf_condition"),
                'multilanguage' => false,
        ),
        1 => array(
                'type'          => 'P',
                'lastmod'       => '',
                'changefreq'    => 'daily',
                'priority'      => '0.9',
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
                'priority'      => '0.6',
                'url_pattern'   => 'brands.php?brandid=',
                'multilanguage' => false,
        ),
        3 => array(
                'type'          => 'S',
                'lastmod'       => '',
                'changefreq'    => 'never',
                'priority'      => '0.2',
                'url_pattern'   => 'pages.php?pageid=',
                'items_query'   => "SELECT SQL_NO_CACHE CONCAT('%s', $sql_tbl[pages].pageid) as url, $sql_tbl[pages].pageid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[pages] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[pages].pageid AND $sql_tbl[xmlmap_lastmod].type = 'S' WHERE $sql_tbl[pages].active='Y' AND $sql_tbl[pages].level='E'",
                'multilanguage' => false,
        ),
        4 => array(
                'type'          => 'E',
                'lastmod'       => '',
                'changefreq'    => 'monthly',
                'priority'      => '0.4',
                'url_pattern'   => '',
                'items_query'   => "%s SELECT SQL_NO_CACHE url, '%s' as date FROM $sql_tbl[xmlmap_extra]" . ((empty($sf_condition)) ? '' : " WHERE $sf_condition"),
                'multilanguage' => false,
        ),
        5 => array(
                'type'          => 'H',
                'lastmod'       => '',
                'changefreq'    => 'daily',
                'priority'      => '1.0',
                'url_pattern'   => '',
                'items_query'   => "%s SELECT SQL_NO_CACHE IF((SELECT value FROM $sql_tbl[config] WHERE name = 'xseo_xmlmap_use_root') = 'Y','$GLOBALS[http_location]','home.php') as url, '%s' as date",
                'multilanguage' => false,
        ),
        6 => array(
                'type'          => 'K',
                'lastmod'       => '',
                'changefreq'    => 'weekly',
                'priority'      => '0.8',
                'url_pattern'   => '',
//                'items_query'   => "%s SELECT SQL_NO_CACHE IF((SELECT value FROM $sql_tbl[config] WHERE name = 'xseo_xmlmap_use_root') = 'Y','$GLOBALS[http_location]','home.php') as url, '%s' as date",
                'multilanguage' => false,
        ),
        );

        return $config_XML_Sitemap_items;
}

function url_exists($url) {
    $hdrs = @get_headers($url);
    return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}

function func_google_phone_and_area_code($google_phone, $phone_ext){
	global $config;

        $userinfo_area_code = "";

        $google_phone = preg_replace("/[^0-9]/S","", $google_phone);

        $google_phone_strlen = strlen($google_phone);

        if ($google_phone_strlen == 11 && $google_phone{0} == "1"){
                $google_phone{0} = "";
                $google_phone = trim($google_phone);
                $google_phone_strlen = strlen($google_phone);
        }

        if ($google_phone_strlen >= 10){

                $tmp_counter = 0;
                $google_phone_new = "";
                for ($i=$google_phone_strlen; $i>=0; $i--){

                        if ($tmp_counter > 7 && $tmp_counter <= 10){
                                $userinfo_area_code = $google_phone{$i}.$userinfo_area_code;
                        }

                        $google_phone_new = $google_phone{$i}.$google_phone_new;

                        if ($tmp_counter == 4){
                                $google_phone_new = "-".$google_phone_new;
                        }

                        if ($tmp_counter == 7){
                                $google_phone_new = ") ".$google_phone_new;
                        }

                        if ($tmp_counter == 10){
                                $google_phone_new = "(".$google_phone_new;

                                if ($google_phone_strlen > 10){
                                        $google_phone_new = "] ".$google_phone_new;
                                }
                        }

                        $tmp_counter++;
                }

                if ($google_phone_strlen > 10){
                        $google_phone_new = "[+".$google_phone_new;

                        $google_phone_new = urlencode($google_phone_new);
                }

                $google_phone = $google_phone_new;
        }

//func_print_r($google_phone, $google_phone_new, $userinfo_area_code);

        $google_phone = $google_phone . (!empty($phone_ext) ? " ext $phone_ext": "");
        $google_phone =  str_replace(" ", "+", $google_phone);

        $fraud_Google_phone_search_exclusions = trim($config["Fraud_check"]["fraud_Google_phone_search_exclusions"]);
        if (!empty($fraud_Google_phone_search_exclusions)){
                $fraud_Google_phone_search_exclusions = str_replace(",", "+-", $fraud_Google_phone_search_exclusions);
                $fraud_Google_phone_search_exclusions = str_replace(" ", "+", $fraud_Google_phone_search_exclusions);
                $fraud_Google_phone_search_exclusions = "+-".$fraud_Google_phone_search_exclusions;
        }

        $google_phone .= $fraud_Google_phone_search_exclusions;

	$result["google_phone"] = $google_phone;
	$result["userinfo_area_code"] = $userinfo_area_code;

	return $result;
}

function func_phone_or_fax_area_code_info($po_fax_number){
        global $sql_tbl;

	$po_fax_area_code = "";

        $po_fax_number = preg_replace("/[^0-9]/S","", $po_fax_number);
        $po_fax_number_strlen = strlen($po_fax_number);

        if ($po_fax_number_strlen == 11 && $po_fax_number{0} == "1"){
                $po_fax_number{0} = "";
                $po_fax_number = trim($po_fax_number);
                $po_fax_number_strlen = strlen($po_fax_number);
        }

        if ($po_fax_number_strlen >= 10){
                $tmp_counter = 0;
                for ($i=$po_fax_number_strlen; $i>=0; $i--){
                        if ($tmp_counter > 7 && $tmp_counter <= 10){
                                $po_fax_area_code = $po_fax_number{$i}.$po_fax_area_code;
                        }
                        $tmp_counter++;
                }

                $po_fax_area_codes = func_query_first("SELECT * FROM $sql_tbl[Telephone_area_codes] WHERE area_code='".addslashes($po_fax_area_code)."'");

                if (!empty($po_fax_area_codes)){
                        $po_fax_area_code_info = $po_fax_area_codes["area"] . " (".trim($po_fax_area_codes["state"]) . ")";
                }
        }

	return $po_fax_area_code_info;
}

function func_define_approximate_shippings($productid, $product_info=''){
	global $sql_tbl, $intershipper_rates;

#
##
###
	global $all_approximation_shipping_rates, $all_manufacturer_info, $two_shippings;

	if (empty($product_info)){
		$product_info = func_query_first("SELECT * FROM $sql_tbl[products] WHERE productid='$productid'");
		$product_info["price"] = func_query_first_cell("SELECT price FROM $sql_tbl[pricing] WHERE productid='$productid' AND quantity <= '$product_info[min_amount]' AND variantid = '0' AND membershipid = '0' ORDER BY quantity DESC LIMIT 1");
	}

	if (!empty($all_approximation_shipping_rates[$product_info["manufacturerid"]])){
		$approximation_shipping_rates = $all_approximation_shipping_rates[$product_info["manufacturerid"]];
	}
	else {
		$approximation_shipping_rates = func_query("SELECT * FROM $sql_tbl[approximation_shipping_rates] WHERE manufacturerid='$product_info[manufacturerid]' ORDER BY last_updated_date");
	}

	if (!empty($all_manufacturer_info[$product_info["manufacturerid"]])){
		$manufacturer_info = $all_manufacturer_info[$product_info["manufacturerid"]];
	} else {
		$manufacturer_info = func_query_first("SELECT manufacturer, m_city, m_country, m_state, m_zipcode FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
	}

//	$count_approximation_shipping_rates = count($approximation_shipping_rates);

#
##
###
//$approximation_shipping_rates=""; // <-----------DELETE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! It is just for tests !!!
	$ORIGINAL_approximation_shipping_rates_empty_flag = false;
	if (empty($approximation_shipping_rates)){
		$states = func_query("SELECT * FROM $sql_tbl[states] WHERE country_code='US' AND base_state_zipcode!=''");

		$ORIGINAL_approximation_shipping_rates_empty_flag = true;
		$approximation_shipping_rates = $states;
	}
###
##
#


	$shippings_str = "";

	if (!empty($approximation_shipping_rates)){

		$current_time = time();
		$max_time = 60*60*24*30*2; //2 months

		$time_diff_ok = true;




		foreach ($approximation_shipping_rates as $k => $v){

//if ($v["state"] != "AK")
//continue;

            $shipping_name = "";
            $Shipping_charge = "0.00";
            $shipping_currency = "";
			$shipping_currency = "USD";

		    if ($product_info["free_ship_zone"] != "14" && $product_info["free_ship_zone"] != "15" ){

                if (!$ORIGINAL_approximation_shipping_rates_empty_flag){
                    $diff_date = $current_time - $v["last_updated_date"];

                    if ($diff_date > $max_time){
                    $time_diff_ok = false;
                    break;
                    }
                }


#
##
###
                if (empty($product_info["weight"]) || $product_info["weight"] == "0.00"){
                        $product_info["weight"] = "0.1";
                }


                if ($manufacturer_info['m_country'] == "US"){
                    $shipping_id = 1;
                    $shipping_name = "Ground";

###
                    $real_weight = $product_info["weight"];
                    $Volume = $product_info["dim_x"]*$product_info["dim_y"]*$product_info["dim_z"];

                    if ($Volume > $two_shippings[$shipping_id]["vol_threshold"] && !empty($two_shippings[$shipping_id]["dim_factor"])){
                        $bw = $Volume/$two_shippings[$shipping_id]["dim_factor"];
                        $weight = max($real_weight, $bw);
                    } else {
                        $weight = $real_weight;
                    }

                    $weight = ceil($weight);

                    $product_info["weight"] = $weight;
###

                } 
                elseif ($manufacturer_info['m_country'] == "CA"){
                        $shipping_id = 65;
                        $shipping_name = "Standard";
                }


                if (!$ORIGINAL_approximation_shipping_rates_empty_flag){

                    if ($product_info["weight"] > 0 && $product_info["weight"] <= 1){
                        $Shipping_charge = $v["bw_1"];
                    }
                    elseif ($product_info["weight"] > 1 && $product_info["weight"] <= 75){
                        $Shipping_charge = $v["bw_1"] + ($v["bw_75"] - $v["bw_1"])/(75 - 1) * ($product_info["weight"] - 1);
                    }
                    elseif ($product_info["weight"] > 75){
                        $Shipping_charge = $v["bw_75"] + ($v["bw_150"] - $v["bw_75"])/(150 - 75) * ($product_info["weight"] - 75);
                    }

                    $Shipping_charge = price_format($Shipping_charge);


                    $intershipper_rates[0]["methodid"] = $shipping_id;
                    $intershipper_rates[0]["rate"] = $Shipping_charge;

                    $approximation_intershipper_rates = $intershipper_rates;
			}
			else {
                    $approximation_intershipper_rates = "";
                    $v["state"] = $v["code"];
			}

			$customer_info["b_state"] = $customer_info["s_state"] = $v["state"];
			$customer_info["b_country"] = $customer_info["s_country"] = "US";
			$customer_info["b_zipcode"] = $customer_info["s_zipcode"] = func_query_first_cell("SELECT base_state_zipcode FROM $sql_tbl[states] WHERE code='$v[state]' AND country_code='US'");

			$product_info["amount"] = 1;
			$product_info["subtotal"] = $product_info["price"];
			$products[0] = $product_info;

//func_print_r($intershipper_rates, $customer_info);
//print"---->";
//func_print_r($approximation_intershipper_rates);

#
##
			$customer_zone_ship_for_flat_rate = func_get_customer_zone_ship($customer_info, "master", "D", $product_info["manufacturerid"]);

			$flat_rate_shipping_cost = "";
			$flat_rate_shippings = func_query($query = "SELECT * FROM $sql_tbl[shipping_rates] WHERE zoneid='$customer_zone_ship_for_flat_rate' AND provider='master' AND mintotal<='$product_info[price]' AND maxtotal>='$product_info[price]' AND minweight<='$product_info[weight]' AND maxweight>='$product_info[weight]' AND type='D' AND manufacturerid='$product_info[manufacturerid]' ORDER BY maxtotal, maxweight");
			if (!empty($flat_rate_shippings)){
				foreach ($flat_rate_shippings as $k_fr => $v_fr){
					$flat_rate_shippings[$k_fr]["shipping_cost"] = $v_fr["cost_marcup"] + $v_fr['rate'] + $v_fr['weight_rate']*$product_info["weight"] + $v_fr["item_rate"]*$product_info["min_amount"] + $product_info["price"]*$v_fr['rate_p'] / 100 + $product_info["shipping_freight"];
				}

				$flat_rate_shippings = my_array_sort($flat_rate_shippings, "shipping_cost");
				$flat_rate_shipping_cost = price_format($flat_rate_shippings[0]["shipping_cost"]);
			}
##
#

			if (!$ORIGINAL_approximation_shipping_rates_empty_flag){
				$Add_Shipping_rate = func_calculate_shippings($products, $shipping_id, $customer_info, "master", $approximation_intershipper_rates);
				unset($approximation_intershipper_rates);

//func_print_r($Add_Shipping_rate);
//die();

				if (!empty($Add_Shipping_rate["shipping_cost"]) && $Add_Shipping_rate["shipping_cost"] > 0){
					$Shipping_charge = $Add_Shipping_rate["shipping_cost"];
					$Shipping_charge = price_format($Shipping_charge);
				}


				if ($flat_rate_shipping_cost != ""){
					$Shipping_charge = min($Shipping_charge, $flat_rate_shipping_cost);
				}
			} 
			else {
				$Shipping_charge = $flat_rate_shipping_cost;
			}
###
##
#
		    }

		    $shippings_str_arr[$k] = "US:".$v["state"].":".$shipping_name.":".$Shipping_charge.$shipping_currency;

		    $shippings_google_arr[$k]["price"]["value"] = $Shipping_charge;
		    $shippings_google_arr[$k]["price"]["currency"] = trim($shipping_currency);
		    $shippings_google_arr[$k]["country"] = "US";
		    $shippings_google_arr[$k]["region"] = $v["state"];
		    $shippings_google_arr[$k]["service"] = $shipping_name;

		}

		if ($time_diff_ok){
			$shippings_str = implode(",", $shippings_str_arr);
		}

//func_print_r($shippings_str, $shippings_str_arr, $product_info["free_ship_zone"]);
//die();

	}

	$shippings["shippings_str"] = $shippings_str;
	$shippings["shippings_google_arr"] = $shippings_google_arr;
	return $shippings;
}

function func_pc_find_new_categoryid($productid){
	global $sql_tbl;

	$product = func_query_first("SELECT product, fulldescr FROM $sql_tbl[products] WHERE productid='$productid'");
//func_print_r($product);	
	$sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid'");
	$pc_options = func_query_first("SELECT * FROM $sql_tbl[pc_options] WHERE storefrontid='$sfid'");

	$text = $product["product"] . " " . $product["product"] . " " . $product["fulldescr"];
	$text = func_del_excluded_char_sequences($text, $pc_options["excluded_char_sequences"]);
	$text = func_del_stop_words($text, $pc_options["stop_words"]);

//func_print_r($text);

	if (empty($text)){
		return false;
	}

	$text_arr = explode(" ", $text);

	$categories = db_query($query="SELECT categoryid, pc_category_weight FROM $sql_tbl[categories] WHERE pc_ready_to_classify='Y' AND avail='Y' AND storefrontid='$sfid' AND pc_category_weight != 0");

	$idxcl = 0;

	while ($category = db_fetch_array($categories)) {

		$p1 = 0;
		$idxcl++;

		$categoryid = $category["categoryid"];
//print "Category \r\n";
//func_print_r($categoryid);

		$pc_category_weight = $category["pc_category_weight"];

//		$current_category_terms = func_query("SELECT $sql_tbl[pc_terms].term FROM $sql_tbl[pc_terms] LEFT JOIN $sql_tbl[pc_category_terms] ON $sql_tbl[pc_terms].termid=$sql_tbl[pc_category_terms].termid WHERE $sql_tbl[pc_category_terms].categoryid='$categoryid' AND $sql_tbl[pc_category_terms].bayes_weight<>0 and $sql_tbl[pc_terms].storefrontid = '$sfid'");
		$current_category_terms = func_query("SELECT $sql_tbl[pc_terms].term FROM $sql_tbl[pc_terms] WHERE $sql_tbl[pc_terms].storefrontid = '$sfid'");

		$current_category_terms_arr = array();
		if (!empty($current_category_terms)){
			foreach ($current_category_terms as $v){
				$current_category_terms_arr[] = $v["term"];
			}
		}

		foreach ($text_arr as $word){
			if (in_array($word, $current_category_terms_arr)){
				// sleep for some time 
				usleep(5000);
				//$termid = func_query_first_cell("SELECT termid FROM $sql_tbl[pc_terms] WHERE term='$word' and $sql_tbl[pc_terms].storefrontid = '$sfid'");
				//$bayes_weight = 0;
				//$bayes_weight = func_query_first_cell("SELECT bayes_weight FROM $sql_tbl[pc_category_terms] WHERE categoryid='$categoryid' AND termid='$termid'");
				$bayes_weight = func_query_first_cell("Select COALESCE(CASE WHEN CT.categoryid is not NULL THEN LOG((Count(CT.termid)+1)/C.pc_z) ELSE LOG(1/C.pc_z) END,0) As bayes_weight from $sql_tbl[pc_terms] T left join $sql_tbl[categories] C ON C.categoryid = '$categoryid' left join $sql_tbl[pc_category_terms] CT ON CT.categoryid = C.categoryid and CT.termid = T.termid where T.term = '$word' and T.storefrontid = '$sfid'");
//func_print_r($word,$termid,$bayes_weight);

				$p1 = $p1 + $bayes_weight;
			}
		}

		if ($pc_category_weight + $p1 == 0) {$p1 = -10000000;}
		$cl[$idxcl]["1"] = $categoryid;
		$cl[$idxcl]["2"] = $pc_category_weight + $p1;

		unset($current_category_terms_arr);
	}

	if (!empty($cl) && is_array($cl)){
//func_print_r($cl);

		foreach ($cl as $k => $v){

			if ($k == "1"){
				$max = $v["2"];
				$new_categoryid = $v["1"];
			}

			if ($max < $v["2"]){

				$prev_max = $max;
				$prev_new_categoryid = $new_categoryid;

				$max = $v["2"];
				$new_categoryid = $v["1"];
			}

		}
	}

#
##
	if (!empty($new_categoryid)){

		$pc_most_relevant_categories = $max.",".$new_categoryid.";".(!empty($prev_max)?$prev_max.",".$prev_new_categoryid:"");

		if ($prev_max == ""){
			$prev_max = 0;
		}

		$pc_delta = ($max - $prev_max)/($max + $prev_max);
		$pc_delta = abs($pc_delta);

		db_query("UPDATE $sql_tbl[products_categories] SET categoryid='$new_categoryid' WHERE productid='$productid' AND main='Y'");
		db_query("UPDATE $sql_tbl[products] SET pc_classify_status='AC', pc_most_relevant_categories='$pc_most_relevant_categories', pc_delta='$pc_delta' WHERE productid='$productid'");

//func_print_r($productid);
//die();
	}
##
#

	return $new_categoryid;
}

function func_del_excluded_char_sequences($text='', $excluded_char_sequences=''){

	$text = trim($text);
	$text = strtolower($text);

	$excluded_char_sequences = trim($excluded_char_sequences);
	$excluded_char_sequences = strtolower($excluded_char_sequences);

        if (empty($text) || empty($excluded_char_sequences))
                return $text;

	$excluded_char_sequences_arr = explode(" ", $excluded_char_sequences);
	foreach ($excluded_char_sequences_arr as $k => $v){
		$char_sequence = trim($v);
		$text = str_replace($char_sequence, " ", $text);
	}

        $text = strip_tags($text);
        $text = htmlspecialchars_decode($text);
	$text = preg_replace("/[\r\n\t]/S", " ", $text);
	$text = preg_replace("/[^0-9a-zA-Z]/S"," ", $text);
	$text = preg_replace('/\s\s+/', ' ', $text);
	$text = trim($text);

        return $text;
}

function func_del_stop_words($text='', $stop_words=''){

        $text = trim($text);
	$text = strtolower($text);

	$stop_words = trim($stop_words);
	$stop_words = strtolower($stop_words);

	if (empty($text) || empty($stop_words))
		return $text;

	$text = " " . $text . " ";

	$stop_words_arr = explode(" ", $stop_words);
	foreach ($stop_words_arr as $k => $v){
		$stop_word = trim($v);
		$stop_word = " ".$stop_word." ";
		$text = str_replace($stop_word, " ", $text);
	}

	$text = preg_replace('/\s\s+/', ' ', $text);
	$text = trim($text);

	return $text;
}

function func_new_mail_notification($v_arr){
	global $sql_tbl;

	if (!empty($v_arr["OrderLink"]) || !empty($v_arr["AmazonOrderLink"])){

		if (!empty($v_arr["OrderLink"])){
			$OrderLink_arr = explode("-", $v_arr["OrderLink"]);
			$orderid = $OrderLink_arr[1];
		} else {
			$orderid = func_query_first_cell("SELECT orderid FROM $sql_tbl[orders] WHERE amazonorderid='".$v_arr["AmazonOrderLink"]."'");
		}

		if (!empty($orderid)){

//			$productid = func_query_first_cell("SELECT productid FROM $sql_tbl[order_details] WHERE orderid='$orderid'");

//			if (!empty($productid)){

//				$sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid'");
//				$status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[otrs_options] WHERE storefrontid='$sfid'");
				$status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[otrs_options]");
				$tag_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='$status_id'");

				$statuses = func_query("SELECT cb_status, dc_status, bd_status FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");

				$log = "New OTRS message notification.<br />";

				$tag_added_flag = false;

				if (!empty($statuses)){
					foreach ($statuses as $k => $v){

						$cb_status = $v["cb_status"];
						$dc_status = $v["dc_status"];
						$bd_status = $v["bd_status"];

						if (
(
($cb_status=="AP" || $cb_status=="P" || $cb_status=="O" || $cb_status=="H" || $cb_status=="R" || $cb_status=="N") && ($dc_status=="C" || $dc_status=="L" || $dc_status=="B" || $dc_status=="G" || $dc_status=="S" || $dc_status=="T" || $dc_status=="E")
)
||
(
$cb_status=="A" || $cb_status=="D"
)
						) {
							db_query("DELETE FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='$status_id'");
							db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '$status_id')");
							$log .= "'".$tag_name."' attention tag set";
							$tag_added_flag = true;

							print("OK");
							break;
						}
	
					}
				} // if (!empty($statuses))

				if (!$tag_added_flag){
					$log .= "'".$tag_name."' attention tag NOT SET based on business rules";
				}

				func_log_order($orderid, 'X', $log, 'OTRS');
                print("OK");

//			} // if (!empty($productid))
		}
	}
	elseif (!empty($v_arr["PQLink"])){
		$PQLink_arr = explode("-", $v_arr["PQLink"]);
		$id = $PQLink_arr[1];
		$id = intval($id);

		db_query("UPDATE $sql_tbl[product_question] SET new_otrs_email='Y' WHERE id='$id'");
		print("OK");
	}
}

function func_parse_sku_quickview($page_content) {
        $result = array();
//        $page_content = str_replace(array("\n", "\r"), '', $page_content);

        preg_match_all('/<span class="sku-quickview">(.*?)<\/span>/', $page_content, $result);

        if (!empty($result[1])) {

                if (isset($result[1][0]) && !empty($result[1][0])){

                        $sku_quickview = trim($result[1][0]);

                        if (!empty($sku_quickview)){
                                return $sku_quickview;
                        }
                }
        }

/*
        preg_match_all('/<select name=".*?BuySizeList".*?>(.*?)<\/select>/', $page_content, $result);
        if (!empty($result[1])) {
                preg_match_all('/<option.*?>(.*?)<\/option>/', $result[1][0], $result);
                return (isset($result[1]) && $result[1]) ? $result[1] : false;
        }
*/
        return false;
}

function func_parse_opConfig($page_content) {
        $result = array();

        preg_match_all('/opConfig = new Product.Options\((.*?)\);/', $page_content, $result);

        if (!empty($result[1])) {

                if (isset($result[1][0]) && !empty($result[1][0])){

                        $opConfig = trim($result[1][0]);

                        if (!empty($opConfig)){
                                return $opConfig;
                        }
                }
        }

	return false;
}

function func_parse_add_to_cart_button($page_content) {
        $result = array();

        preg_match_all('/<button type="button" title="(.*?)"/', $page_content, $result);

        if (!empty($result[1])) {

                if (isset($result[1][0]) && !empty($result[1][0])){

                        $add_to_cart_button = trim($result[1][0]);
                        $add_to_cart_button = strtoupper($add_to_cart_button);

                        if ($add_to_cart_button == "ADD TO CART"){
                                return true;
                        }
                }
        }

        return false;
}

function func_parse_cost_to_us($page_content) {
        $result = array();

        $page_content_arr = explode('name="qty"', $page_content);
        $page_content = $page_content_arr[0];

        preg_match_all('/<span class="price">(.*?)<\/span>/', $page_content, $result);

        if (!empty($result[1])) {

                if (isset($result[1][0]) && !empty($result[1][0])){

                        $cost_to_us = $result[1][0];
                        $cost_to_us = str_replace("$", "", $cost_to_us);
                        $cost_to_us = str_replace(",", "", $cost_to_us);
                        $cost_to_us = trim($cost_to_us);

                        if (!empty($cost_to_us)){
                                return $cost_to_us;
                        }
                }
        }

        return false;
}

function func_clear_str($str) {
        static $tbl = false;

        if ($tbl === false)
                $tbl = array_flip(get_html_translation_table(HTML_ENTITIES));

        $str = str_replace(array("\n","\r","\t"), array(" ", "", " "), $str);
        $str = strip_tags($str);
        $str = strtr($str, $tbl);

        return $str;
}

function func_parse_filters($page_content) {
        $result = array();
        $filters = array();

        $page_content = str_replace(array("<br /><tr>", "<br/><tr>"), '<br /></td></tr>', $page_content);

        preg_match_all('/<td class="key">(.*?)<\/td><\/tr>/', $page_content, $result);

        if (!empty($result[1])) {

                if (isset($result[1]) && !empty($result[1]) && is_array($result[1])){

                        $counter = 0;

                        foreach ($result[1] as $k => $str){

                                $str_arr = explode('</td><td class="value">', $str);

                                $f_name = $str_arr[0];
                                $f_name = func_clear_str($f_name);

                                preg_match('/[a-zA-Z]+/', $f_name, $matches);
//func_print_r($matches);
                                if (empty($matches[0])){
                                        continue;
                                }

                                $f_name = trim($f_name);

                                $f_name_UPPER = strtoupper($f_name);

                                if ($f_name_UPPER == "USE" || empty($f_name)){
                                        continue;
                                }

                                $fv_name_str = $str_arr[1];
                                $fv_name_str = str_replace(array(" or ", " OR ", " Or ", " oR "), ', ', $fv_name_str);

                                $fv_name_str_arr = explode(",", $fv_name_str);

                                $fv_name = array();
                                foreach ($fv_name_str_arr as $kk => $vv){

                                        $vv = func_clear_str($vv);

                                        $vv = trim($vv);

                                        if (!empty($vv)){
                                                $fv_name[] = $vv;
                                        }
                                }

                                if (empty($fv_name)){
                                        continue;
                                }

                                $counter++;

                                $filters[$counter]["f_name"] = $f_name;
                                $filters[$counter]["fv_name_arr"] = $fv_name;

                                unset($fv_name);
                        }
                }
        }

        return $filters;
}


function Get_AB_Variant($point_id){
	global $sql_tbl, $pointid_ab_testing_arr, $is_robot, $current_storefront;
	global $XCART_SESSION_NAME;
	global $$XCART_SESSION_NAME;
	global $XCARTSESSID;
	global $detect_isMobile_was_created;

//	$ab_testing_point = func_query_first("SELECT * FROM $sql_tbl[ab_testing_points] WHERE point_id='$point_id'");	
	$ab_testing_point = func_query_first("SELECT * FROM $sql_tbl[ab_testing_points] WHERE point_id='$point_id' AND enabled='Y'");

	if (empty($ab_testing_point)) {
		return false;
	}

	if (isset($pointid_ab_testing_arr[$point_id])) {
		$variant_id = $pointid_ab_testing_arr[$point_id]["variant_id"];
		return $variant_id;
	}

	$current_time = time();

	$cur_storefront_info = func_get_storefront_info($current_storefront);

	$storefront_prefix = str_replace("-", "", $cur_storefront_info["prefix"]);

	if ($storefront_prefix == "MAIN_SF_PREFIX") {
		$storefront_prefix = "AR";
	}

	if (
			($ab_testing_point["enabled"] == "N") ||
			!($ab_testing_point["point_start_date"] <= $current_time && $current_time <= $ab_testing_point["point_end_date"]) ||
			(strpos($ab_testing_point["storefronts_enabled"], $storefront_prefix) === false) ||
			$is_robot == "Y" ||
			defined("IS_ROBOT") ||
			(empty($$XCART_SESSION_NAME) && empty($XCARTSESSID))||
			($ab_testing_point["exclude_mobile"] == "Y" && $detect_isMobile_was_created)
	) {
		$variant_id = func_query_first_cell("SELECT variant_id FROM $sql_tbl[ab_point_variants] WHERE point_id='$point_id' AND is_default='Y'");
	} else {
		$variant_id = ($ab_testing_point["total_hits"] + 1) % $ab_testing_point["mod_param"];

		if ($variant_id == "") {
			$variant_id = 0;
		}

		$variant_id_balanced = func_query_first_cell("SELECT variant_id FROM $sql_tbl[ab_point_variants] WHERE point_id = $point_id ORDER BY total_hits_count ASC, variant_id = $variant_id DESC");
		if (empty($variant_id_balanced)) $variant_id_balanced = 0;

#
##
###
		/*if ($variant_id > 1) {
			$variant_id = 1;
		}

		$total_hits_count_v0 = func_query_first_cell("SELECT total_hits_count FROM $sql_tbl[ab_point_variants] WHERE point_id='$point_id' AND variant_id='0'");
		$total_hits_count_v1 = func_query_first_cell("SELECT total_hits_count FROM $sql_tbl[ab_point_variants] WHERE point_id='$point_id' AND variant_id='1'");

		if ($total_hits_count_v1 > $total_hits_count_v0 && $variant_id == 1) {
			$variant_id = 0;
		}

		if ($total_hits_count_v0 > $total_hits_count_v1 && $variant_id == 0) {
			$variant_id = 1;
		}*/
###
##
#
		db_query("UPDATE $sql_tbl[ab_testing_points] SET total_hits=total_hits+1 WHERE point_id='$point_id'");
		db_query("UPDATE $sql_tbl[ab_point_variants] SET total_hits_count=total_hits_count+1 WHERE point_id='$point_id' AND variant_id='$variant_id_balanced'");

		$pointid_ab_testing_arr[$point_id]["variant_id"] = $variant_id_balanced;

		x_session_save('pointid_ab_testing_arr');
	}


	return $variant_id;
}

function AB_Goal_Hit($point_id_arr, $orderid=""){
	global $sql_tbl, $pointid_ab_testing_arr, $first_order_total_in_current_session;

	if (empty($point_id_arr) || !is_array($point_id_arr) || empty($pointid_ab_testing_arr) || !is_array($pointid_ab_testing_arr)){
		return false;
	}

#
## if !empty $orderid then it is order invoice page
###    
	if (!empty($orderid)){
		$order_info = func_query_first("SELECT paymentid, is_mobile_checkout FROM $sql_tbl[orders] WHERE orderid='$orderid'");
		$paymentid = $order_info["paymentid"];
		$is_mobile_checkout = $order_info["is_mobile_checkout"];

#		if ($paymentid == "4"){
#			return false;
#		}
	}
###
##
#

	foreach ($point_id_arr as $point_id){
		foreach ($pointid_ab_testing_arr as $k_point_id => $v){
			if ($point_id == $k_point_id){

#
## if !empty $orderid then it is order invoice page
###
                                if (!empty($orderid)){

					$ab_testing_point_info = func_query_first("SELECT exclude_payment_methods, exclude_mobile FROM $sql_tbl[ab_testing_points] WHERE point_id='$point_id'");

					$exclude_payment_methods = explode(",", $ab_testing_point_info["exclude_payment_methods"]);
					if (
						in_array($paymentid, $exclude_payment_methods) ||
						($ab_testing_point_info["exclude_mobile"] == "Y" && $is_mobile_checkout == "Y")
					){
						break;
					}
                                }
###
##
#

				if ($v["reach_goal_count_was_incremented"] != "Y"){
					db_query("UPDATE $sql_tbl[ab_point_variants] SET reach_goal_count=reach_goal_count+1 WHERE point_id='$point_id' AND variant_id='$v[variant_id]'");
					$pointid_ab_testing_arr[$point_id]["reach_goal_count_was_incremented"] = "Y";
				}

				if (!empty($first_order_total_in_current_session) && $v["dollar_amount_of_goal_conversions_was_incremented"] != "Y"){
					db_query("UPDATE $sql_tbl[ab_point_variants] SET dollar_amount_of_goal_conversions=dollar_amount_of_goal_conversions+$first_order_total_in_current_session WHERE point_id='$point_id' AND variant_id='$v[variant_id]'");
					$pointid_ab_testing_arr[$point_id]["dollar_amount_of_goal_conversions_was_incremented"] = "Y";


					$variants_for_current_point = func_query("SELECT * FROM $sql_tbl[ab_point_variants] WHERE point_id='$point_id'");

					if (!empty($variants_for_current_point)){
						$total_goal_conversions = 0;
						$total_dollar_amount_of_goal_conversions = 0;
						$total_success_measure = 0;

						foreach ($variants_for_current_point as $variant){
							$total_goal_conversions += $variant["reach_goal_count"];
							$total_dollar_amount_of_goal_conversions += $variant["dollar_amount_of_goal_conversions"];
						}

                                                foreach ($variants_for_current_point as $k_variant => $variant){

							if (!empty($total_goal_conversions)){
								$k_goal_conversions = $variant["reach_goal_count"] / $total_goal_conversions;
							} else {
								$k_goal_conversions = 0;
							}

							if (!empty($total_dollar_amount_of_goal_conversions)){
								$k_dollar_amount_of_goal_conversions = $variant["dollar_amount_of_goal_conversions"] / $total_dollar_amount_of_goal_conversions;
							} else {
								$k_dollar_amount_of_goal_conversions = 0;
							}

                                                        $variants_for_current_point[$k_variant]["k_goal_conversions"] = $k_goal_conversions;
							$variants_for_current_point[$k_variant]["k_dollar_amount_of_goal_conversions"] = $k_dollar_amount_of_goal_conversions;

							$success_measure = sqrt(pow($k_goal_conversions, 2) + pow($k_dollar_amount_of_goal_conversions, 2));
							$variants_for_current_point[$k_variant]["success_measure"] = $success_measure;

							$total_success_measure += $success_measure;
                                                }

						if ($total_goal_conversions > 0){
							$k_total_goal_conversions = 1/sqrt($total_goal_conversions);
							$k_total_goal_conversions = round($k_total_goal_conversions, 2);
						} else {
							$k_total_goal_conversions = 0;
						}

						foreach ($variants_for_current_point as $k_variant => $variant){
							
							if ($total_success_measure > 0){
								$average_success_measure = $variant["success_measure"]/$total_success_measure;
								$average_success_measure *= 100;
								$average_success_measure = round($average_success_measure);
							} else {
								$average_success_measure = 0;
							}

							$variants_for_current_point[$k_variant]["average_success_measure"] = $average_success_measure;

							$average_success_measure_min = $average_success_measure * (1 - $k_total_goal_conversions);
							$average_success_measure_min = round($average_success_measure_min);
							$variants_for_current_point[$k_variant]["average_success_measure_min"] = $average_success_measure_min;

                                                        $average_success_measure_max = $average_success_measure * (1 + $k_total_goal_conversions);
							$average_success_measure_max = round($average_success_measure_max);
                                                        $variants_for_current_point[$k_variant]["average_success_measure_max"] = $average_success_measure_max;

							$variants_for_current_point[$k_variant]["success_measure_range"] = $average_success_measure_min." ÷ ".$average_success_measure_max;

							$outcome_average_success_measure_max[$k_variant] = $average_success_measure_max;
						}

						arsort($outcome_average_success_measure_max);
						$outcome = 1;
						foreach ($outcome_average_success_measure_max as $k_variant => $vm){
							$variants_for_current_point[$k_variant]["outcome"] = $outcome;
							$outcome++;
						}

                                                foreach ($variants_for_current_point as $k_variant => $variant){
                                                	foreach ($variants_for_current_point as $kk_variant => $v_variant){
								if ($k_variant != $kk_variant){
									if (
										($v_variant["average_success_measure_min"] < $variant["average_success_measure_min"] && $variant["average_success_measure_min"] < $v_variant["average_success_measure_max"])
										||
										($v_variant["average_success_measure_min"] < $variant["average_success_measure_max"] && $variant["average_success_measure_max"] < $v_variant["average_success_measure_max"])

									){
										$variants_for_current_point[$k_variant]["outcome_save_to_db"] = "N";
									}
								}
							}
						}

                                                foreach ($variants_for_current_point as $k_variant => $variant){
							$data_arr["success_measure_range"] = $variant["success_measure_range"];
							$data_arr["average_success_measure"] = $variant["average_success_measure"];
							$data_arr["outcome"] = ($variant["outcome_save_to_db"]=="N" ? "Inconclusive" : $variant["outcome"]);

							func_array2update("ab_point_variants", $data_arr, "point_id='$point_id' AND id='$variant[id]'");
						}
					}
				}
			}
		}
	}

	x_session_save('pointid_ab_testing_arr');
}


function func_get_signature($sfid=false, $products=false) {
	global $sql_tbl, $current_storefront, $config, $login;

	$customer_info = func_query_first("SELECT phone_ext, position FROM $sql_tbl[customers] WHERE login='$login'");

	if ($sfid){
		$use_storefrontid = $sfid;
	}
	elseif (!empty($products) && is_array($products)){
		foreach ($products as $k => $v){
			$use_storefrontid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$v[productid]'");
			break;
		}
	}
	elseif ($current_storefront != ""){
		$use_storefrontid = $current_storefront;
	}

	$cur_storefront_info = func_get_storefront_info($use_storefrontid);

	$signature = $config["Company"]["signature"];
	$signature = str_replace("{{storefront-url}}", "http://".$cur_storefront_info["domain"], $signature);
	$signature = str_replace("{{position}}", $customer_info["position"], $signature);
	$signature = str_replace("{{ext}}", $customer_info["phone_ext"], $signature);

	return $signature;
}

function func_backprocess_log($process_id="", $log_text="") {
        global $sql_tbl;

	db_query("INSERT INTO $sql_tbl[backprocess_logs] (date, process_id, log_text) VALUES ('".time()."','".addslashes($process_id)."','".addslashes($log_text)."')");
}

function func_product_availability($r_avail=false, $price=false, $cost_to_us=false, $eta_date_mm_dd_yyyy=false, $productid=false, $product=false){

        if ($r_avail==false && $price==false && $cost_to_us==false && $eta_date_mm_dd_yyyy==false && $productid==false && $product==false){
                $availability = "out of stock";
                return $availability;
        }

        if (!empty($productid) || (!empty($product) && is_array($product))){

                if (!empty($productid)){

                        if (function_exists('func_select_product')) {
                                x_load("product");
                        }

                        $product = func_select_product($productid, '0', 'false');
                }

                if (
			(empty($product) || !is_array($product))
			||
			($product["shipping_freight"] == "0.00" && strpos($product["productcode"], "ART-") === false)
		){
                        $availability = "out of stock";
                        return $availability;
                }

                $r_avail = $product["r_avail"];
                $price = $product["price"];
                $cost_to_us = $product["cost_to_us"];
                $eta_date_mm_dd_yyyy = $product["eta_date_mm_dd_yyyy"];
        }

        $availability = "in stock";

        if ($r_avail <= 0){
                $availability = "out of stock";
        }

        if ($availability == "in stock" && !empty($eta_date_mm_dd_yyyy)){
//                $eta_date_mm_dd_yyyy_arr = explode("/", $eta_date_mm_dd_yyyy);
//                $eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $eta_date_mm_dd_yyyy_arr[0], $eta_date_mm_dd_yyyy_arr[1], $eta_date_mm_dd_yyyy_arr[2]);
                $current_time = time();

//                if ($current_time < $eta_date_mm_dd_yyyy_time){
                if ($current_time < $eta_date_mm_dd_yyyy){
                        $availability = "out of stock";
                }
        }

        if ($availability == "in stock" && $price != false && $cost_to_us != false){
                if ($cost_to_us > $price){
                        $availability = "out of stock";
                }
        }

        return $availability;
}

function func_decreased_price($cost_to_us, $price, $new_map_price){

        $max1 = $cost_to_us + ($price - $cost_to_us)/3;
        $new_price = max($new_map_price, $max1);
        $new_price = number_format(round($new_price, 2), 2, ".", "");
        return $new_price;
}

function func_GetAAJ_product_info($supplier_internal_id, $supplier_internal_option=''){

		x_load("http");
/*
                $post = array();
                $post[] = "form_key=WJrDj5WRAUur1ndq";
                $post[] = "product=".$supplier_internal_id;
                $post[] = "real_product=".$supplier_internal_id;
                $post[] = "super_attribute%5B1284%5D=11973";
                $post[] = "options%5B449924%5D=1";
                $post[] = "qty=0";
                $post = implode("&", $post);
*/
                $post = array();
                $post[] = "product=".$supplier_internal_id;
                $post[] = "real_product=".$supplier_internal_id;
		$post[] = "related_product=";
		$post[] = "options%5B".$supplier_internal_option."%5D=1";
		$post[] = "qty=1";
                $post = implode("&", $post);

                list($a1,$data,$a2)=func_http_post_request("www.aajewelry.com","/aajewelry/simpleproduct/loadproduct/",$post);

                $loadproduct = json_decode($data, true);

		$unit_price = strip_tags($loadproduct["product"]["options"]["0"]["unit_price"]);
		$unit_price = str_replace(array("$",","), "", $unit_price);

		$group_price = "";
		$discount_min_qty = 0;
		if (!empty($loadproduct["product"]["discount"]) && (is_array($loadproduct["product"]["discount"]))) {
			$group_price = $loadproduct["product"]["discount"]["0"]["group_price"];
			$group_price = str_replace(array("$",","), "", $group_price);
			$discount_min_qty = $loadproduct["product"]["discount"]["0"]["min_qty"];
			$discount_min_qty = str_replace(array("$",","), "", $discount_min_qty);
		    }
		

		if (($loadproduct["product"]["options"]["0"]["uom"] == "SQINCH") || (!empty($loadproduct["product"]["options"]) && is_array($loadproduct["product"]["options"])) && ($loadproduct["product"]["options"]["0"]["qty"] == "0" || $loadproduct["product"]["options"]["0"]["qty_unit"] == "0" || empty($unit_price) || $unit_price == "0.00")){
			$instock = "N";
		}
		else {
			$instock = "Y";
			$unit = $loadproduct["product"]["options"]["0"]["uom"];
			$min_amount = ceil($loadproduct["product"]["options"]["0"]["qty_unit"]);
			if (($unit == 'EA' || $unit == 'CASE' || $unit == 'GROSS' || $unit == 'GRS' || $unit == 'PK') && $min_amount > 1) 
			    {
				$min_amount = 1;
				
			    }

			if ($min_amount > 1){
				$mult_order_quantity = 'Y';
			}
			else {
				$mult_order_quantity = 'N';
			}

			$list_price = strip_tags($loadproduct["product"]["msrp"]);
			$list_price = str_replace(array("$",","), "", $list_price);
			if (empty($list_price) || $list_price == "0.00"){
				$list_price = 0;
			}


			if (
				    (!empty($loadproduct["product"]["options"]) && 
				    is_array($loadproduct["product"]["options"]) && 
				    $loadproduct["product"]["options"]["0"]["qty"]>0 && 
				    $unit_price>0 && 
				    ($group_price=="" || ($group_price!="" && $discount_min_qty != "1") )
				    )
			)
			{
			/*(!empty($loadproduct["product"]["options"]) && is_array($loadproduct["product"]["options"]) && $loadproduct["product"]["options"]["0"]["qty"]>0 && $unit_price>0){*/
				$cost_to_us = $unit_price;
			}
			else {
				$cost_to_us = strip_tags($loadproduct["product"]["price"]);
				$cost_to_us = str_replace(array("$",","), "", $cost_to_us);
				
			}

			$AAJ_product_info["cost_to_us"] = $cost_to_us;
			$AAJ_product_info["list_price"] = $list_price;
			$AAJ_product_info["mult_order_quantity"] = $mult_order_quantity;
			$AAJ_product_info["min_amount"] = $min_amount;
			$AAJ_product_info["unit"] = $unit;
			$AAJ_product_info["rawdata"] = $data;

			$discount_table = "";
			if (!empty($loadproduct["product"]["discount"]) && is_array($loadproduct["product"]["discount"])){

				$new_discount_table_arr = array();

	                        foreach ($loadproduct["product"]["discount"] as $k_d => $v_d){
        	                        if ($v_d["min_qty"] > 1 && !empty($v_d["discount_pct"]) && $v_d["qty_unit"] == $AAJ_product_info["min_amount"] && strtolower($v_d["discount_pct"]) != "null"){
//						$v_d["discount_pct"] = str_replace(".", "", $v_d["discount_pct"]);
                	                        $new_discount_table_arr[] = ceil($v_d["min_qty"]*$AAJ_product_info["min_amount"]).":".round($v_d["discount_pct"]/100,4);
                        	        }
	                        }

				if (!empty($new_discount_table_arr)){
					$discount_table = implode(",", $new_discount_table_arr);
				}
			}

			if (empty($discount_table)) {
				if ($AAJ_product_info["min_amount"] == "1"){
					$discount_table = "2,3,4,6,8,12";
				}
				else {
					$discount_table = (2*$AAJ_product_info["min_amount"]).",".(3*$AAJ_product_info["min_amount"]).",".(4*$AAJ_product_info["min_amount"]).",".(5*$AAJ_product_info["min_amount"]);
				}
			}

			$AAJ_product_info["discount_table"] = $discount_table;
		}

		$AAJ_product_info["instock"] = $instock;
		$AAJ_product_info["supplier_internal_option"] = $supplier_internal_option;

//func_print_r($loadproduct, $AAJ_product_info);

		return $AAJ_product_info;
}

function func_log_cidev_surf($resource_type, $resource_id = "0"){
	global $sql_tbl, $XCARTSESSID, $current_storefront, $detect_isMobile_was_created, $clean_url_data, $is_robot, $cidev_filters_tree_sorted;

	if (
                $is_robot == "Y" ||
                defined("IS_ROBOT") ||
                (empty($$XCART_SESSION_NAME) && empty($XCARTSESSID))
	) {
		return false;
	}


/*
$resource_type
P - Product
C - Category
B - Brand
A - Add to cart
K - checKout
S - elastic Search
H - Home page
O - Order submitted
T - sTatic page
*/

	$goals_arr = array(
		"A" => "goal_addtocart",
		"K" => "goal_checkout",
		"S" => "goal_search",
		"O" => "goal_order"
	);

	$surf_meta = func_query_first("SELECT * FROM $sql_tbl[cidev_surf_meta] WHERE sessid='$XCARTSESSID'");

	if (empty($surf_meta)){

		$referal_url = "";

                if (strpos($_SERVER["REQUEST_URI"], "origin") !== false){
                        $referal_url = str_replace("/dispatcher.php?request_uri=","",$_SERVER["REQUEST_URI"]);
                        $referal_url = $_SERVER["HTTP_HOST"].$referal_url;

			global $login;
			db_query("UPDATE $sql_tbl[customers] SET referer='".addslashes($referal_url)."' WHERE login='$login'");
                }

                if (!empty($_SERVER["HTTP_REFERER"]) && empty($referal_url)){
                        $referal_url_arr1 = explode("//", $_SERVER["HTTP_REFERER"]);
                        $referal_url_arr2 = explode("/", $referal_url_arr1[1]);
                        $referal_url = $referal_url_arr1[0]."//".$referal_url_arr2[0];
                }

                $cidev_surf_meta_arr = array(
                        "sessid" => $XCARTSESSID,
                        "date" => time(),
                        "referal_url" => addslashes($referal_url),
                        "is_mobile" => ($detect_isMobile_was_created ? "Y":"N"),
                        "goal_order" => 'N',
                        "goal_checkout" => 'N',
                        "goal_addtocart" => 'N',
                        "goal_search" => 'N',
                        "points_visited" => '1',
                        "last_update" => time(),
                        "storefrontid" => $current_storefront
                );

                if (in_array($resource_type, array("A","K","S","O"))){
                        $cidev_surf_meta_arr[$goals_arr[$resource_type]] = "Y";
                }

                $cidev_surf_path_arr["meta_id"] = func_array2insert("cidev_surf_meta", $cidev_surf_meta_arr);

		if (in_array($resource_type, array("P","C","B","T"))){
			$resource_id = $clean_url_data["resource_id"];
		}

		$cidev_surf_path_arr["resource_id"] = $resource_id;
		$cidev_surf_path_arr["resource_type"] = $resource_type;
//		$cidev_surf_path_arr["sessid"] = $XCARTSESSID;
		$cidev_surf_path_arr["timestamp"] = time();

                if (in_array($resource_type, array("C","B","S")) && !empty($cidev_filters_tree_sorted) && is_array($cidev_filters_tree_sorted)){
                        $selected_fv_id_arr = array();
                        foreach ($cidev_filters_tree_sorted as $v){
                          if (!empty($v["filter_values"]) && is_array($v["filter_values"])){
                            foreach ($v["filter_values"] as $tree_filter_values){
                              if ($tree_filter_values["selected"] == "Y"){
                                $selected_fv_id_arr[] = $tree_filter_values["fv_id"];
                              }
                            }
                          }
                        }

                        if (!empty($selected_fv_id_arr)){
                                $cidev_surf_path_arr["additional_data"] = implode(",", $selected_fv_id_arr);
                        }
                }

		func_array2insert("cidev_surf_path", $cidev_surf_path_arr);
	}
	else {

		$points_visited = $surf_meta["points_visited"] + 1;

		$goals_str_to_db = "";
                if (in_array($resource_type, array("A","K","S","O"))){
                        $goals_str_to_db =", ".$goals_arr[$resource_type]."='Y'";
                }

		db_query("UPDATE $sql_tbl[cidev_surf_meta] SET points_visited='$points_visited', last_update='".time()."' $goals_str_to_db WHERE id='$surf_meta[id]'");

		$cidev_surf_path_arr["meta_id"] = $surf_meta["id"];
//		$cidev_surf_path_arr["sessid"] = $XCARTSESSID;
		$cidev_surf_path_arr["position"] = $points_visited;
		$cidev_surf_path_arr["resource_type"] = $resource_type;
		$cidev_surf_path_arr["timestamp"] = time();

		
		if (in_array($resource_type, array("P","C","B","T"))){
			$cidev_surf_path_arr["resource_id"] = $clean_url_data["resource_id"];
		}
		else {
			$cidev_surf_path_arr["resource_id"] = $resource_id;
		}

		if ($resource_type == "S"){
			$REQUEST_URI_arr = explode("/", $_GET["request_uri"]);
			$cidev_surf_path_arr["additional_data"] = $REQUEST_URI_arr[2];
		}

                if (in_array($resource_type, array("C","B","S")) && !empty($cidev_filters_tree_sorted) && is_array($cidev_filters_tree_sorted)){
			$selected_fv_id_arr = array();
			foreach ($cidev_filters_tree_sorted as $v){
			  if (!empty($v["filter_values"]) && is_array($v["filter_values"])){
			    foreach ($v["filter_values"] as $tree_filter_values){
			      if ($tree_filter_values["selected"] == "Y"){
				$selected_fv_id_arr[] = $tree_filter_values["fv_id"];
			      }
			    }
			  }
			}

			if (!empty($selected_fv_id_arr)){
				$cidev_surf_path_arr["additional_data"] = implode(",", $selected_fv_id_arr);
			}
		}

		func_array2insert("cidev_surf_path", $cidev_surf_path_arr);
	}
}

function func_convert_date_mm_dd_yyyy($date, $to_format){

	#
	# $to_format examples: "seconds", "m/d/Y", ...
	#

	if (empty($date) || empty($to_format)){
		return false;
	}

	if ($to_format == "seconds"){

		if (is_int($date)){
			return $date;
		}

		if (strpos($date, "/") !== false){
			$date_arr = explode("/", $date);
			$time = mktime(0, 0, 0, $date_arr[0], $date_arr[1], $date_arr[2]);

			return $time;
		}
	} 
	else {
		
		if (!is_int($date)){
                        return $date;
                }

		$date_str = date($to_format, $date);
	
		return $date_str;
	}
}

function func_detect_working_hours(){
	global $config, $working_days, $smarty;

	$day = strtolower(date('l'));

	$working_hours = $working_days[$day];

	if ($working_hours["type"] == "non_working"){
		$store_in_working_hours = "N";
	}
	elseif ($working_hours["type"] == "all_day"){
		$store_in_working_hours = "Y";
	}
	elseif ($working_hours["type"] == "custom"){
		$store_in_working_hours = "N";

		$current_time = price_format(date("G.i"));
		$working_hours["current_time"] = $current_time;
		if (price_format($working_hours["from"]) <= $current_time && $current_time <= price_format($working_hours["to"])){
			$store_in_working_hours = "Y";
		}
	}

	$working_hours["store_in_working_hours"] = $store_in_working_hours;

	$smarty->assign("working_hours", $working_hours);
}

function func_add_slashes($str){

	# This function for jQuery carousel
	## Do not modify lines below.
	###
	$str = str_replace("&#39;","\&#39;",$str);
	$str = str_replace("'","\&#39;",$str);
	$str = str_replace('&#34;','\&#34;',$str);
	$str = str_replace('"','\&#34;',$str);
	###
	##
	#

	$str = addslashes($str);
	
	return $str;
}
?>
