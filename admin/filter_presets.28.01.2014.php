<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2012 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2012           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: filter_presets.php,v 1.0.0.0 2012/06/06 12:11:20 kirill Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('order');

if ($REQUEST_METHOD == "POST" && !empty($fid)) {
    if (!in_array($time_from_mode, array('D', 'H'))) {
        $time_from_mode = 'H';
    }
    
    $time_type = array('O', 'D');
    if (!in_array($placement_time_from_type, $time_type)) {
        $placement_time_from_type = 'O';
    }
    if (!in_array($placement_time_to_type, $time_type)) {
        $placement_time_to_type = 'O';
    }

    $update = array(
        'title'                     => trim($title),
        'time_from_date'            => mktime(0, 0, 0, intval($fromMonth), intval($fromDay), intval($fromYear)),
        'time_from'                 => intval($time_from),
        'time_from_mode'            => $time_from_mode,
        'time_to'                   => intval($time_to),
        'placement_time_from_type'  => $placement_time_from_type,
        'placement_time_to_type'    => $placement_time_to_type,
        'enabled'                   => $enabled == 'Y' ? 'Y' : 'N',
        'bold'                      => $bold == 'Y' ? 'Y' : 'N',
    );
    func_array2update('filter_presets', $update, "fid='$fid'");
    
    db_query("DELETE FROM $sql_tbl[filter_preset_statuses] WHERE fid='$fid'");
    foreach ($status as $s) {
        db_query("INSERT INTO $sql_tbl[filter_preset_statuses] (fid, status) VALUES('$fid', '$s')");
    }
    
    db_query("DELETE FROM $sql_tbl[filter_preset_distributors] WHERE fid='$fid'");
    foreach ($distributors as $d) {
        db_query("INSERT INTO $sql_tbl[filter_preset_distributors] (fid, manufacturerid) VALUES('$fid', '$d')");
    }

#
##
###
    db_query("DELETE FROM $sql_tbl[filter_preset_fraud_statuses] WHERE fid='$fid'");
    foreach ($fraud_statuses_filter as $f) {
        db_query("INSERT INTO $sql_tbl[filter_preset_fraud_statuses] (fid, fraud_status) VALUES('$fid', '$f')");
    }
###
##
#
    
    func_header_location("configuration.php?option=Filter_Presets&fid=$fid");
}

if (isset($fid)) {
    $filter = func_get_filter($fid);
    
    if (empty($filter)) {
        func_header_location('configuration.php?option=Filter_Presets');
    }
    
    $distributors = func_query_hash("SELECT manufacturerid, manufacturer FROM $sql_tbl[manufacturers] ORDER BY manufacturer", 'manufacturerid', false, false);
    foreach ($distributors as $k => $v) {
        if (in_array($k, $filter['distributors'])) {
            $distributors[$k]['selected'] = 'Y';
        }
    }
   
    $statuses = func_query_hash("SELECT code, name, type FROM $sql_tbl[order_statuses] ORDER BY orderby", array('type', 'code'), false, false);
    foreach ($statuses as $k => $v) {
        foreach ($v as $k1 => $v1) {
            if (!empty($filter[$k]) && in_array($k1, $filter[$k])) {
                $statuses[$k][$k1]['selected'] = 'Y';
            }
        }
    }

#
##
###
	foreach ($fraud_statuses as $k => $v){
		$fraud_statuses_filter[$k]["name"] = $v;

	        if (in_array($k, $filter['fraud_statuses'])) {
	            $fraud_statuses_filter[$k]['selected'] = 'Y';
	        }
	}
	$smarty->assign('fraud_statuses_filter', $fraud_statuses_filter);
###
##
#
     
    $smarty->assign('filter', $filter);
    $smarty->assign('distributors', $distributors);
    $smarty->assign('statuses', $statuses);
    
    $smarty->assign('filter_preset_title', 'title');
} else {
    $_filters = func_query("SELECT * FROM $sql_tbl[filter_presets] ORDER BY fid");

    $count = 0;
    $filters = array();
    foreach ($_filters as $k => $v) {
        if ($count % FILTER_PRESET_PER_ROW == 0) {
            $filters[] = array();
        }
        $filters[count($filters) - 1][] = $_filters[$k];
        $count++;
    }

    $smarty->assign('filters', $filters);
}

?>
