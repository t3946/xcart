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
# $Id: func.db.php,v 1.9.2.10 2006/10/31 13:23:35 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

/***
 * Mimic for @mysql_real_escape_string
 *
 * @param array|string $inp
 * @return array|string
 */
function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'","\"", "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "''", "\"", '\\Z'), $inp);
    }

    return $inp;
}

function db_query($query) {
	return \Xcart\Connection::getInstance()->executeQuery($query);
}

function db_result(\Doctrine\DBAL\Driver\Statement $result, $offset) {
	return db_fetch_field($result, $offset);
}

function db_fetch_row(\Doctrine\DBAL\Driver\Statement $result) {
	return $result->fetch(PDO::FETCH_NUM);
}

function db_fetch_array(\Doctrine\DBAL\Driver\Statement $result, $flag=MYSQL_ASSOC) {
	return $result->fetch(PDO::FETCH_ASSOC);
}

function db_fetch_field(\Doctrine\DBAL\Driver\Statement $result, $num = 0) {
	return $result->fetchColumn($num);
}

function db_free_result(\Doctrine\DBAL\Driver\Statement $result) {
	if ($result) {
		$result->closeCursor();
	}
}

function db_num_rows(\Doctrine\DBAL\Driver\Statement $result) {
	return $result->rowCount();
}

function db_num_fields(\Doctrine\DBAL\Driver\Statement $result) {
	return $result->columnCount();
}

function db_insert_id() {
	return \Xcart\Connection::getInstance()->lastInsertId();
}

function db_affected_rows(\Doctrine\DBAL\Driver\Statement $result) {
	return $result->rowCount();
}

function db_mysql_get_server_info()
{
	return \Xcart\Connection::getInstance()->getWrappedConnection()->getServerVersion();
}



function db_error_generic($query, $query_error, $msg) {
	global $debug_mode, $config, $xcart_dir;

	$email = false;

	if (@$config["Email_Note"]["admin_sqlerror_notify"]=="Y") {
		$email = array ($config["Company"]["site_administrator"]);
	}

	if ($debug_mode == 1 || $debug_mode == 3) {
		echo "<b><font COLOR=DARKRED>INVALID SQL: </font></b>".htmlspecialchars($query_error)."<br />";
		echo "<b><font COLOR=DARKRED>SQL QUERY FAILURE:</font></b>".htmlspecialchars($query)."<br />";
		flush();
	}

	$do_log = ($debug_mode == 2 || $debug_mode == 3);

	if ($email !== false || $do_log) {
		@require_once $xcart_dir."/include/logging.php";
		x_log_add('SQL', $msg, true, 1, $email, !$do_log);
	}
}

function db_prepare_query($query, $params) {
	static $prepared = array();

	if (!empty($prepared[$query])) {
		$info = $prepared[$query];
		$tokens = $info['tokens'];
	}
	else {
		$tokens = preg_split('/((?<!\\\)\?)/S', $query, -1, PREG_SPLIT_DELIM_CAPTURE);

		$count = 0;
		foreach ($tokens as $k=>$v) if ($v === '?') $count ++;

		$info = array (
			'tokens' => $tokens,
			'param_count' => $count
		);
		$prepared[$query] = $info;
	}

	if (count($params) != $info['param_count']) {
		return array (
			'info' => 'mismatch',
			'expected' => $info['param_count'],
			'actual' => count($params));
	}

	$pos = 0;
	foreach ($tokens as $k=>$val) {
		if ($val !== '?') continue;

		if (!isset($params[$pos])) {
			return array (
				'info' => 'missing',
				'param' => $pos,
				'expected' => $info['param_count'],
				'actual' => count($params));
		}

		$val = $params[$pos];
		if (is_array($val)) {
			$val = func_array_map('addslashes', $val);
			$val = implode("','", $val);
		}
		else {
			$val = addslashes($val);
		}

		$tokens[$k] = "'" . $val . "'";
		$pos ++;
	}

	return implode('', $tokens);
}

#
# New DB API: Executing parameterized queries
# Example1:
#   $query = "SELECT * FROM table WHERE field1=? AND field2=? AND field3='\\?'"
#   $params = array (val1, val2)
#   query to execute:
#      "SELECT * FROM table WHERE field1='val1' AND field2='val2' AND field3='\\?'"
# Example2:
#   $query = "SELECT * FROM table WHERE field1=? AND field2 IN (?)"
#   $params = array (val1, array(val2,val3))
#   query to execute:
#      "SELECT * FROM table WHERE field1='val1' AND field2 IN ('val2','val3')"
#
# Warning:
#  1) all parameters must not be escaped with addslashes()
#  2) non-parameter symbols '?' must be escaped with a '\'
#
function db_exec($query, $params = []) {
	if (!is_array($params)) {
		$params = [$params];
	}
	return db_query(db_prepare_query($query, $params));
}

