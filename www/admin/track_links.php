<?php

define('USE_TRUSTED_POST_VARIABLES', 1);
$trusted_post_variables = array('add', 'data');

require './auth.php';
require $xcart_dir . '/include/security.php';

$location[] = array(func_get_langvar_by_name('lbl_tracking_links'), '');

# Add title
if ($mode === 'add' && !empty($add['shipping'])) {
    if (empty($add['orderby'])) {
        $add['orderby'] = func_query_first_cell("SELECT MAX(orderby)+1 FROM $sql_tbl[tracking_links]");
    }
    func_array2insert("tracking_links", $add);

# Update title(s)
} elseif ($mode === 'update' && !empty($data)) {

    foreach ($data as $carrier_id => $v) {

        $tracking_links_carrier_arr = array();
        $tracking_links_carrier_arr["carrier"] = $v["carrier"];
        $tracking_links_carrier_arr["link"] = $v["link"];
        $tracking_links_carrier_arr["orderby"] = $v["carrier_orderby"];
        $tracking_links_carrier_arr["phone"] = $v["phone"];
        func_array2update("tracking_links_carrier", $tracking_links_carrier_arr, "carrier_id = '$carrier_id'");

        if (!empty($v["orderby"]) && is_array($v["orderby"])) {
            foreach ($v["orderby"] as $linkid => $val) {
                $tracking_links_arr = array();
                $tracking_links_arr["orderby"] = $val;
                $tracking_links_arr["shipping"] = $v["shipping"][$linkid];
                func_array2update("tracking_links", $tracking_links_arr, "linkid = '$linkid'");
            }
        }
    }

# Delete title(s)
} elseif ($mode === 'delete' && (!empty($ids) || !empty($carrier_ids))) {

    if (!empty($carrier_ids)) {
        $string = "carrier_id IN ('" . implode("','", $carrier_ids) . "')";
        db_query("DELETE FROM $sql_tbl[tracking_links] WHERE " . $string);
        db_query("DELETE FROM $sql_tbl[tracking_links_carrier] WHERE " . $string);
    }

    if (!empty($ids)) {
        $string = "linkid IN ('" . implode("','", $ids) . "')";
        db_query("DELETE FROM $sql_tbl[tracking_links] WHERE " . $string);
    }

} elseif ($mode === 'add_carrier' && !empty($add_carrier['carrier'])) {

    if (empty($add_carrier['orderby'])) {
        $add_carrier['orderby'] = func_query_first_cell("SELECT MAX(orderby)+1 FROM $sql_tbl[tracking_links_carrier]");
    }

    func_array2insert("tracking_links_carrier", $add_carrier);

}

if (!empty($mode)) {
    func_header_location("track_links.php");
}

$links = func_query("SELECT * FROM $sql_tbl[tracking_links_carrier] ORDER BY orderby");

if (!empty($links)) {

    foreach ($links as $k => $v) {
        $links[$k]["shippings"] = func_query("SELECT * FROM $sql_tbl[tracking_links] WHERE carrier_id='$v[carrier_id]' ORDER BY orderby");
    }

    $smarty->assign("links", $links);
}

#
# Assign Smarty variables and show template
#
$smarty->assign("main", "tracking_links");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
