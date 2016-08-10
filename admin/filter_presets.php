<?php
/*****************************************************************************\
 * +-----------------------------------------------------------------------------+
 * | X-Cart                                                                      |
 * | Copyright (c) 2001-2012 Ruslan R. Fazliev <rrf@rrf.ru>                      |
 * | All rights reserved.                                                        |
 * +-----------------------------------------------------------------------------+
 * | PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
 * | FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
 * | AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
 * |                                                                             |
 * | THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
 * | THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
 * | FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
 * | AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
 * | PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
 * | CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
 * | COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
 * | (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
 * | LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
 * | AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
 * | OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
 * | AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
 * | THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
 * | THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
 * |                                                                             |
 * | The Initial Developer of the Original Code is Ruslan R. Fazliev             |
 * | Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2012           |
 * | Ruslan R. Fazliev. All Rights Reserved.                                     |
 * +-----------------------------------------------------------------------------+
 * \*****************************************************************************/

#
# $Id: filter_presets.php,v 1.0.0.0 2012/06/06 12:11:20 kirill Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('order');

if ($REQUEST_METHOD == "POST" && !empty($fid)) {
    if (!in_array($time_from_mode, array('D', 'H'))) {
        $time_from_mode = 'H';
    }
   
    $time_type = array('O', 'D', 'M', 'R');
    if (!in_array($placement_time_from_type, $time_type)) {
        $placement_time_from_type = 'O';
    }
    if (!in_array($placement_time_to_type, $time_type)) {
        $placement_time_to_type = 'O';
    }

    $preset_position = preg_replace("/[^0-9\,]/S","",$preset_position);

    $preset_position_substr_count = substr_count($preset_position, ',');
    if ($preset_position_substr_count > 1){
	$preset_position_arr = explode(",", $preset_position);
	$preset_position = $preset_position_arr[0].",".$preset_position_arr[1];
    } elseif ($preset_position_substr_count == 1){
	$preset_position_arr = explode(",", $preset_position);
	if (empty($preset_position_arr[0]) || empty($preset_position_arr[1])){
		$preset_position = "";
	}
    } elseif ($preset_position_substr_count == 0){
		$preset_position = "";
    }

    if (!empty($preset_position)){
	if ($preset_position_arr[1] > FILTER_PRESET_PER_ROW){
		$preset_position = "";
	}

	$count_filter_presets = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[filter_presets]");
	$count_rows_in_filter_presets = ceil($count_filter_presets/FILTER_PRESET_PER_ROW);

        if ($preset_position_arr[0] > $count_rows_in_filter_presets){
                $preset_position = "";
        }
    }

    if (!empty($preset_position)){

	$used_preset_position_fid = func_query_first_cell("SELECT fid FROM $sql_tbl[filter_presets] WHERE preset_position='$preset_position' AND fid!='$fid'");

//func_print_r($used_preset_position_fid, $preset_position);
//die();

	if (!empty($used_preset_position_fid)){

//		$found_empty_preset_position = false;

//                for ($i=FILTER_PRESET_PER_ROW; $i>=1; $i--){
//                        for ($j=$count_rows_in_filter_presets; $j>=1; $j--){

//				$tmp_preset_position = $j.",".$i;
//				$is_such_preset_position = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[filter_presets] WHERE preset_position='$tmp_preset_position'");
//				if (empty($is_such_preset_position) || $is_such_preset_position == 0){
					$curren_title = func_query_first_cell("SELECT title FROM $sql_tbl[filter_presets] WHERE fid='$used_preset_position_fid'");
					$tmp_preset_position = "";
					$new_title = "";
					if (!empty($curren_title)){
						$new_title = $curren_title." (MOVED)";
					}


					db_query("UPDATE $sql_tbl[filter_presets] SET preset_position='$tmp_preset_position', title='$new_title' WHERE fid='$used_preset_position_fid'");

//func_print_r($new_title, $tmp_preset_position, $used_preset_position_fid);
//die();

//					$found_empty_preset_position = true;
//					break;
//				}
//			}

//			if ($found_empty_preset_position){
//				break;
//			}
//		}

	}



    }

    $update = array(
        'title'                     => trim($title),
        'preset_position'           => $preset_position,
        'marker'		    => $marker,
        'orders_source'		    => $orders_source,
        'processor_empty'           => $processor_empty,
        'time_from_date'            => mktime(0, 0, 0, intval($fromMonth), intval($fromDay), intval($fromYear)),
        'time_from'                 => intval($time_from),
        'time_from_mode'            => $time_from_mode,
        'time_to'                   => intval($time_to),
        'placement_time_from_type'  => $placement_time_from_type,
        'placement_time_to_type'    => $placement_time_to_type,
        'enabled'                   => $enabled == 'Y' ? 'Y' : 'N',
        'bold'                      => $bold == 'Y' ? 'Y' : 'N',
        'direct_link'  => $filter_direct_link,
    );
    func_array2update('filter_presets', $update, "fid='$fid'");
    
    db_query("DELETE FROM $sql_tbl[filter_preset_statuses] WHERE fid='$fid'");
    if (!empty($status))
    foreach ($status as $s) {
        db_query("INSERT INTO $sql_tbl[filter_preset_statuses] (fid, status) VALUES('$fid', '$s')");
    }
    
    db_query("DELETE FROM $sql_tbl[filter_preset_distributors] WHERE fid='$fid'");
    if (!empty($distributors))
    foreach ($distributors as $d) {
        db_query("INSERT INTO $sql_tbl[filter_preset_distributors] (fid, manufacturerid) VALUES('$fid', '$d')");
    }

#
##
###
    db_query("DELETE FROM $sql_tbl[filter_preset_product_question_statuses] WHERE fid='$fid'");
    if (!empty($product_question_statuses_filter))
    foreach ($product_question_statuses_filter as $f) {
        db_query("INSERT INTO $sql_tbl[filter_preset_product_question_statuses] (fid, pq_status) VALUES('$fid', '$f')");
    }

    db_query("DELETE FROM $sql_tbl[filter_preset_storefronts] WHERE fid='$fid'");
    if (!empty($storefronts_filter))
    foreach ($storefronts_filter as $f) {
        db_query("INSERT INTO $sql_tbl[filter_preset_storefronts] (fid, storefrontid) VALUES('$fid', '$f')");
    }

    db_query("DELETE FROM $sql_tbl[filter_preset_fraud_statuses] WHERE fid='$fid'");
    if (!empty($fraud_statuses_filter))
    foreach ($fraud_statuses_filter as $f) {
        db_query("INSERT INTO $sql_tbl[filter_preset_fraud_statuses] (fid, fraud_status) VALUES('$fid', '$f')");
    }


    db_query("DELETE FROM $sql_tbl[filter_preset_attention_tag_statuses] WHERE fid='$fid'");
    if (!empty($attention_tags_values_filter))
    foreach ($attention_tags_values_filter as $f) {
	if (!empty($f)){
	        db_query("INSERT INTO $sql_tbl[filter_preset_attention_tag_statuses] (fid, status_id) VALUES('$fid', '$f')");
	}
    }


    db_query("DELETE FROM $sql_tbl[filter_preset_ship_to_country] WHERE fid='$fid'");
    if (!empty($ship_to_countries_filter))
        foreach ($ship_to_countries_filter as $f) {
            db_query("INSERT INTO $sql_tbl[filter_preset_ship_to_country] (fid, country_code) VALUES('$fid', '$f')");
        }

    db_query("DELETE FROM $sql_tbl[filter_preset_po_statuses] WHERE fid='$fid'");
    if (!empty($po_status))
    foreach ($po_status as $ps) {
        db_query("INSERT INTO $sql_tbl[filter_preset_po_statuses] (fid, status) VALUES('$fid', '$ps')");
    }

    func_header_location("configuration.php?option=Filter_Presets&fid=$fid");
}