#
# Execute mysql query and store result into associative array with
# column names as keys
#
function func_query($query) {
	$result = false;

	if ($p_result = db_query($query)) {
		while ($arr = db_fetch_array($p_result))
			$result[] = $arr;
		db_free_result($p_result);
	}

	return $result;
}

#
# Execute mysql query and store result into associative array with
# column names as keys and then return first element of this array
# If array is empty return array().
#
function func_query_first($query) {
	if ($p_result = db_query($query)) {
		$result = db_fetch_array($p_result);
		db_free_result($p_result);
        }

        return is_array($result) ? $result : array();
}

#
# Execute mysql query and store result into associative array with
# column names as keys and then return first cell of first element of this array
# If array is empty return false.
#
function func_query_first_cell($query) {
	if ($p_result = db_query($query)) {
		$result = db_fetch_row($p_result);
		db_free_result($p_result);
	}

	return is_array($result) ? $result[0] : false;
}

function func_query_column($query, $column = 0) {
	$result = array();

	$fetch_func = is_int($column) ? 'db_fetch_row' : 'db_fetch_array';

	if ($p_result = db_query($query)) {
		while ($row = $fetch_func($p_result))
			$result[] = $row[$column];

		db_free_result($p_result);
	}

	return $result;
}

#
# Insert array data to table
#
function func_array2insert ($tbl, $arr, $is_replace = false, $is_ignore = false) {
	global $sql_tbl;

	if (empty($tbl) || empty($arr) || !is_array($arr))
		return false;

	if (!empty($sql_tbl[$tbl]))
		$tbl = $sql_tbl[$tbl];

	if ($is_replace )
		$query = "REPLACE";
	else
		$query = "INSERT";

	if ($is_ignore)
		$query = "INSERT IGNORE";

	func_check_tbl_fields($tbl, array_keys($arr));

	$arr_keys = array_keys($arr);
	foreach ($arr_keys as $k=>$v) {
		if (!preg_match("/^`.*`$/", $v)) $arr_keys[$k] = "`$v`";
	}

	$arr_values = array_values($arr);
	foreach ($arr_values as $k=>$v) {
		if ((!preg_match("/^'.*'$/", $v)) && (!preg_match('/^".*"$/', $v))) $arr_values[$k] = "'$v'";
	}

	$query .= " INTO $tbl (" . implode(", ", $arr_keys) . ") VALUES (" . implode(", ", $arr_values) . ")";

	$r = db_query($query);
	if ($r) {
		return db_insert_id();
	}

	return false;
}

#
# Update array data to table + where statament
#
function func_array2update ($tbl, $arr, $where = '') {
	global $sql_tbl;

	if (empty($tbl) || empty($arr) || !is_array($arr))
		return false;

	if ($sql_tbl[$tbl])
		$tbl = $sql_tbl[$tbl];

	$r = array();
	foreach ($arr as $k => $v) {
		if (!preg_match("/^`.*`$/", $k)) $k = "`$k`";
		if ((!preg_match("/^'.*'$/", $v)) && (!preg_match('/^".*"$/', $v))) $v = "'$v'";
		$r[] = "$k=$v";
	}

	func_check_tbl_fields($tbl, array_keys($arr));

	$query = "UPDATE $tbl SET ". implode(", ", $r) . ($where ? " WHERE " . $where : "");

	return db_query($query);
}

function func_query_hash($query, $column = false, $is_multirow = true, $only_first = false) {
	$result = array();
	$is_multicolumn = false;
	$is = null;

	if ($p_result = db_query($query)) {
		if ($column === false) {

			# Get first field name 
			$c = db_fetch_field($p_result);
			$column = $c->name;

		} elseif (is_array($column)) {
			if (count($column) == 1) {
				$column = current($column);

			} else {
				$is_multicolumn = true;
			}
		}

		while ($row = db_fetch_array($p_result)) {

			# Get key(s) column value and remove this column from row
			if ($is_multicolumn) {

				$keys = array();
				foreach ($column as $c) {
					$keys[] = $row[$c];
					func_unset($row, $c);
				}
				$keys = implode('"]["', $keys);

			} else {
				$key = $row[$column];
				func_unset($row, $column);
			}

			if ($only_first)
				$row = array_shift($row);

			if ($is_multicolumn) {

				# If keys count > 1
				if ($is_multirow) {
					eval('$result["'.$keys.'"][] = $row;');

				} else {
					eval('$is = isset($result["'.$keys.'"]);');
					if (!$is) {
						eval('$result["'.$keys.'"] = $row;');
					}
				}

			} elseif ($is_multirow) {
				$result[$key][] = $row;

			} elseif (!isset($result[$key])) {
				$result[$key] = $row;
			}
		}

		db_free_result($p_result);
	}

	return $result;
}

