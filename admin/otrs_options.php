<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

if ($REQUEST_METHOD == 'POST' && $mode == "update") {

//	db_query("UPDATE $sql_tbl[otrs_options] SET OTRS_passphrase='$OTRS_passphrase', status_id='$status_id' WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
    db_query("UPDATE $sql_tbl[otrs_options] SET OTRS_passphrase='$OTRS_passphrase', status_id='$status_id'");

    krsort($cb_status);
    foreach ($cb_status as $iRuleId => $sStatus) {
        $aUpdateParams = ['cb_status' => $cb_status[$iRuleId],
            'dc_status' => $dc_status[$iRuleId],
            'bd_status' => $bd_status[$iRuleId],
            'action' => $action[$iRuleId],
            'rule_id' => $iRuleId,
        ];
        func_array2insert("cidev_otrs_new_message_rules", $aUpdateParams, true);
    }

    if (!empty($rules_to_delete)){
        $rules_to_delete = array_keys($rules_to_delete);
        db_query("DELETE FROM $sql_tbl[cidev_otrs_new_message_rules] WHERE rule_id IN (".implode(',',$rules_to_delete).")");
    }


    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";
    func_header_location("configuration.php?option=OTRS_options");
}

//$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options]");

if (empty($otrs_options)) {
//	db_query("INSERT INTO $sql_tbl[otrs_options] (storefrontid) VALUES ('".$current_storefront_info["storefrontid"]."')");
    db_query("INSERT INTO $sql_tbl[otrs_options] (OTRS_passphrase) VALUES ('passphrase')");
//	$otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
    $otrs_options = func_query_first("SELECT * FROM $sql_tbl[otrs_options]");
}

$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] WHERE active='Y' ORDER BY orderby, status");

$order_statuses = func_query("SELECT * FROM $sql_tbl[order_statuses] WHERE type IN ('CB', 'DC', 'BD') ORDER BY orderby");

$otrs_new_message_rules = func_query("SELECT * FROM $sql_tbl[cidev_otrs_new_message_rules]");

$smarty->assign("attention_tags_values", $attention_tags_values);
$smarty->assign("otrs_options", $otrs_options);
$smarty->assign("order_statuses", $order_statuses);
$smarty->assign("otrs_new_message_rules", $otrs_new_message_rules);
?>
