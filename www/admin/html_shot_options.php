<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

if ($REQUEST_METHOD == 'POST' && $mode == "update") {

    $sAttentionFlags = '';
    if (!empty($attention_tags_after_remove) && is_array($attention_tags_after_remove)) {
        $sAttentionFlags = implode(',',$attention_tags_after_remove);
    }

    db_query("UPDATE $sql_tbl[config] SET value='$remove_shot_after_days' WHERE name='remove_shot_after_days'");
    db_query("UPDATE $sql_tbl[config] SET value='$days_past_attn_tag_set' WHERE name='days_past_attn_tag_set'");
    db_query("UPDATE $sql_tbl[config] SET value='$sAttentionFlags' WHERE name='attention_tags_after_remove'");
    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";
    func_header_location("configuration.php?option=HTML_shots_options");
}


$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] WHERE active='Y' ORDER BY orderby, status");
$attention_tags = func_query_first("SELECT * FROM $sql_tbl[config] WHERE name='attention_tags_after_remove'");
if (!empty($attention_tags)){
    $attention_tags_selected = explode(',',$attention_tags['value']);
}

$smarty->assign("attention_tags_selected", $attention_tags_selected);
$smarty->assign("attention_tags_values", $attention_tags_values);
?>
