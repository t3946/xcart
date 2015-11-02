<?php
define('USE_TRUSTED_POST_VARIABLES',1);
define('USE_TRUSTED_SCRIPT_VARS',1);
$trusted_post_variables = array("add_inq_subject");

require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Order dashboard", "orders.php?page_name=dashboard");
$location[] = array("Create new inquiry", "");

if ($REQUEST_METHOD == 'POST'){

        if ($mode == "add_inquiry"){

                $top_message["content"] = 'Done.';
                $top_message["type"] = "I";

                $add_inq_subject = trim($add_inq_subject);
                if(!empty($add_inq_subject) && !empty($add_inq_type_id)){
                        db_query("INSERT INTO $sql_tbl[inquiries] (inq_type_id, inq_subject, datetime, createdby_login) VALUES ('$add_inq_type_id', '$add_inq_subject', '".time()."', '$login')");

                        $inq_id = db_insert_id();
			$inq_id_edited = sprintf('%1$05d', $inq_id);
			$add_inq_email_subject = "INQ-".$inq_id_edited.": ".$add_inq_email_subject;
			
			db_query("UPDATE $sql_tbl[inquiries] SET inq_email_subject='$add_inq_email_subject' WHERE inq_id='$inq_id'");

			if (!empty($add_inq_tag_id) && is_array($add_inq_tag_id)){
				foreach ($add_inq_tag_id as $inq_tag_id => $v){
					if ($v == "Y"){
						db_query("INSERT INTO $sql_tbl[inquirires_tags] (inq_id,inq_tag_id) VALUES ('$inq_id', '$inq_tag_id')");
					}
				}
			}

//                        $inquiry_type = func_query_first_cell("SELECT inquiry_type FROM $sql_tbl[inquiry_types] WHERE inq_type_id='$add_inq_type_id'");
//                        $subject = "INQ-".$inq_id_edited.": ".$inquiry_type." by ".$userfirstname;
			$subject = $add_inq_email_subject;
                        $body = $add_inq_subject;

                        $to = "inquiries_internal@s3stores.com";
//$to = "xcartmaster@gmail.com";
                        $from = "xcart@s3stores.com";

			$headers = array (
                		"Content-Type" => "text/html"
		        );
                        func_send_simple_mail($to, $subject, $body, $from, $headers);
                } else {
                        $top_message["content"] = 'Not added.';
                        $top_message["type"] = "E";
                }

                func_header_location("create_new_inquiry.php");
        }
}

$inquiry_attn_tags = func_query("SELECT $sql_tbl[inquiries_attention_tags].*, COUNT($sql_tbl[inquiries].inq_type_id) as count FROM $sql_tbl[inquiries_attention_tags] LEFT JOIN $sql_tbl[inquirires_tags] ON $sql_tbl[inquirires_tags].inq_tag_id=$sql_tbl[inquiries_attention_tags].inq_tag_id LEFT JOIN $sql_tbl[inquiries] ON $sql_tbl[inquiries].inq_id=$sql_tbl[inquirires_tags].inq_id GROUP BY $sql_tbl[inquiries_attention_tags].inq_tag_id ORDER BY inquiry_attn_tag");

$inquiry_types = func_query("SELECT * FROM $sql_tbl[inquiry_types] WHERE active='Y' ORDER BY inquiry_type");

$smarty->assign("inquiry_types", $inquiry_types);
$smarty->assign("inquiry_attn_tags", $inquiry_attn_tags);
$smarty->assign("main", "create_new_inquiry");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
