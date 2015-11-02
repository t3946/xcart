<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST'){

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";

	if ($mode == "update_inquiry_type" && !empty($post_data) && is_array($post_data)){

		foreach ($post_data as $inq_type_id => $v){

			if ($v["delete"] == "Y"){
				db_query("DELETE FROM $sql_tbl[inquiry_types] WHERE inq_type_id='$inq_type_id'");
			}
			elseif (!empty($v["inquiry_type"])) {
				db_query("UPDATE $sql_tbl[inquiry_types] SET inquiry_type='".$v["inquiry_type"]."', active='$v[active]' WHERE inq_type_id='$inq_type_id'");
			}
		}
	}

	if ($mode == "add_inquiry_type"){

		$count_add_inquiry_type = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[inquiry_types] WHERE inquiry_type='$add_inquiry_type'");

		if (!empty($add_inquiry_type) && empty($count_add_inquiry_type)){
			db_query("INSERT INTO $sql_tbl[inquiry_types] (inquiry_type, active) VALUES ('$add_inquiry_type', '$add_active')");
		}
		else {
		        $top_message["content"] = 'Not added.';
		        $top_message["type"] = "E";
		}
	}

        if ($mode == "update_inquiry_attn_tag" && !empty($post_data) && is_array($post_data)){

                foreach ($post_data as $inq_tag_id => $v){

                        if ($v["delete"] == "Y"){
                                db_query("DELETE FROM $sql_tbl[inquiries_attention_tags] WHERE inq_tag_id='$inq_tag_id'");
                        }
                        elseif (!empty($v["inquiry_attn_tag"])) {
                                db_query("UPDATE $sql_tbl[inquiries_attention_tags] SET inquiry_attn_tag='".$v["inquiry_attn_tag"]."', active='$v[active]' WHERE inq_tag_id='$inq_tag_id'");
                        }
                }
        }
        
        if ($mode == "add_inquiry_attn_tag"){
                
                $count_add_inquiry_attn_tag = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[inquiries_attention_tags] WHERE inquiry_attn_tag='$add_inquiry_attn_tag'");      
                
                if (!empty($add_inquiry_attn_tag) && empty($count_add_inquiry_attn_tag)){
                        db_query("INSERT INTO $sql_tbl[inquiries_attention_tags] (inquiry_attn_tag, active) VALUES ('$add_inquiry_attn_tag', '$add_active')");
                }
                else {
                        $top_message["content"] = 'Not added.';
                        $top_message["type"] = "E";
                }
        }

	func_header_location("configuration.php?option=Inquiries_options");
}

$inquiry_types = func_query("SELECT * FROM $sql_tbl[inquiry_types] ORDER BY inquiry_type");
$inquiry_attn_tags = func_query("SELECT * FROM $sql_tbl[inquiries_attention_tags] ORDER BY inquiry_attn_tag");

$smarty->assign("inquiry_types", $inquiry_types);
$smarty->assign("inquiry_attn_tags", $inquiry_attn_tags);
?>
