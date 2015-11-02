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
# X-Cart installation wizard
#
# $Id: install.php,v 1.183.2.10 2007/01/15 07:35:27 max Exp $
#

include "./top.inc.php";

if (!defined('XCART_SESSION_START'))
	define('XCART_SESSION_START',1);
if (!defined('XCART_START'))
	define('XCART_START',1);

define("XCART_EXT_ENV", true);

#
# Predefined common variables
#

$min_ver = "4.0.6";

$directories_to_create = array("files", "catalog", "images", "var", "var/log", "var/cache", "var/tmp", "var/templates_c", "var/upgrade");
$directories_to_create[] = "files/admin";
$directories_to_create[] = "files/provider";
$directories_to_create[] = "files/root";

$init_blowfish_key = "8d5db63ada15e11643a0b1c3477c2c5c";

$files_to_create = array(
	"files/.htaccess" => "Deny from all",
	"var/cache/.htaccess" => "Deny from all\n<files \"*.js\">\nAllow from all\n</files>",
	"var/templates_c/.htaccess" => "Deny from all",
	"var/tmp/.htacces" => "Deny from all",
	"var/upgrade/.htacces" => "Deny from all",
	"var/log/.htaccess" => "Deny from all",
);

$installation_product = "X-Cart";

#
# Modules definition
# used in include/install.php (install subsystem)
#
# This array describes what to do at the current step of installation:
# - key in $modules - number of step
# - $modules[$step]["name"] - suffix of function name
#   (e.g. module_language for "language")
# - $modules[$step]["comment"] - name of language variable that
#   content will appears at page (see include/install_lng_*.php)
#
# Each module function should accept at least one argument: $params
# Expected return value of module function:
# - false on success
# - true on failure (and set up global variable $error)
#
$modules = array (
	0 => array(
			"name" => "language",
			"comment" => "mod_language"
	),
	1 => array(
			"name" => "default",
			"comment" => "mod_license",
			"js_next" => 1
		),
	2 => array(
			"name" => "check_cfg",
			"comment" => "mod_check_cfg"
		),
	3 => array(
			"name" => "cfg_install_db",
			"comment" => "mod_cfg_install_db",
			"js_next" => 1
		),
	4 => array(
			"name" => "install_db",
			"comment" => "mod_install_db"
		),
	5 => array(
			"name" => "cfg_install_dirs",
			"comment" => "mod_cfg_install_dirs"
		),

	6 => array(
			"name" => "install_dirs",
			"comment" => "mod_install_dirs"
		),
	7 => array(
			"name" => "cfg_enable_paypal",
			"comment" => "mod_cfg_enable_paypal"
		),
	8 => array(
			"name" => "enable_paypal",
			"comment" => "mod_enable_paypal"
		),
	9 => array(
			"name" => "generate_snapshot",
			"comment" => "mod_generate_snapshot"
		),
	10 => array(
			"name" => "install_done",
			"comment" => "mod_install_done",
			"param" => "func_success"
		)
);

################################################################
#
# Common functions goes here
#
################################################################

function change_config($params) {
	global $installation_auth_code;

	$current_directory = str_replace("\\", "/", realpath("."));
	$allfile = "";

	// Write data to config.php
	if (!($fp = fopen("config.php", "r+")))
		return false;

	while (!feof($fp)) {
		$buffer = fgets($fp, 4096);

		if (preg_match('/^\$sql_host *=/', $buffer))
			$buffer = preg_replace('/=.*;/', "='".addslashes($params["mysqlhost"])."';", $buffer);

		if (preg_match('/^\$sql_user *=/', $buffer))
			$buffer = preg_replace('/=.*;/', "='".addslashes($params["mysqluser"])."';", $buffer);

		if (preg_match('/^\$sql_db *=/', $buffer))
			$buffer = preg_replace('/=.*;/', "='".addslashes($params["mysqlbase"])."';", $buffer);

		if (preg_match('/^\$sql_password *=/', $buffer))
			$buffer = preg_replace('/=.*;/', "='".addslashes($params["mysqlpass"])."';", $buffer);

		if (preg_match('/^\$xcart_http_host *= *"/', $buffer))
			$buffer = preg_replace('/=.*;/', "=\"".addslashes($params["xcart_http_host"])."\";", $buffer);

		if (preg_match('/^\$xcart_https_host *= *"/', $buffer))
			$buffer = preg_replace('/=.*;/', "=\"".addslashes($params["xcart_https_host"])."\";", $buffer);

		if (preg_match('/^\$xcart_web_dir *= *"/', $buffer))
			$buffer = preg_replace('/=.*;/', "=\"".addslashes($params["xcart_web_dir"])."\";", $buffer);

		if (preg_match('/^\$license *=/', $buffer))
			$buffer = preg_replace('/=.*;/', "='".$installation_auth_code."';", $buffer);

		/*
			When the option "Update config.php only" is enabled, Blowfish key is not
			regenerated
			(This is not done intentionally, because, if the Blowfish key gets regenerated,
			the new key will be different from the key that was used to encrypt all the
			data, and the data will not be able to be decrypted).
		*/
		if (empty($params["config_only"]) && preg_match('/^\$blowfish_key *=/', $buffer))
			$buffer = preg_replace('/=.*;/', "='".$params["blowfish_key"]."';", $buffer);

		$allfile .= $buffer;
	}

	ftruncate($fp, 0);
	rewind($fp);

	fwrite($fp, $allfile);

	fclose($fp);

	return true;
}