#
# Generate unique id
#  $type - one character
# Currently used types:
#  U - for users (anonymous)
#
function func_genid($type) {
	global $sql_tbl;

	db_query("INSERT INTO $sql_tbl[counters] (type) VALUES ('$type')");
	$value = db_insert_id();

	if ($value < 1)
		trigger_error("Cannot generate unique id", E_USER_ERROR);

	db_query("DELETE FROM $sql_tbl[counters] WHERE type='$type' AND value<'$value'");

	return $value;
}

#
# Generate SQL-query relations
#
function func_generate_joins($joins, $parent = false) {
	$str = '';

	foreach ($joins as $jname => $j) {
		if ((!empty($parent) && $parent != $j['parent']) || (empty($parent) && !empty($j['parent'])))
			continue;

		$str .= func_build_join($jname, $j);
		unset($joins[$jname]);

		list($js, $tmp) = func_generate_joins($joins, (empty($j['tblname']) ? $jname : $j['tblname']));
		$str .= $tmp;
		$keys = array_diff(array_keys($joins), array_keys($js));
		if (!empty($keys)) {
			foreach ($joins as $k => $v) {
				if (in_array($k, $keys))
					unset($joins[$k]);
			}
		}
	}

	if (empty($parent) && !empty($joins)) {
		foreach ($joins as $jname => $j) {
			$str .= func_build_join($jname, $j);
		}
		unset($joins);
	}

	if ($parent === false)
		return $str;
	else
		return array($joins, $str);
}

#
# Get [LEFT | INNER] JOIN string
#
function func_build_join($jname, $join) {
	global $sql_tbl;

	$str = " ".($join['is_inner'] ? "INNER" : "LEFT")." JOIN ";
	if (!empty($join['tblname'])) {
		$str .= $sql_tbl[$join['tblname']]." as ".$jname;
	} else {
		$str .= $sql_tbl[$jname];
	}
	$str .= " ON ".$join['on'];

	return $str;
}

#
# Check table fields names
#
function func_check_tbl_fields($tbl, $fields) {
	static $storage = array();
	global $sql_tbl;

	if (empty($fields))
		return;

	if (!is_array($fields))
		func_header_location("error_message.php?access_denied&id=77");
		

	if (!is_array($tbl))
		$tbl = array($tbl);

	$fields_orig = array();
	foreach ($tbl as $t) {
		if (isset($sql_tbl[$t]))
			$t = $sql_tbl[$t];

		if (!isset($storage[$t])) {
			$storage[$t] = func_query_column("SHOW FIELDS FROM ".$t);
			if (empty($storage[$t]))
				func_header_location("error_message.php?access_denied&id=78");
		}

		$fields_orig = func_array_merge($fields_orig, $storage[$t]);
	}

	$fields_orig = array_unique($fields_orig);
	$res = array_diff($fields, $fields_orig);
	if (!empty($res))
		func_header_location("error_message.php?access_denied&id=79");
}

function func_get_column_from_array($col, $arr) {
    if (empty($arr) || !is_array($arr) || empty($col)) {
        return false;
    }

    $result = array();

    foreach ($arr as $k => $a) {
        if (isset($a[$col])) {
            $result[$k] = $a[$col];
        }
    }

    return $result;
}


function func_get_first_last_name($first_name) {

	$first_name = trim($first_name);
	$name_arr = explode(" ", $first_name);
	$name_arr_count = count($name_arr);

	$last_name = "";
	if ($name_arr_count > 1){
		$last_name = array_pop($name_arr);
		unset($name_arr[$name_arr_count]);
		$first_name = implode(" ", $name_arr);
	}

	$new_first_last_name["first_name"] = $first_name;
	$new_first_last_name["last_name"] = $last_name;

	return $new_first_last_name;
}

?>
