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
# $Id: prepare.php,v 1.62.2.6 2006/09/26 11:01:59 max Exp $
#
# This module provides compatibility with different hostings and versions of PHP.
#

if ( !defined('XCART_START') ) { header("Location: index.php"); die("Access denied"); }

if (ini_get("magic_quotes_sybase") && ini_get("magic_quotes_gpc"))
	define("X_QUOTES_SYBASE", true);

@include $xcart_dir."/check_requirements.php";  /*disabled for speed optimizations*/
#
#
# DO NOT CHANGE ANYTHING BELOW THIS LINE UNLESS
# YOU REALLY KNOW WHAT ARE YOU DOING
#
#

if (get_magic_quotes_runtime()) {
	@set_magic_quotes_runtime(0);
}
if (ini_get('magic_quotes_sybase')) {
	ini_set("magic_quotes_sybase",0);
}
ini_set("session.bug_compat_42",1);
ini_set("session.bug_compat_warn",0);

$__quotes_qpc = function_exists('get_magic_quotes_gpc') ? get_magic_quotes_gpc() : false;

function func_microtime() {
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
}

function func_unset(&$array) {
	$keys = func_get_args();
	array_shift($keys);
	if (!empty($keys) && !empty($array) && is_array($array)) {
		foreach ($keys as $key) {
			if (@isset($array[$key]))
				unset($array[$key]);
		}
	}
}

# responsible version of empty()
function zerolen() {
	foreach (func_get_args() as $arg) {
		if (strlen($arg) == 0) return true;
	}

	return false;
}

function func_array_map($func, $var) {
	if (!is_array($var)) return $var;

	foreach($var as $k=>$v)
		$var[$k] = call_user_func($func,$v);

	return $var;
}

#
# Variant of the function array_map(), where user function is used both for
# the value of an array element and for its key
#
function func_array_map_hash($func, $var) {
	if (!is_array($var))
		return $var;

	$var_proc = array();
	foreach ($var as $k => $v) {
		$var_proc[call_user_func($func, $k)] = call_user_func($func, $v);
		unset($var[$k]);
	}

	return $var_proc;
}

function func_array_merge() {
	$vars = func_get_args();

	$result = array();
	if (!is_array($vars) || empty($vars)) {
		return $result;
	}

	foreach($vars as $v) {
		if (is_array($v) && !empty($v)) {
			$result = array_merge($result, $v);
		}
	}

	return $result;
}

function func_addslashes($var) {
	return is_array($var) ? func_array_map_hash('func_addslashes', $var) : addslashes($var);
}

function func_addslashes_keys($var) {
	if (!is_array($var))
		return addslashes($var);

	$var_proc = array();
	foreach ($var as $k => $v) {
		unset($var[$k]);
		$var_proc[func_addslashes_keys($k)] = $v;
	}

	return $var_proc;
}

function func_stripslashes($var) {
	return is_array($var) ? func_array_map_hash('func_stripslashes', $var) : stripslashes($var);
}

function func_array_key_exists($key, $search) {
	if (function_exists("array_key_exists")) {
		return array_key_exists($key, $search);

	} elseif (!isset($search[$key])) {
		foreach ($search as $k => $v) {
			if ($k === $key)
				return true;
		}

		return false;
	}

	return true;
}

function func_strip_tags($var) {
	return is_array($var) ? func_array_map_hash('func_strip_tags', $var) : strip_tags($var);
}

function func_have_script_tag($var) {
	if (!is_array($var)) {
		return (stristr($var, '<script') !== false);
	}
	foreach ($var as $item) {
		if (!is_array($var)) {
			if (stristr($var, '<script') !== false) return true;
		}
		elseif (func_have_script_tag($item)) return true;
	}
	return false;
}

function func_allowed_var($name) {
	global $reject;
	if (in_array($name,$reject) && !defined('ADMIN_UNALLOWED_VAR_FLAG')) {
		define('ADMIN_UNALLOWED_VAR_FLAG',1);
	}
	return !in_array($name,$reject);
}