#
# Recrypt all encrypted data
#
function recrypt_data(&$params) {
	global $bf_crypted_tables, $blowfish;

	if (!$blowfish)
		return false;

	$tbls = mysql_query("SHOW TABLES");

	if (!$tbls)
		return false;

	while ($tbl = mysql_fetch_row($tbls)) {
		$tbl = preg_replace("/^xcart_/S", "", $tbl[0]);

		if (!isset($bf_crypted_tables[$tbl]))
			continue;

		$data = mysql_query("SELECT ".$bf_crypted_tables[$tbl]['key'].", ".implode(", ", $bf_crypted_tables[$tbl]['fields'])." FROM xcart_".$tbl." WHERE 1 ".$bf_crypted_tables[$tbl]['where']);
		if (!$data)
			continue;

		while ($row = mysql_fetch_assoc($data)) {
			$key = array_shift($row);

			if (empty($row) || empty($key))
				continue;

			$update = array();
			foreach ($row as $fname => $fvalue) {
				if (substr($fvalue, 0, 1) == "B")
					$update[] = $fname.' = "'.addslashes(recrypt_field($fvalue, $params)).'"';
			}

			if (!empty($update)) {
				mysql_query("UPDATE xcart_$tbl SET ".implode(", ", $update)." WHERE ".$bf_crypted_tables[$tbl]['key']." = '".addslashes($key)."'");
			}
		}

		mysql_free_result($data);
	}

	mysql_free_result($tbls);

	return true;
}

#
# Recrypt field
#
function recrypt_field($field, &$params) {
	global $init_blowfish_key;

	if (empty($init_blowfish_key) || empty($params['blowfish_key']) || strlen($field) < 3 || substr($field, 0, 1) != 'B')
		return $field;

	if (substr($field, 1, 1) == '-') {
		$field = trim(func_bf_decrypt(substr($field, 2), $init_blowfish_key));
		$init_crc32 = substr($field, -8);
		$field = substr($field, 0, -8);

	} else {
		$init_crc32 = substr($field, 1, 8);
		$field = trim(func_bf_decrypt(substr($field, 9), $init_blowfish_key));
	}

	$crc32 = crc32(md5($field));

	if (crc32("test") != -662733300 && $crc32 > 2147483647)
		$crc32 -= 4294967296;
	$crc32 = dechex(abs($crc32));
	$crc32 = str_repeat("0", 8-strlen($crc32)).$crc32;

	return "B-".func_bf_crypt($field.$crc32, $params['blowfish_key']);
}

#
# Check all encrypted data
#
function check_crypted_data() {
	global $xcart_dir, $bf_crypted_tables, $blowfish, $blowfish_key;

	include $xcart_dir."/init.php";
	x_load('crypt');

	if (empty($bf_crypted_tables) || empty($blowfish) || empty($blowfish_key))
		return false;

	$tbls = mysql_query("SHOW TABLES");

	if (!$tbls)
		return false;

	$i = 0;
	while ($tbl = mysql_fetch_row($tbls)) {
		$tbl = preg_replace("/^xcart_/S", "", $tbl[0]);

		if (!isset($bf_crypted_tables[$tbl]))
			continue;

		$data = mysql_query("SELECT ".$bf_crypted_tables[$tbl]['key'].", ".implode(", ", $bf_crypted_tables[$tbl]['fields'])." FROM xcart_".$tbl." WHERE 1 ".$bf_crypted_tables[$tbl]['where']);
		if (!$data)
			continue;

		while ($row = mysql_fetch_assoc($data)) {
			$key = array_shift($row);

			if (empty($row) || empty($key))
				continue;

            foreach ($row as $fname => $field) {
                if (substr($field, 0, 1) != "B")
					continue;

				if (substr($field, 1, 1) == '-') {
					$field = trim(func_bf_decrypt(substr($field, 2), $blowfish_key));
					$init_crc32 = substr($field, -8);
					$field = substr($field, 0, -8);
					$crc32 = func_crc32(md5($field));

				} else {
					$init_crc32 = substr($field, 1, 8);
					$field = trim(func_bf_decrypt(substr($field, 9), $blowfish_key));
					$crc32 = func_crc32($field);
				}

				if ($init_crc32 != $crc32)
					return false;

				if (++$i % 10 == 0) {
					echo ". ";
					flush();
				}
			}
		}

		mysql_free_result($data);
	}

	mysql_free_result($tbls);
	
	return true;
}

function config_get($dir) {
	static $var_defs = array (
		'sql_host', 'sql_user', 'sql_db', 'sql_password',
		'xcart_http_host', 'xcart_https_host', 'xcart_web_dir',
		'license'
	);

	static $config_files = array (
		'config.php', 'config.local.php'
	);

	$cnf = false;

	foreach ($config_files as $f) {
		$file = $dir.'/'.$f;

		$fp = @fopen($file, "r");
		if (!$fp) continue;

		$buffer = fread($fp, filesize($file));
		fclose($fp);

		foreach ($var_defs as $var) {
			$regexp = '!^\s*\$'.preg_quote($var).'\s*=\s*[\'"](.+)[\'"];!';

			if (preg_match($regexp, $buffer, $matches)) {
				$cnf[$var] = $matches[1];
			}
		}
	}

	return $cnf;
}


################################################################
#
# Modules goes here
#
################################################################

#
# start: Default module
# Shows Terms & Conditions
#

function module_default(&$params) {
	global $error, $templates_directory;
	global $installation_auth_code;
	global $installation_product;
	global $install_lng;
?>
<center>
<?php message(lng_get("thank_you", "product", $installation_product)); ?>
<br /><br />

<?php
	if (!file_exists('./COPYRIGHT')) {
		fatal_error(lng_get("no_license_file"));
		exit;
 	}
?>
<textarea name="copyright" cols="80" rows="22" readonly="readonly">
<?php
ob_start();
require "./COPYRIGHT";
$tmp = ob_get_contents();
ob_end_clean();
echo htmlspecialchars($tmp);
?>
</textarea>

<p />
<?php if (file_exists($templates_directory)) { ?>
<table>
<tr>
	<td><input type="radio" id="force_current_2" name="params[force_current]" value="2" /></td>
	<td align="left"><label for="force_current_2"><b><?php echo_lng("new_install"); ?></b></label></td>
</tr>
<tr>
	<td><input type="radio" id="force_current_5" name="params[force_current]" value="5,skip_dirs,noinfomail,nopaypal" checked="checked" /></td>
	<td align="left"><label for="force_current_5"><b><?php echo_lng("reinstall_skins"); ?></b></label></td>
</tr>
<tr>
	<td colspan="2" align="left"><b><?php echo_lng("auth_code"); ?>: </b><input type="text" name="params[auth_code]" size="20" /><br /><font size="1"><?php echo_lng("auth_code_note"); ?></font></td>
</tr>
</table>
<p />
<?php } else { ?> <input type="hidden" name="params[auth_code]" value="<?php echo $installation_auth_code?>" /> <?php } ?>
<input id="agree" type="checkbox" name="params[agree]" /> <label for="agree"><?php echo_lng("i_accept_license"); ?></label>

<br /><br />

</center>

<br />

<?php
	return false;
}

