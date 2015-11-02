<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: func.php,v 1.0 2010/11/26 13:31:24 kate Exp $
#

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

#
# Substitute several config values for current storefront. If $substitute is not set, use the values from the 
# storefronts_config table.
#
function func_sf_substitute_config_values($sf_id, $substitute = array()) {
	global $config, $sql_tbl, $current_storefront;

	if (empty($substitute)) {
		if (!isset($sf_id)) {
			return false;
		} else {
			$substitute = func_query_hash('SELECT name, value, category FROM ' . $sql_tbl['storefronts_config'] . ' WHERE storefrontid=' . $sf_id . ' AND type != "separator"', 'category', true, false);
		}
	}

	if (empty($substitute) || !is_array($substitute)) {
		return false;
	} else {
		foreach ($substitute as $c => $vs) {
			foreach ($vs as $v) {
				$config[$c][$v['name']] = $v['value'];
			}
		}
		return true;
	}
}

#
# Get default values of the SF specific properties
# $type
# H - hierarchy (Category -> Name -> Value)
# S - short (Name -> Value)
# F - full
#

function func_get_default_config($type = 'H') {
    global $domain_specific_config, $sql_tbl;

    $result = array();

    if (is_array($domain_specific_config)) {
        foreach ($domain_specific_config as $cat=>$opt) {
            if (is_array($opt) && !empty($opt)) {
                $names = implode('", "', array_keys($opt));
                if ($type == 'F') {
                    $result_part = func_query('SELECT * FROM ' . $sql_tbl['config'] 
                        . ' WHERE category="' . $cat . '" AND name IN ("' . $names . '")');
                    $result = array_merge($result, $result_part);
                } elseif ($type == 'H') {
                $result[$cat] = func_query_hash('SELECT name, value FROM ' . $sql_tbl['config'] 
                    . ' WHERE category="' . $cat . '" AND name IN ("' . $names . '")', 'name', false, true);
                } elseif ($type == 'S') {
                    $result = $result + func_query_hash('SELECT name, type FROM ' . $sql_tbl['config'] 
                        . ' WHERE category="' . $cat . '" AND name IN ("' . $names . '")', 'name', false, true);
                }
            }
        }

        if ($type == 'F') {
            foreach ($result as $k => $r) {
                $result[$k]['orderby'] = $domain_specific_config[$r['category']][$r['name']];
            }
            usort($result, 'func_sort_arr_by_orderby');
        }
    }

    return $result;
}

#
# Gather all necessary storefront information
# type: ID - numeric unique identifier, D - domain name
#

function func_get_storefront_info($sf_id, $type = 'ID', $full = false) {
	global $sql_tbl, $config;

	x_load('files', 'image');

	$sf_info = array();

	if ($type == 'ID') {
		
		$sf_id = intval($sf_id);
	
		if ($sf_id == 0) {
			$sf_info = array(
				'storefrontid'	=> 0,
				'status'		=> ($config['General']['shop_closed'] == 'Y') ? 'D' : 'E',
				'prefix'		=> MAIN_SF_PREFIX,
				'top_banner'	=> 'default',
                'domain'        => MAIN_SF_DOMAIN,
			);
		} else {
			if ($sf_id > 0) {
				$sf_info = func_query_first('SELECT s.storefrontid, s.status, s.domain, c.value as prefix FROM ' . $sql_tbl['storefronts'] . ' as s'
                    . ' LEFT JOIN ' . $sql_tbl['storefronts_config'] . ' as c ON s.storefrontid=c.storefrontid'
                    . ' WHERE s.storefrontid=' . $sf_id . ' AND c.name="opt_order_prefix"');
			} else {
				return false;
			}
		}
	} else {
		$sf_info = func_query_first('SELECT storefrontid, status, prefix FROM ' . $sql_tbl['storefronts'] . ' WHERE domain = "' . $sf_id . '"');
	}

	if (isset($sf_info['storefrontid']) /* && !empty($sf_info['storefrontid'])*/ ) {
		$tmp = func_image_properties('S', $sf_info['storefrontid']);
		$sf_info['is_image'] = (!empty($tmp) && is_array($tmp)) ? true : false;
		if ($sf_info['is_image']) {
			$sf_info['image'] = $tmp;
		$sf_info['image']['image_path'] = func_get_image_url($sf_info['storefrontid'], 'S', $sf_info['image']['image_path']);
	}
	}

    if ($full) {
        if ($sf_id == 0) {
            $sf_info['config'] = func_get_default_config();
        } else {
            $sf_config = func_query_hash('SELECT name, value, category FROM ' . $sql_tbl['storefronts_config'] . ' WHERE storefrontid=' . $sf_id . ' AND type != "separator"', 'category', true, false);
            if (is_array($sf_config) && !empty($sf_config)) {
                foreach ($sf_config as $c => $vs) {
                    foreach ($vs as $v) {
                        $sf_info['config'][$c][$v['name']] = $v['value'];
                    }
                }
            }
        }
    }

	return $sf_info;
}

function func_get_http_location_sf($sfid) {
    global $sql_tbl;

    $sfid = intval($sfid);

    if ($sfid != 0) {
        $sf_domain = func_query_first_cell('SELECT domain FROM ' . $sql_tbl['storefronts'] . ' WHERE storefrontid = ' . $sfid);
        if (!empty($sf_domain)) {
            return $sf_domain;
        }
    } else {
        return MAIN_SF_DOMAIN;
    }

    return false;
}

/**
 * Check order prefix list (all storefronts)
 *
 * @param string  $prefix       Order prefix
 * @param integer $storefrontid Storefront that must be excluded from check
 *
 * @return boolean
 */
function func_msf_is_unique_order_prefix($prefix, $storefrontid) {
	global $sql_tbl, $config;

	$prefix = (string) trim($prefix);
	$storefrontid = (int) $storefrontid;

	$main_sf_config = func_get_default_config('H');
	$main_prefix = $main_sf_config['General']['opt_order_prefix'];

	$is_unique = (
		($main_prefix != $prefix || $storefrontid == 0)
		&& func_query_first_cell(
			"SELECT COUNT(value) FROM $sql_tbl[storefronts_config]"
			. " WHERE name = 'opt_order_prefix' AND value = '$prefix' AND storefrontid != '$storefrontid'"
		) == '0'
	);

	return $is_unique;
}

/**
 * Sort storefront array (by orderby field)
 */
function func_msf_sort_config_array($a, $b) {
	$res = ($a['orderby'] == $b['orderby']) ? 0 : (($a['orderby'] < $b['orderby']) ? -1 : 1);
	return $res == 0 ? strcmp($a['config']['company_name'], $b['config']['company_name']) : $res;
}

/**
 * Sort storefront array (by orderby field)
 */
function func_msf_sort_front_array($a, $b) {
        return strcmp($a['company_name'], $b['company_name']);
}

/**
 * Sort storefront array (by name field)
 */
function func_msf_sort_front_array_by_name($a, $b) {
        return strcmp($a['name'], $b['name']);
}

?>