#
# Wrapper for version_compare() function
#
function func_version_compare($ver1, $ver2) {
	if (function_exists("version_compare"))
		return version_compare($ver1, $ver2);

	$ver1 = str_replace("..", ".", preg_replace("/([^\d\.]+)/S", ".\\1.", str_replace(array("_", "-", "+"), array(".", ".", "."), $ver1)));
	$ver2 = str_replace("..", ".", preg_replace("/([^\d\.]+)/S", ".\\1.", str_replace(array("_", "-", "+"), array(".", ".", "."), $ver2)));

	$ratings = array(
		"/^dev$/i" => -100,
		"/^alpha$/i" => -90,
		"/^a$/i" => -90,
		"/^beta$/i" => -80,
		"/^b$/i" => -80,
		"/^RC$/i" => -70,
		"/^pl$/i" => -60
	);
	foreach ($ver1 as $k => $v) {
		if (!is_numeric($v))
			$v = preg_replace(array_keys($ratings), array_values($ratings), $v);

		if (!is_numeric($ver2[$k]))
			$ver2[$k] = preg_replace(array_keys($ratings), array_values($ratings), $ver2[$k]);

		$r = strcmp($v, $ver2[$k]);
		if ($r != 0)
			return $r;
	}

	return 0;
}

#
# Strip Sybase-style magic quotes
#
function func_stripslashes_sybase($data) {
	return is_array($data) ? func_array_map_hash("func_stripslashes_sybase", $data) : str_replace("''", "'", $data);
}

define('X_REJECT_OVERRIDE', 1);
define('X_REJECT_CLEAN', 2);
define('X_REJECT_OVERRIDE_GET', 4);
define('X_REJECT_OVERRIDE_POST', 8);
define('X_REJECT_OVERRIDE_COOKIE', 16);
define('X_REJECT_OVERRIDE_ENV', 32);
define('X_REJECT_OVERRIDE_SERVER', 64);
define('X_REJECT_OVERRIDE_FILES', 128);
define('X_REJECT_OVERRIDE_SESSION', 256);
define('X_REJECT_OVERRIDE_GLOBALS', (X_REJECT_OVERRIDE_GET|X_REJECT_OVERRIDE_POST|X_REJECT_OVERRIDE_COOKIE|X_REJECT_OVERRIDE_ENV|X_REJECT_OVERRIDE_SERVER|X_REJECT_OVERRIDE_FILES|X_REJECT_OVERRIDE_SESSION));

function func_init_reject($option = 0) {
	static $reject = false;

	if ($option & X_REJECT_CLEAN) {
		$reject = false;
		return array();
	}

	if (!$reject || $option & X_REJECT_OVERRIDE) {
		$reject = array_keys($GLOBALS);
		$reject[] = 'reject';
		$reject[] = '__name';
		$reject[] = '__avar';
		$reject[] = 'GLOBALS';
		$reject[] = '_GET';
		$reject[] = '_POST';
		$reject[] = '_SERVER';
		$reject[] = '_ENV';
		$reject[] = '_COOKIE';
		$reject[] = '_FILES';
		$reject[] = '_SESSION';
		$reject[] = 'XCART_SESSION_VARS';
		$reject[] = 'XCART_SESSION_UNPACKED_VARS';
		$reject[] = 'HTTP_RAW_POST_DATA';

		$gvars_array = array(
			X_REJECT_OVERRIDE_GET 		=> 'GET', 
			X_REJECT_OVERRIDE_POST 		=> 'POST', 
			X_REJECT_OVERRIDE_COOKIE 	=> 'COOKIE', 
			X_REJECT_OVERRIDE_ENV 		=> 'ENV', 
			X_REJECT_OVERRIDE_SERVER 	=> 'SERVER', 
			X_REJECT_OVERRIDE_FILES 	=> 'FILES', 
			X_REJECT_OVERRIDE_SESSION 	=> 'SESSION'
		);
		foreach ($gvars_array as $__bvar => $__avar) {
			global $_GET;
			global $_POST;
			global $_SERVER;
			global $_COOKIE;
			global $HTTP_FILES_VARS;
			global $_SESSION;
			global $_ENV;

			if ($option & $__bvar) {
				if (isset(${'_' . $__avar}) && is_array(${'_' . $__avar})) {
    				$reject = array_merge($reject, array_keys(${'_' . $__avar}));
    			}
    		}
		}
	}

	return $reject;
}