#
# 'next' button handler. checks 'agree' button checked
#

function module_default_js_next() {
?>
	function step_next() {
		if (document.getElementById('agree').checked) {
			return true;
		} else {
			alert("<?php echo_lng_js("mod_license_alert"); ?>");
		}
		return false;
	}
<?php
}

#
# end: Default module
#
#
# start: Check_cfg module
# Get info about current php configuration
#

function module_check_cfg(&$params) {
	global $min_ver, $error;

?>
<center>
<table width="100%" cellspacing="0" cellpadding="2">

<tr>
	<td align="center">

<table width="50%" cellspacing="0" cellpadding="4">

<tr class="Clr2">
	<td colspan="3" align="center"><b><?php echo_lng("cheking_results"); ?></b></td>
</tr>

<tr class="Clr1">
	<td align="center"><b><?php echo_lng("critical_dependencies"); ?></b></td>
	<td width="1%">&nbsp;</td>
	<td width="1%" align="center"><b><?php echo_lng("status"); ?></b></td>
</tr>

<?php
#
# PHP Version must be not less than $min_ver
#

	$ver = phpversion();
	$status = ($min_ver > $ver ? 0 : 1);
	$ck_res = $status;
?>
<tr class="Clr2">
	<td nowrap="nowrap" align="left"><?php echo_lng("php_ver_min","version",$min_ver); ?> ... <?php echo $ver ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

<?php

#
# PRCE extension must be On
#

	$status = function_exists('preg_match') ? 1 : 0;
	$ck_res &= $status;
?>
<tr class="Clr1">
	<td align="left"><?php echo_lng("pcre_extension_is"); ?> ... <?php echo on_off($status) ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

<?php

#
# PHP Safe mode must be Off
#

	$res = bool_get("safe_mode");
	$status = (!empty($res) ? 0 : 1);
	$ck_res &= $status;
?>
<tr class="Clr2">
	<td align="left"><?php echo_lng("php_safe_mode_is"); ?> ... <?php echo on_off(!$status) ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

<?php

#
# Disabled functions list ideally must be empty
#

	$res = ini_get("disable_functions");
	$status = (empty($res) ? 1 : 0);
?>
<tr class="Clr1">
	<td align="left"><?php echo_lng("php_disabled_funcs"); ?> ... <?php echo ($status ? lng_get("php_disabled_funcs_none") : $res) ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

<?php

#
# File uploads must be On
#

	$res = bool_get("file_uploads");
	$status = (!empty($res) ? 1 : 0);
	$ck_res &= $status;
?>
<tr class="Clr2">
	<td align="left"><?php echo_lng("php_fileuploads_is"); ?> ... <?php echo on_off($status) ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

<?php

#
# MySQL functions must present
#

	$status = function_exists('mysql_connect');
	$ck_res &= $status;
?>
<tr class="Clr1">
	<td align="left"><?php echo_lng("php_mysql_support_is"); ?> ... <?php echo on_off($status) ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

<?php

#
# Register long arrays (PHP 5) must be On
#

	$ver = phpversion();
	$res = ($ver >= "5.0.0" ? bool_get("register_long_arrays") : 1);
	$status = (empty($res) ? 0 : 1);
	$ck_res &= $status;
?>
<tr class="Clr2">
	<td align="left"><?php echo_lng("php_register_long_arrays_is"); ?> ... <?php echo on_off($res) ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($status) ?></td>
</tr>

</table>
<br />
<table width="50%" cellspacing="0" cellpadding="4" align="center">

<tr class="Clr2">
	<td align="center"><b><?php echo_lng("non_critical_dependencies"); ?></b></td>
	<td width="1%">&nbsp;</td>
	<td width="1%" align="center"><b><?php echo_lng("status"); ?></b></td>
</tr>

<?php
	$res = ini_get("upload_max_filesize");
?>
<tr class="Clr1">
	<td align="left"><?php echo_lng("php_upload_maxsize_is"); ?> ... <?php echo $res ?></td>
	<td width="1%">-</td>
	<td width="1%" align="center"><?php echo status($res) ?></td>
</tr>

</table>
	</td>
</tr>

<tr>
	<td colspan="2" align="left">
<br /><br />
<p>
<?php message(lng_get("access_perm_note")) ?>
</p>

<font color="darkblue">
&gt; chmod 777 .<br />
&gt; chmod 666 config.php<br />
&gt; chmod 755 admin/newsletter.sh<br />
&gt; chmod 755 payment/*.pl<br />
</font>
	</td>
</tr>

</table>

<?php if ($ck_res) { ?><br /><?php message(lng_get("push_next_button")); } ?>

</center>

<br />
<?php
	$error = !$ck_res;
	return false;
}

#
# end: Check_cfg module
#

#
# start: Cfg_install_db module
# Get mysql server info and check it before installing db
#

function module_cfg_install_db(&$params) {
	global $HTTP_SERVER_VARS, $error, $schemes_repository;
	global $xcart_dir;

	if (!isset($params["mysqlhost"])) {
		$mysqlhost = "localhost";
		$mysqluser = "";
		$mysqlpass = "";
		$mysqlbase = "xcart";
?>
<center>
<p>
<b><font color="darkgreen"><?php echo_lng("install_web_mysql"); ?>:</font></b>
</p>
</center>

<table width="100%" border="0" cellpadding="4">

<tr class="Clr2">
	<td width="70%"><?php echo_lng("install_http_name"); ?></td>
	<td><input type="text" name="params[xcart_http_host]" size="30" value="<?php echo $HTTP_SERVER_VARS["HTTP_HOST"]; ?>" /></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_https_name"); ?></td>
	<td><input type="text" name="params[xcart_https_host]" size="30" value="<?php echo $HTTP_SERVER_VARS["HTTP_HOST"]; ?>" /></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_webdir"); ?></td>
	<td><input type="text" name="params[xcart_web_dir]" size="30" value="<?php echo preg_replace("~/install(\.php)*$~", "", $HTTP_SERVER_VARS["PHP_SELF"]); ?>" /></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_mysqlhost"); ?></td>
	<td><input type="text" name="params[mysqlhost]" size="30" value="<?php echo $mysqlhost; ?>" /></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_mysqluser"); ?></td>
	<td><input name="params[mysqluser]" size="30" type="text" value="<?php echo $mysqluser; ?>" /></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_mysqldb"); ?></td>
	<td><input name="params[mysqlbase]" size="30" type="text" value="<?php echo $mysqlbase; ?>" /></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_mysqlpass"); ?></td>
	<td><input name="params[mysqlpass]" size="30" type="text" value="<?php echo $mysqlpass; ?>" /></td>
</tr>

<tr class="Clr1">
	<td width="70%"><?php echo_lng("install_email"); ?></td>
	<td><input type="text" name="params[company_email]" size="30" value="" /></td>
</tr>

</table>

<center>
<br /><?php message(lng_get("push_next_button")); ?>
</center>

<br />
<?php
		return true;
	} else {
#
# Now trying to check if there is already database named $params["mysqlbase"]
#
		$ck_res = 1;

		$mylink = @mysql_connect($params["mysqlhost"], $params["mysqluser"], $params["mysqlpass"]);
		if (!$mylink) {
			$ck_res &= fatal_error(lng_get("error_connect"));
		}
		else if (!@mysql_select_db($params["mysqlbase"])) {
			$ck_res &= fatal_error(lng_get("error_select_db", "db", $params["mysqlbase"]));
		}
		else if (!is_writable("config.php")) {
			$ck_res &= fatal_error(lng_get("error_check_write_config"));
		}
		else {
			$mystring = "";
			$first = true;

			$res = @mysql_list_tables($params["mysqlbase"]);

			while ($row = @mysql_fetch_row($res)) {
				$ctable = $row[0];
				if ($ctable == "xcart_products")
					warning_error(lng_get("warning_db_tables_exists"));
			}

			@mysql_close ($mylink);
		}

		$country_languages = get_lang_names_re($xcart_dir.'/sql',
			'!^xcart_language_(..)\.sql$!S',$params['lngcode'], 'language');

		$country_states = get_lang_names_re($xcart_dir.'/sql',
			'!^states_(..)\.sql$!S',$params['lngcode'],'country');

		$country_preconf = get_lang_names_re($xcart_dir.'/sql',
			'!^xcart_conf_(..)\.sql$!S',$params['lngcode'],'country');

		if (count($country_preconf) > 1) {
			$country_preconf[''] = ''; # no preconfiguration by default
			asort($country_preconf);
		}

?>

<table width="100%" cellpadding="4">

<tr class="Clr2">
	<td width="70%"><?php echo_lng("install_http_name"); ?></td>
	<td><?php echo $params["xcart_http_host"] ?></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_https_name"); ?></td>
	<td><?php echo $params["xcart_https_host"] ?></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_webdir"); ?></td>
	<td><?php echo $params["xcart_web_dir"] ?></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_mysqlhost"); ?></td>
	<td><?php echo $params["mysqlhost"] ?></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_mysqluser"); ?></td>
	<td><?php echo $params["mysqluser"] ?></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_mysqldb"); ?></td>
	<td><?php echo $params["mysqlbase"] ?></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_mysqlpass"); ?></td>
	<td><?php echo $params["mysqlpass"] ?></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_email"); ?></td>
	<td><?php echo $params["company_email"] ?></td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_languages"); ?></td>
	<td>
	<select name="params[languages][]" multiple="multiple" size="4">
<?php
foreach ($country_languages as $code=>$name) {
	printf("<option value=\"%s\"%s>%s</option>\n", $code,
		($code == $params['lngcode']) ? " selected=\"selected\"" : "",
		$name);
}
?>
	</select>
	</td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_states"); ?></td>
	<td>
	<select name="params[states][]" multiple="multiple" size="5">
<?php
foreach ($country_states as $code=>$name) {
	printf("<option value=\"%s\">%s</option>\n", $code, $name);
}
?>
	</select>
	</td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_demodata"); ?></td>
	<td>
	<select name="params[demo]">
		<option value="1"><?php echo_lng('lbl_yes'); ?></option>
		<option value="0"><?php echo_lng('lbl_no'); ?></option>
	</select>
	</td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_configuration"); ?></td>
	<td>
	<select name="params[conf]">
<?php
foreach ($country_preconf as $code=>$name) {
	printf("<option value=\"%s\">%s</option>\n", $code, $name);
}
?>
	</select>
	</td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("install_update_config"); ?></td>
	<td><input type="checkbox" name="params[config_only]" value="Y" /></td>
</tr>

<tr class="Clr1">
	<td><?php echo_lng("install_store_images_in"); ?></td>
	<td>
	<select name="params[images_location]">
		<option value="FS" selected="selected"><?php echo_lng("install_store_images_fs"); ?></option>
		<option value=""><?php echo_lng("install_store_images_db"); ?></option>
	</select>
	</td>
</tr>

</table>

<center>
<?php if ($ck_res) { ?><br /><?php message(lng_get("push_next_button_to_install")); } ?>
</center>

<br />
<?php
		$error = !$ck_res;
		return false;
	}
}

function module_cfg_install_db_js_next() {
?>
	function step_next() {
		for (var i = 0; i < document.ifrm.elements.length; i++) {
			if (document.ifrm.elements[i].name.search("mysqlhost") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert ("<?php echo_lng_js("install_mysqlhost_alert"); ?>");
					return false;
				}
			}

			if (document.ifrm.elements[i].name.search("mysqluser") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert ("<?php echo_lng_js("install_mysqluser_alert"); ?>");
					return false;
				}
			}

			if (document.ifrm.elements[i].name.search("mysqlbase") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert ("<?php echo_lng_js("install_mysqldb_alert"); ?>");
					return false;
				}
			}
		}
		return true;
	}
<?php
}

#
# end: Cfg_install_db module
#

#
# start: Install_db module
#

function module_install_db(&$params) {
	global $error;
	global $installation_auth_code;
?>
</td>
</tr>
</table>

<script type="text/javascript" language="javascript">
<!--
	loaded = false;

	function refresh() {
		window.scroll(0, 100000);

		if (loaded == false)
			setTimeout('refresh()', 1000);
	}

	setTimeout('refresh()', 1000);
-->
</script>

<?php
	$ck_res = 1;


	$mylink = @mysql_connect($params["mysqlhost"], $params["mysqluser"], $params["mysqlpass"]);
	if (!$mylink) {
		$ck_res = $ck_res && fatal_error(lng_get("error_unexp_connect"));
	}
	elseif (!@mysql_select_db($params["mysqlbase"])) {
		$ck_res = $ck_res && fatal_error(lng_get("error_unexp_select_db", "db", $params["mysqlbase"]));
	}
	elseif (!is_writable("config.php")) {
		$ck_res = $ck_res && fatal_error(lng_get("error_unexp_check_write_config"));
	}
	else {

		#
		# Generate new Blowfish key
		#
		mt_srand(time());
		$params['blowfish_key'] = md5(mt_rand(0, time()));

		#
		# Updating config.php file
		#
		echo "<br /><b>".lng_get("updating_config_file")."</b><br />\n"; flush();

		$res = change_config($params);
		echo status($res)."<br />\n";

		if (!$res)
			fatal_error(lng_get("error_cannot_open_config"));

		$ck_res = $ck_res && $res;

		echo "<br /><b>".lng_get("check_crypted_data")."</b><br />\n"; flush();
        if (!empty($params["config_only"])) {
			$res = check_crypted_data();
			echo status($res)."<br />\n";

			if (!$res)
	            fatal_error(lng_get("check_crypted_data_failed"));
		}

		$ck_res = $ck_res && $res;

		if (empty($params["config_only"])) {
			$ck_res = $ck_res && do_install_db($params);
		}
		else {
			if (!$ck_res)
				fatal_error(lng_get("fatal_error_config_update"));
            else
                echo "<br /><br /><br /><br /><br />";
		}
	}
?>

<table class="TableTop" width="100%" border="0" cellspacing="0" cellpadding="0">

<tr>
	<td>

<center>
<?php if ($ck_res) { ?><br /><?php message(lng_get("push_next_button")); } ?>
</center>

<br />

<script type="text/javascript" language="javascript">
<!--
	loaded = true;
-->
</script>

<?php
	$error = !$ck_res;
	return false;
}

function do_install_db(&$params) {
	global $installation_auth_code;
	global $config, $xcart_dir, $sql_tbl, $str_out, $images_step;
	global $active_modules, $data_caches, $var_dirs;
	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS, $HTTP_COOKIE_VARS, $HTTP_POST_FILES;

	echo "<br /><b>".lng_get("creating_tables")."</b><br />\n";

	$ck_res = true;

	if ($ck_res) $ck_res = query_upload("sql/dbclear.sql");
	if ($ck_res) $ck_res = query_upload("sql/xcart_tables.sql");

	if ($ck_res) echo "<br /><b>".lng_get("importing_data")."</b><br />\n"; flush();

	if ($ck_res) $ck_res = query_upload("sql/xcart_data.sql");

	#
	# Importing languages
	#
	if ($ck_res) {
		if (empty($params["languages"]))
			$params["languages"] = array($params['lngcode']);
		echo "<br /><b>".lng_get("importing_languages")."</b><br />\n"; flush();
		if (is_array($params["languages"])) {
			foreach ($params["languages"] as $_k=>$lng_code)
				if ($ck_res) $ck_res = query_upload("sql/xcart_language_".$lng_code.".sql");
		}
	}

	#
	# Importing states
	#
	if ($ck_res && !empty($params["states"])) {
		echo "<br /><b>".lng_get("importing_states")."</b><br />\n"; flush();
		if (is_array($params["states"])) {
			foreach($params["states"] as $_k=>$country_code) {
				if ($ck_res) $ck_res = query_upload("sql/states_".$country_code.".sql");
			}
		}
	}

	#
	# Importing sample data
	#
	if ($ck_res && $params["demo"] == 1) {
		echo "<br /><b>".lng_get("importing_demodata")."</b><br />\n"; flush();

		$demo_files = array("sql/xcart_demo.sql","sql/xcart_demo_".$params["conf"].".sql");
		foreach ($demo_files as $_file) {
			if (!file_exists($xcart_dir."/".$_file)) continue;
			$ck_res = $ck_res && query_upload($_file);
			if (!$ck_res) break;
		}
	}

	#
	# Apply pre-configured settings to selected country
	#
	if ($ck_res && !empty($params["conf"])) {
		echo "<br /><b>".lng_get("importing_data")."</b><br />\n"; flush();

		$ck_res = $ck_res && query_upload("sql/xcart_conf_".$params["conf"].".sql");
	}

	if ($ck_res && !empty($params["company_email"])) {
		$ck_res = $ck_res && runquery("UPDATE xcart_config SET value='$params[company_email]' WHERE name in ('orders_department','support_department','newsletter_email','users_department','site_administrator')");
		$ck_res = $ck_res && runquery("UPDATE xcart_customers SET email='$params[company_email]'");
	}

	#
	# Move images to the file system
	#
	if ($ck_res && $params['images_location'] == "FS") {
		echo "<br /><b>".lng_get("moving_images_to_fs")."</b><br />\n"; flush();

		include $xcart_dir."/init.php";
		x_load('backoffice','image');

		# process N images per pass
		$images_step = 50;

		foreach (array_keys($config['available_images']) as $avail_type) {
			$str_out = "";
			$moved = func_move_images($avail_type, array("location" => "FS"));

			if (!$moved) {
				$ck_res = false;
				break;
			}
		}

		runquery("UPDATE xcart_setup_images SET location='FS'");
		func_build_quick_flags();
		func_data_cache_get("setup_images", array(), true);
	}

	if (!$ck_res)
		fatal_error(lng_get("fatal_error_install_db"));
	else {
		recrypt_data($params);
		@mysql_query("INSERT INTO xcart_config VALUES ('license','License','$installation_auth_code','',0,'text')");
	}

	return $ck_res;
}

#
# end: Install_db module
#

#
# start: Cfg_install_dirs module
# Get color/layout settings
#

function module_cfg_install_dirs(&$params) {
	global $error, $schemes_repository;
	$skin_descr = read_skin_descr();
?>
<center>
<p>
<b><font color="darkgreen"><?php echo_lng("select_color_n_layout"); ?>:</font></b>
</p>
</center>

<table width="100%" cellpadding="4">
<tr class="Clr2">
	<td><?php echo_lng("select_layout"); ?></td>
	<td>
	<select name="params[layout]">
		<option value="">3-columns (<?php echo_lng("default"); ?>)</option>
<?php
$layout = (empty($skin_descr['layout']) ? "" : $skin_descr['layout']);
if ($dir = @opendir($schemes_repository."/templates")) {
	while (($file = readdir($dir)) !== false) {
		if ($file!="." && $file!="..")
			echo "\t\t<option value=\"$file\"".($layout==$file ? " selected=\"selected\"" : "").">".str_replace("_"," ",$file)."</option>\n";
	}

	closedir($dir);
}
?>
	</select>
	</td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("select_color"); ?></td>
	<td>
	<select name="params[color]">
		<option value="">orange (<?php echo_lng("default"); ?>)</option>
<?php
$color = (empty($skin_descr['color']) ? "" : $skin_descr['color']);
if ($dir = @opendir($schemes_repository."/colors")) {
	while (($file = readdir($dir)) !== false) {
		if ($file!="." && $file!="..")
			echo "\t\t<option value=\"$file\"".($color==$file ? " selected=\"selected\"" : "").">$file</option>\n";
	}

	closedir($dir);
}
?>
	</select>
	</td>
</tr>

<tr class="Clr2">
	<td><?php echo_lng("select_dingbats"); ?></td>
	<td>
	<select name="params[dingbats]">
		<option value="">default</option>
<?php
$dingbats = (empty($skin_descr['dingbats']) ? "" : $skin_descr['dingbats']);
if ($dir = @opendir($schemes_repository."/dingbats")) {
	while (($file = readdir($dir)) !== false) {
		if ($file!="." && $file!="..")
			echo "\t\t<option value=\"$file\"".($dingbats==$file ? " selected=\"selected\"" : "").">$file</option>\n";
	}

	closedir($dir);
}
?>
	</select>
	</td>
</tr>
</table>

<center>
<p />
<table border="1">
<tr>
	<th width="150" bgcolor="#DDDDDD"><?php echo_lng("color_scheme"); ?></th>
	<th bgcolor="#DDDDDD"><?php echo_lng("recommended_dingbats"); ?></th>
</tr>
<tr>
	<td align="left">orange (<?php echo_lng("default"); ?>)</td>
	<td align="left">default, colortrans, redemboss, blackback, color</td>
</tr>
<tr>
	<td align="left">blue</td>
	<td align="left">colortrans, blackback, color, blue<font color="red">!</font></td>
</tr>
<tr>
	<td align="left">green</td>
	<td align="left">colortrans, greenround<font color="red">!</font>, blackback, color</td>
</tr>
<tr>
	<td align="left">grey</td>
	<td align="left">default, colortrans, redemboss, blackback, color</td>
</tr>
<tr>
	<td align="left">red</td>
	<td align="left">colortrans, redemboss, blackback, color</td>
</tr>
<tr>
	<td align="left">yellow</td>
	<td align="left">default, colortrans, greenround, redemboss, color, blue</td>
</tr>
</table>
<br />
<?php message(lng_get("push_next_button_to_install")); ?>
<br /><br />
</center>
<?php
}
#
# end: Cfg_install_dirs module
#

#
# start: Install_dirs module
#

function module_install_dirs(&$params) {
	global $directories_to_create, $files_to_create, $templates_repository, $schemes_repository, $error;
	global $xcart_dir;
	global $templates_directory;

?>
</td>
</tr>
</table>

<script type="text/javascript" language="javascript">
<!--
	loaded = false;

	function refresh() {
		window.scroll(0, 100000);

		if (loaded == false)
			setTimeout('refresh()', 1000);
	}

	setTimeout('refresh()', 1000);
-->
</script>

<?php
	$ck_res = 1;

	if (empty($params['flags']['skip_dirs'])) {
		echo "<br /><b>".lng_get("creating_directories")."</b><br />\n";

		$ck_res = $ck_res && create_dirs($directories_to_create);

		$ck_res = $ck_res && create_files($files_to_create);

		if ($ck_res && !file_exists($xcart_dir.DIRECTORY_SEPARATOR.".pgp") && file_exists($xcart_dir.DIRECTORY_SEPARATOR.".pgp.def")) {
			$ck_res = copy_files_sub(".pgp.def", ".pgp");
		}
	}

	if($ck_res) {
		echo "<br /><b>".lng_get("copying_templates")."</b><br />\n";
		$ck_res = copy_files($templates_repository);
	}

	if($ck_res && !empty($params["color"])) {
		echo "<br /><b>".lng_get("copying_color_scheme")."</b><br />\n";
		$ck_res = copy_files($schemes_repository."/colors/".$params["color"]);
	}

	if($ck_res && !empty($params["dingbats"])) {
		echo "<br /><b>".lng_get("copying_dingbats")."</b><br />\n";
		$ck_res = copy_files($schemes_repository."/dingbats/".$params["dingbats"]);
	}

	if($ck_res && !empty($params["layout"])) {
		echo "<br /><b>".lng_get("creating_layout")."</b><br />\n";
		$ck_res = copy_files($schemes_repository."/templates/".$params["layout"]);
	}

	if ($ck_res) {
		$data = sprintf("layout=%s\ncolor=%s\ndingbats=%s", $params["layout"], $params["color"], $params["dingbats"]);
		$file = $xcart_dir.DIRECTORY_SEPARATOR.$templates_directory.DIRECTORY_SEPARATOR.'.skin_descr';
		$fp = fopen($file,"w");
		$ck_res = ($fp !==false);
		if ($ck_res) {
			fwrite($fp, $data);
			fclose($fp);
		}
		else
			warning_error(lng_get("warn_file_create_failed", "file", $file));
	}

	if (!$ck_res) {
		fatal_error(lng_get("error_creating_directories"));
	} else {

		# Clean var/templates_c and var/cache directories
		$clean_dirs = array(
			"./var/templates_c",
			"./var/cache"
		);

		foreach($clean_dirs as $cd) {
			if (!is_dir($cd) || !file_exists($cd))
				continue;

			$d = @opendir($cd);
			if (!$d)
				continue;

			while ($f = readdir($d)) {
				if ($f == '.' || $f == '..')
					continue;
				@unlink($cd."/".$f);
			}
			closedir($d);
		}

		$cnf = config_get($xcart_dir);
		$location = 'home.php';

		if (!empty($cnf['xcart_web_dir']))
			$location = $cnf['xcart_web_dir'].DIR_CUSTOMER."/home.php";
?>
<a name="preview"></a>
<center>
<h3><?php echo_lng("color_layout_preview"); ?> (<a href="javascript: preview.location.reload()"><?php echo_lng("click_to_refresh"); ?></a>)</h3>
<iframe src="<?php echo $location; ?>" width="80%" height="400" scrolling="auto" frameborder="1" name="preview"></iframe>
<br />
<br />
</center>
<?php
	}
?>

<table class="TableTop" width="100%" cellspacing="0" cellpadding="0">

<tr>
	<td>

<center>
<?php if ($ck_res) { ?><br /><?php message(lng_get("push_next_button")); } ?>
</center>

<input type="hidden" name="ck_res" value="<?php echo (int)$ck_res ?>" />

<br />

<script type="text/javascript"language="javascript">
<!--
	loaded = true;
-->
</script>

<?php
	$error = !$ck_res;
	return false;
}

#
# end: Install_dirs module
#

#
# start: Generate_snapshot module
#

function module_generate_snapshot(&$params) {
	global $xcart_dir, $var_dirs, $sql_tbl, $smarty;
	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS, $HTTP_COOKIE_VARS, $HTTP_POST_FILES;

?>

	</td>
</tr>
</table>

<br /><br />

<?php
	@include $xcart_dir."/init.php";

	x_load('snapshots', 'backoffice');

	#
	# Update the 'display_states' of xcart_countries table
	#
	func_update_country_states("", true);

	#
	# Generate the system snapshot
	#

	$current_time = time();
	$md5file = f_get_md5file_name($current_time);
	echo_lng("txt_begin_generating_snapshot"); func_flush();

	$result = func_generate_snapshot($md5file, true);
	if ($result["error"]) {
		echo "<font color='red'>"; echo_lng("err_".$result["errordescr"]); echo "</font>";
	}
	else {
		$config_snapshots[] = array("time"=>$current_time, "descr"=>lng_get("installation_snapshot"));
		f_update_snapshots($config_snapshots);
		echo "<br />";
		echo_lng("msg_snapshot_generated");
		if (!empty($result["unprocessed_files"]))
			echo_lng("txt_N_unprocessed_files_in_snapshot", "unproc", $result["unprocessed_files"], "total", $result["total_files"]);
	}
	echo "<br /><br />";

?>

<table class="TableTop" width="100%" border="0" cellspacing="0" cellpadding="0">

<tr>
	<td>

<?php
}

#
# end: Generate_snapshot module
#

#
# start: Cfg_enable_paypal module
#

function module_cfg_enable_paypal(&$params) {
?>
<?php echo_lng('paypal_question'); ?>
<select name="params[force_current]">
	<option value="8"><?php echo_lng('lbl_yes'); ?></option>
	<option value="9" selected="selected"><?php echo_lng('lbl_no'); ?></option>
</select>
<br /><br /><br />
<?php
}

#
# end: Cfg_enable_paypal module
#

#
# start: Enable_paypal module
#

function module_enable_paypal(&$params) {
?>
<p><?php message(lng_get("install_web_paypal")); ?></p>

<table width="100%" border="0" cellpadding="4">

<tr class="Clr2">
	<td width="70%"><?php echo_lng('install_paypal_account'); ?></td>
	<td><input type="text" name="params[paypal_account]" size="30" value="" /></td>
</tr>

</table>

<?php echo_lng('install_web_paypal_comment'); ?>

<br /><br />
<?php
}

#
# end: Enable_paypal module
#

#
# start: Install_done module
#

function func_success() {
	global $xcart_package;
	global $installation_auth_code;
	global $install_language_charset;
	global $params;
	global $xcart_dir;
	global $installation_product;
	global $smarty, $mail_smarty;
	global $sql_tbl, $config;
	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS, $HTTP_COOKIE_VARS, $HTTP_POST_FILES;

	include $xcart_dir."/init.php";

	x_load('mail');

	$paypal_enable_id = false;
	if (!empty($params['paypal_account']) && trim($params['paypal_account']) != '') {
		$paypal_account = trim($params['paypal_account']);
		$processor = 'ps_paypal.php';
		$template = 'customer/main/payment_offline.tpl';

		$paypal_enable_id = md5(uniqid(microtime()));
		db_query("REPLACE INTO $sql_tbl[config] (category, name, value) VALUES ('', 'paypal_enable_id','$paypal_enable_id')");
		$paymentid = func_query_first_cell("SELECT paymentid FROM $sql_tbl[payment_methods] WHERE payment_method='PayPal' LIMIT 1");

		if ($paymentid === false) {
			$insert_params = array (
				'payment_method' => 'PayPal',
				'payment_script' => 'payment_cc.php',
				'payment_template' => $template,
				'active' => 'N',
				'orderby' => '999',
				'processor_file' => $processor
			);

			$paymentid = func_array2insert('payment_methods', $insert_params);
			db_query("UPDATE $sql_tbl[ccprocessors] SET paymentid='".$paymentid."', param01='".$paypal_account."', param02='".addslashes($config['Company']['company_name'])."', param03='USD' WHERE processor='".$processor."'");

			$tmp = func_query_first("SELECT * from $sql_tbl[ccprocessors] WHERE processor='ps_paypal_pro.php'");
			$cc_processor = $tmp["module_name"];
			// PayPal ExpressCheckout
			$insert_params['payment_method'] = $cc_processor.': '.$tmp['param08'];
			$insert_params['processor_file'] = 'ps_paypal_pro.php';
			$paymentid = func_array2insert('payment_methods', $insert_params);
			db_query("UPDATE $sql_tbl[ccprocessors] SET paymentid='".$paymentid."' WHERE processor='ps_paypal_pro.php'");

			// PayPal DirectPayment
			$insert_params['payment_template'] = 'customer/main/payment_cc.tpl';
			$insert_params['payment_method'] = $cc_processor.': '.$tmp['param09'];
			func_array2insert('payment_methods', $insert_params);
		}
		else {
			db_query("UPDATE $sql_tbl[ccprocessors] SET paymentid='".$paymentid."', param01='".$paypal_account."' WHERE processor='".$processor."'");
			db_query("UPDATE $sql_tbl[payment_methods] SET active='N' WHERE paymentid='".$paymentid."'");
		}

		$mail_smarty->assign("paypal_enable_id", $paypal_enable_id);
		func_send_mail($paypal_account, 'mail/paypal_enable_subj.tpl', 'mail/paypal_enable.tpl', $config["Company"]["site_administrator"], true);
	}

	ob_start();
?>
<ol>
<li><u><a href="<?php echo $xcart_catalogs['customer']; ?>/home.php"><b><?php echo_lng("customer_area"); ?></b></a></u></li>
<?php if ($xcart_package=="PRO") { ?>

<li><u><a href="<?php echo $xcart_catalogs['admin']; ?>/home.php"><b><?php echo_lng("admin_area"); ?></b></a></u><br />
<?php echo_lng("username"); ?>: admin<br />
<?php echo_lng("password"); ?>: admin<br />
</li>
<?php } ?>


<li><u><a href="<?php echo $xcart_catalogs['provider']; ?>/home.php"><b><?php echo lng_get($xcart_package=="PRO" ? "provider_area" : "admin_area") ?></b></a></u><br />
<?php echo_lng("username"); ?>: <?php echo ($xcart_package=="PRO"?"provider":"master") ?><br />
<?php echo_lng("password"); ?>: <?php echo ($xcart_package=="PRO"?"provider":"master") ?><br />

<?php if ($xcart_package=="PRO") { ?>
<br />
<?php echo_lng("username"); ?>: root<br />
<?php echo_lng("password"); ?>: root<br />
<?php } ?>
</li>
</ol>
<?php
	$interfaces = ob_get_contents();
	ob_end_clean();

?>
<center>
<h3><?php message(lng_get("install_complete")); ?></h3>
</center>

<?php if (!empty($paypal_enable_id)) { ?>
<?php echo_lng("install_paypal_mail_note"); ?>
<p />
<?php } ?>

<?php echo_lng("auth_code_for_future","code", $installation_auth_code); ?>
<p />
<?php echo_lng("distribution_warning", "product", $installation_product); ?>
<?php echo_lng("xcart_final_note");
	echo $interfaces;

	$email_message = lng_get("final_email_message",
		"interfaces", $interfaces,
		"installation_auth_code", $installation_auth_code,
		"blowfish_key", $params['blowfish_key'],
		"product", $installation_product
	);
	if (!empty($paypal_enable_id))
		$email_message .= "<br />".lng_get("install_paypal_mail_note")."<br />";

	if (empty($params['flags']['noinfomail']) && !empty($params['company_email'])) {
		$lend = (X_DEF_OS_WINDOWS?"\r\n":"\n");
		if (X_DEF_OS_WINDOWS)
			$message = preg_replace("/(?<!\r)\n/", "\r\n", $message);

		$install_wiz = lng_get("install_wiz", "product", $installation_product);
		$email_message =<<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<style type="text/css">
<!--
BODY,P,DIV,TH,TD,P,INPUT,SELECT {
	COLOR: #550000;
	FONT-FAMILY: Verdana, Arial, Helvetica;
	FONT-SIZE: 12px;
}
A {
	COLOR: #330000;
	TEXT-DECORATION: none;
}
A:hover {
	COLOR: #550000;
	TEXT-DECORATION: underline;
}
BODY {
	MARGIN: 0px;
	PADDING: 0px;
	BACKGROUND-COLOR: #FFFBD3;
}
FORM {
	MARGIN: 0px;
}
TABLE,IMG {
	BORDER: 0px;
}
-->
</style>
</head>
<body>
$email_message
<br />
<hr size="1" noshade="noshade" />
$install_wiz
</body>
</html>
EOT;
		$headers =
			"From: \"$install_wiz\" <$params[company_email]>" .  $lend .
			"X-Mailer: PHP/" . phpversion() . $lend .
			"MIME-Version: 1.0" . $lend .
			"Content-Type: text/html; charset=" . $install_language_charset . $lend;

		if (preg_match('/([^ @,;<>]+@[^ @,;<>]+)/S', $params['company_email'], $m)) {
			@mail($params['company_email'], lng_get("install_complete"), $email_message, $headers, "-f".$m[1]);
		} else {
			@mail($params['company_email'], lng_get("install_complete"), $email_message, $headers);
		}
	}

	return false;
}

#
# end: Install_done module
#

$use_sessions_type = 99;

include "./include/install.php";

?>
