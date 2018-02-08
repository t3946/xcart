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
# $Id: contact_us_profiles.php,v 1.7.2.1 2006/08/10 12:11:20 max Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('backoffice', 'user', 'mail');

#
# Serialized arrays:
#
# Standart fields descriptions and statuses:
# $config["Contact_Us"]["contact_us_fields"]
#   array:
#       field = field_name
#       avail = "BCP"
#       required = "BCP"
#

$usertypes_array = array('C' => '');
if (!empty($active_modules['XAffiliate'])) {
	$usertypes_array['B'] = '';
}
if (empty($active_modules['Simple_Mode'])) {
	$usertypes_array['P'] = '';
}

if($mode == 'update_status' && $REQUEST_METHOD == 'POST') {
	$tmp = array();

    if (!empty($data)) {
        $default_data = $data['default'];
        $add_data = $data['additional'];
    }

	if($default_data) {
		foreach ($usertypes_array as $utype => $to_disable) {
			if ($default_data['department']['avail'][$utype] != $default_data['department']['required'][$utype]) {
				$default_data['department']['required'][$utype] = $default_data['department']['avail'][$utype];
			}
		}
		foreach($default_data as $k => $v) {
			$tmp[] = array(
                'field' => $k, 
                'avail' => @implode('', @array_keys($v['avail'])), 
                'required' => @implode('', @array_keys($v['required'])),
                'orderby'   => $v['orderby']
            );
		}
	}
	$tmp_string = addslashes(serialize($tmp));
	db_query("REPLACE INTO $sql_tbl[config] (name, value, category) VALUES ('contact_us_fields', '$tmp_string', 'Contact_Us')");
	db_query("UPDATE $sql_tbl[contact_fields] SET avail = '', required = ''");
	if($add_data) {
		foreach($add_data as $k => $v) {
			db_query('UPDATE ' . $sql_tbl['contact_fields'] . ' SET avail = "' . @implode('', @array_keys($v['avail']))
                . '", required = "' . @implode('', @array_keys($v['required']))
                . '", orderby = "' . $v['orderby']
                . '" WHERE field = "' . $k . '"');
		}
	}

//	if (is_array($deps)) {

	$dep_names = array();
	$cidev_dep = func_query("SELECT name FROM $sql_tbl[departments] WHERE frozen='Y'");
	if (!empty($cidev_dep) && is_array($cidev_dep)){
		foreach ($cidev_dep as $dep_name){
			$dep_names[] = $dep_name["name"];
		}
	}

//func_print_r($dep_names, $deps);
//die();

	db_query('DELETE FROM ' . $sql_tbl['departments']);

	if (is_array($deps)) {
		foreach ($deps as $d) {
			if (!empty($d['name']) && !empty($d['email']) && func_check_email($d['email'])) {

				$frozen = "";
				if (in_array($d['name'], $dep_names)){
					$frozen = "Y";
				}

				$dquery = array(
					'name' => $d['name'],
					'email' => $d['email'],
					'frozen' => $frozen,
				);
				func_array2insert('departments', $dquery);
			} else {
				$top_message['content'] = func_get_langvar_by_name('err_departments_contactus', null, false, true);
				$top_message['type'] = 'E';
			}
		}
	}
}
elseif ($mode == 'update_fields' && $REQUEST_METHOD == 'POST') {
	if ($update) {
		foreach ($update as $k => $v) {
			func_languages_alt_insert("lbl_contact_field_".$k, $v['field'], $current_language);
			unset($v['field']);
			if ($v['type'] == 'S' && $v['variants'])
				$v['variants'] = implode(";", array_filter(explode(";", $v['variants']), "func_callback_empty"));
			else
				$v['variants'] = '';

			func_array2update("contact_fields", $v, "fieldid = '$k'");
		}
	}

	if ($newfield && (($newfield_variants && $newfield_type == 'S') || $newfield_type != 'S')) {
		if (!$newfield_orderby)
			$newfield_orderby = func_query_first_cell("SELECT MAX(orderby) FROM $sql_tbl[contact_fields]")+1;
		if ($newfield_type == 'S')
			$newfield_variants = implode(";", array_filter(explode(";", $newfield_variants), "func_callback_empty"));
		else
			$newfield_variants = '';

		db_query("INSERT INTO $sql_tbl[contact_fields] (field, type, orderby, variants) VALUES ('$newfield', '$newfield_type', '$newfield_orderby', '$newfield_variants')");
		$id = db_insert_id();
		func_languages_alt_insert("lbl_contact_field_".$id, $newfield);
	}
}
elseif ($mode == 'delete' && $REQUEST_METHOD == 'POST' && $fields) {
	db_query("DELETE FROM $sql_tbl[contact_fields] WHERE fieldid IN ('".implode("', '", array_keys($fields))."')");
	db_query("DELETE FROM $sql_tbl[languages_alt] WHERE SUBSTRING(name, 20) IN ('".implode("', '", array_keys($fields))."') AND name LIKE 'lbl_contact_field_%'");
}

if ($mode) {
	func_header_location("configuration.php?option=Contact_Us");
}

foreach ($default_contact_us_fields as $k=>$v) {
	$default_contact_us_fields[$k]["title"] = func_get_default_field($k);
}

$default_fields = unserialize($config["Contact_Us"]["contact_us_fields"]);
if (!$default_fields) {
	$default_fields = array();
	$enabled_field = array("B" => 'Y', "C" => 'Y', "P" => 'Y');
	foreach ($default_contact_us_fields as $k => $v) {
		$default_fields[] = array(
			"title" => $v['title'],
			"field" => $k,
			"avail" => ($v['avail'] == 'Y' ? $enabled_field : $v['avail']),
			'required' => (($v['required'] == 'Y') ? $enabled_field : $v['required']),
            'ftype'  => 'default',
            'orderby'=> 0
		);
	}
}
else {
	foreach ($default_fields as $k => $v) {
		$v["title"] = func_get_default_field($v['field']);
		$v['avail'] = func_keys2hash($v['avail']);
		$v['required'] = func_keys2hash($v['required']);
        $v['ftype'] = 'default';
        $v['orderby'] = intval($v['orderby']);
		$default_fields[$k] = $v;
	}
}

$additional_fields = func_get_add_contact_fields();

$all_fields = func_contact_fields_sort($default_fields, $additional_fields);

$smarty->assign('all_fields', $all_fields);
$smarty->assign('additional_fields', $additional_fields);

$departments = func_query('SELECT `name`, `email`, `frozen` FROM ' . $sql_tbl['departments']);
if (!empty($departments)) {
	foreach ($departments as $d) {
		$d['name'] = stripslashes($d['name']);
		$d['email'] = stripslashes($d['email']);
	}
	$smarty->assign('departments', $departments);
	$smarty->assign('qdeps', count($departments));
}

$smarty->assign("usertypes_array", $usertypes_array);
$smarty->assign("usertypes_array_count", count($usertypes_array));

# Field types
$types = array(
	"T" => "Text",
	"C" => "Checkbox",
	"S" => "Select box",
);
$smarty->assign("sections", $sections);
$smarty->assign("types", $types);

?>