if (!defined("XCART_EXT_ENV")) {

    if (isset($_COOKIE['is_robot']) && $_COOKIE['is_robot']) {
	define('IS_ROBOT', 1);
    } 

# strong validation for the SERVER variables
    foreach ($_SERVER as $__var => $__res) {
	    $_SERVER[$__var] = func_strip_tags($__res);
    }

# simple validation for the GET variables
    foreach ($_GET as $__var => $__res) {
        if (defined('USE_TRUSTED_GET_VARS') && in_array($__var, explode(",",USE_TRUSTED_GET_VARS))) {
            continue;
        }

	    $_GET[$__var] = func_strip_tags($__res);
    }
# simple validation for the COOKIE variables
    foreach ($_COOKIE as $__var => $__res) {
        $_COOKIE[$__var] = func_strip_tags($__res);
    }

# validation for the POST variables: strip html tags from untrusted variables
    foreach ($_POST as $__var => $__res) {
	if (defined("USE_TRUSTED_POST_VARIABLES") && in_array($__var, $trusted_post_variables)) {
		# ignore trusted variables: these variables used in product/category modify etc

		if (!defined("USE_TRUSTED_SCRIPT_VARS") && func_have_script_tag($__res)) {
			unset($$__var);
			    unset($_POST[$__var]);
                if (isset($_POST)) {
				unset($_POST[$__var]);
		}
		    }

		continue;
	    } else {
		    $_POST[$__var] = func_strip_tags($__res);
        }
	}

    if (!$__quotes_qpc) {
	# Add slashes
	    foreach (array('GET', 'POST', 'COOKIE') as $__avar) {
		    ${'_' . $__avar} = func_addslashes(${'_' . $__avar});
	}

    } elseif (defined("X_QUOTES_SYBASE")) {
	# Strip Sybase-style magic quotes
	    foreach (array('GET', 'POST', 'COOKIE') as $__avar) {
		    ${'_' . $__avar} = func_stripslashes_sybase(${'_' . $__avar});
		    ${'_' . $__avar} = func_addslashes(${'_' . $__avar});
	}

    } else {
	# Add slashes for keys
	    foreach (array('GET', 'POST', 'COOKIE') as $__avar) {
		    ${'_' . $__avar} = func_addslashes_keys(${'_' . $__avar});
	    }
	}

function func_remove_phishing($arr) {
    global $trusted_vars;

    if (is_array($arr) && !empty($arr))
        foreach($arr as $k => $v) {
            if (is_array($v)) {
                $arr[$k] = func_remove_phishing($v);
                continue;
            }
            if (!in_array($k, $trusted_vars))
                $arr[$k] = htmlspecialchars($arr[$k], ENT_QUOTES);
        }
    return $arr;
}
$_trust = array("GreetingCookie");
$trusted_vars = (empty($trusted_vars) || !is_array($trusted_vars)) ? $_trust : array_merge($trusted_vars, $_trust);
    foreach (array('GET', 'POST', 'COOKIE') as $__avar) {
        if (!defined('AREA_TYPE') || AREA_TYPE == 'C') {
            ${'_' . $__avar} = func_remove_phishing(${'_' . $__avar});
        }
    }

unset($__avar, $__var, $__res);

# register allowed global variables from request
$reject = func_init_reject(X_REJECT_OVERRIDE);
    foreach (array('GET', 'POST', 'COOKIE', 'SERVER') as $__avar) {
	    foreach (${'_' . $__avar} as $__var => $__res) {
		if (func_allowed_var($__var))
			$$__var = $__res;
		else
			func_unset(${'_' . $__avar}, $__var);
	}

	    reset(${'_' . $__avar});
}
func_init_reject(X_REJECT_CLEAN);

    foreach ($_FILES as $__name => $__value) {
        if (!func_allowed_var($__name)) {
            continue;
        }
	$$__name = $__value["tmp_name"];
	foreach($__value as $__k=>$__v) {
		$__varname_ = $__name."_".$__k;
		if (!func_allowed_var($__varname_)) continue;
		$$__varname_ = $__v;
	}
}
unset($reject, $__avar, $__var, $__res);

}