if (isset($fid)) {
    $filter = func_get_filter($fid);
    
    if (empty($filter)) {
        func_header_location('configuration.php?option=Filter_Presets');
    }

/*
###   
    $all_storefronts = $storefronts;
    $storefronts_0[0] = func_get_storefront_info(0, 'ID');
    $all_storefronts = array_merge($storefronts_0, $all_storefronts);
    $smarty->assign('all_storefronts', $all_storefronts);
###
*/
 
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
        foreach ($product_question_statuses as $k => $v){
                $product_question_statuses_filter[$k]["name"] = $v;

                if (in_array($k, $filter['product_question_statuses'])) {
                    $product_question_statuses_filter[$k]['selected'] = 'Y';
                }
        }
        $smarty->assign('product_question_statuses_filter', $product_question_statuses_filter);


	foreach ($all_storefronts as $k => $v){
		$storefronts_filter[$v["storefrontid"]]["name"] = $v["domain"];

                if (in_array($v["storefrontid"], $filter['storefront_ids'])) {
                    $storefronts_filter[$v["storefrontid"]]['selected'] = 'Y';
                }
	}
	$smarty->assign('storefronts_filter', $storefronts_filter);

	foreach ($fraud_statuses as $k => $v){
		$fraud_statuses_filter[$k]["name"] = $v;

	        if (in_array($k, $filter['fraud_statuses'])) {
	            $fraud_statuses_filter[$k]['selected'] = 'Y';
	        }
	}
	$smarty->assign('fraud_statuses_filter', $fraud_statuses_filter);


//func_print_r($filter['attention_tags_values']);

        foreach ($attention_tags_values as $k => $v){
                $attention_tags_values_filter[$k]["name"] = $v;

                if (is_array($filter['attention_tags_values']) && in_array($k, $filter['attention_tags_values'])) {
                    $attention_tags_values_filter[$k]['selected'] = 'Y';
                }
        }
        $smarty->assign('attention_tags_values_filter', $attention_tags_values_filter);


	$ship_to_countries_filter = $countries;
        foreach ($ship_to_countries_filter as $k => $v){
                if (in_array($v["country_code"], $filter['ship_to_countries'])) {
                    $ship_to_countries_filter[$k]['selected'] = 'Y';
                }
        }
        $smarty->assign('ship_to_countries_filter', $ship_to_countries_filter);
###
##
#

//func_print_r($filter);
     
    $smarty->assign('filter', $filter);
    $smarty->assign('distributors', $distributors);
    $smarty->assign('statuses', $statuses);

    global $xcart_dir;
    require_once $xcart_dir . "/include/class/classPOPipeline.php";
    $oPOPipelineStatuses = classPOPipeLine::getPOStatuses();
    $smarty->assign('po_statuses', $oPOPipelineStatuses);

    $smarty->assign('filter_preset_title', 'title');
} else {
    $_filters = func_query("SELECT * FROM $sql_tbl[filter_presets] ORDER BY preset_position, fid");

    foreach ($_filters as $k => $filter) {
     if (empty($filter["preset_position"])){

        $row = ceil($filter['fid'] / FILTER_PRESET_PER_ROW);
        $column = intval($filter['fid'] - ($row - 1) * FILTER_PRESET_PER_ROW);
        
        $preset_position = $row.",".$column;

        $used_preset_position_fid = func_query_first_cell("SELECT fid FROM $sql_tbl[filter_presets] WHERE preset_position='$preset_position' AND fid!='$filter[fid]'");
   
        if (!empty($used_preset_position_fid)){

                $preset_position = "";
                $found_empty_preset_position = false;

	        $count_filter_presets = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[filter_presets]");
        	$count_rows_in_filter_presets = ceil($count_filter_presets/FILTER_PRESET_PER_ROW);

                for ($i=FILTER_PRESET_PER_ROW; $i>=1; $i--){
                        for ($j=$count_rows_in_filter_presets; $j>=1; $j--){
                                $tmp_preset_position = $j.",".$i;
                                $is_such_preset_position = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[filter_presets] WHERE preset_position='$tmp_preset_position'");
                                if (empty($is_such_preset_position) || $is_such_preset_position == 0){
                                        $preset_position = $tmp_preset_position;
                                        $found_empty_preset_position = true;
                                        $row = $j;
                                        $column = $i;
                                        break;
                                }
                        }

                        if ($found_empty_preset_position){
                                break;
                        }
                }
        }

        db_query("UPDATE $sql_tbl[filter_presets] SET preset_position='$preset_position' WHERE fid='$filter[fid]'");

	$_filters[$k]["preset_position"] = $preset_position;
     }

     if (!empty($_filters[$k]["preset_position"])){
        $preset_position_arr = explode(",", $_filters[$k]["preset_position"]);
        $_filters[$k]["row"] = $preset_position_arr[0];
        $_filters[$k]["column"] = $preset_position_arr[1];
     }
    }

    $filters = array();
    foreach ($_filters as $k => $v) {
	$filters[$v["row"]][$v["column"]] = $v;
    }

    ksort($filters);

    foreach ($filters as $k => $v) {
	ksort($v);
	$filters[$k] = $v;
    }

//func_print_r($_filters, $filters);
//func_print_r($filters);
//die();

/*
    $count = 0;
    $filters = array();
    foreach ($_filters as $k => $v) {
        if ($count % FILTER_PRESET_PER_ROW == 0) {
            $filters[] = array();
        }
        $filters[count($filters) - 1][] = $_filters[$k];
        $count++;
    }
*/

    $smarty->assign('filters', $filters);
}


//func_print_r($all_storefronts);

?>