#
# OS detection
#
define('X_DEF_OS_WINDOWS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));

if (!defined('PATH_SEPARATOR')) {
	if (X_DEF_OS_WINDOWS)
		define('PATH_SEPARATOR', ';');
	else
		define('PATH_SEPARATOR', ':');
}

if (empty($REQUEST_URI))
	$REQUEST_URI = $PHP_SELF.($QUERY_STRING?"?$QUERY_STRING":"");

@include $xcart_dir."/include/https_detect.php";

#
# HTTP_REFERER override
#
if ($_GET['iframe_referer']) {
	$HTTP_REFERER = urldecode($_GET['iframe_referer']);
}

if (!empty($HTTP_REFERER) && strncasecmp($HTTP_REFERER,'http://', 7) && strncasecmp($HTTP_REFERER,'https://', 8)) {
	$HTTP_REFERER = "";
	if (!empty($_SERVER['HTTP_REFERER'])) {
		unset($_SERVER['HTTP_REFERER']);
	}
	if (!empty($_GET['iframe_referer'])) {
		unset($_GET['iframe_referer']);
	}
}

#
# Proxy IP
#
$PROXY_IP = '';
if (!empty($HTTP_X_FORWARDED_FOR)) {
	$PROXY_IP = $HTTP_X_FORWARDED_FOR;
} elseif (!empty($HTTP_X_FORWARDED)) {
	$PROXY_IP = $HTTP_X_FORWARDED;
} elseif (!empty($HTTP_FORWARDED_FOR)) {
	$PROXY_IP = $HTTP_FORWARDED_FOR;
} elseif (!empty($HTTP_FORWARDED)) {
	$PROXY_IP = $HTTP_FORWARDED;
} elseif (!empty($HTTP_CLIENT_IP)) {
	$PROXY_IP = $HTTP_CLIENT_IP;
} elseif (!empty($HTTP_X_COMING_FROM)) {
	$PROXY_IP = $HTTP_X_COMING_FROM;
} elseif (!empty($HTTP_COMING_FROM)) {
	$PROXY_IP = $HTTP_COMING_FROM;
}

$HTTP_HOST = isset($_SERVER['HTTP_HOST'])
    ? addslashes($_SERVER['HTTP_HOST'])
    : false;

$REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR'])
    ? addslashes($_SERVER['REMOTE_ADDR'])
    : false;

$PROXY_IP = addslashes($PROXY_IP);

if(!empty($PROXY_IP)) {
	$CLIENT_IP = $PROXY_IP;
	$PROXY_IP = $REMOTE_ADDR;
} else {
	$CLIENT_IP = $REMOTE_ADDR;
}

if (isset($_GET['benchmark']) || isset($_POST['benchmark'])) {
	define("START_TIME", func_microtime());
}

#
# Miscellaneous constants
#

define('SECONDS_PER_DAY', 86400); # 60 * 60 * 24
define('SECONDS_PER_WEEK', 604800); # 60 * 60 * 24 * 7

#
# Aloow displaying content in functions, registered in register_shutdown_function()
#
$zlib_oc = ini_get("zlib.output_compression");
if (!empty($zlib_oc) || func_version_compare(phpversion(), "4.0.6") <= 0)
	define("NO_RSFUNCTION", true);

unset($zlib_oc);

function func_get_request_uri()
{

    if (isset($_SERVER['REQUEST_URI'])) {

        return $_SERVER['REQUEST_URI'];

    }

    if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {

        return $_SERVER['HTTP_X_ORIGINAL_URL'];

    } else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {

        return $_SERVER['HTTP_X_REWRITE_URL'];

    }

    if (
        isset($_SERVER['PATH_INFO'])
        && !zerolen($_SERVER['PATH_INFO'])
    ) {

        if ($_SERVER['PATH_INFO'] == $_SERVER['PHP_SELF']) {

            $request_uri = $_SERVER['PHP_SELF'];

        } else {

            $request_uri = $_SERVER['PHP_SELF'] . $_SERVER['PATH_INFO'];

        }

    } else {

        $request_uri = $_SERVER['PHP_SELF'];

    }

    // Append query string
    if (
        isset($_SERVER['argv'])
        && isset($_SERVER['argv'][0])
        && !zerolen($_SERVER['argv'][0])
    ) {

        $request_uri .= '?' . $_SERVER['argv'][0];

    } elseif (
        isset($_SERVER['QUERY_STRING'])
        && !zerolen($_SERVER['QUERY_STRING'])
    ) {

        $request_uri .= '?' . $_SERVER['QUERY_STRING'];

    }

    return $request_uri;
}


?>
