<?php
require "./auth.php";

x_load("order", "backoffice", "files", "image", "product", "crypt");

x_session_register("rma_request_form_data");

if ($REQUEST_METHOD == "POST") {

	if ($mode == "retrieve_my_order"){

		$orderid = trim($orderid);
		$zipcode = trim($zipcode);
		$email = trim($email);

		$rma_request_form_data["orderid"] = $orderid;
		$rma_request_form_data["zipcode"] = $zipcode;
		$rma_request_form_data["email"] = $email;

		$form_with_error = false;

		if (empty($orderid) || (empty($zipcode) && empty($email))){
			$form_with_error = true;
		}
		else {

	                if (strpos($orderid,"-") !== false){
        	                $orderid_arr = explode("-", $orderid);
                	        $orderid = trim($orderid_arr["1"]);
	                }

			$is_such_order = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE orderid='$orderid' AND (s_zipcode='$zipcode' OR email='$email')");

			if (empty($is_such_order)){
				$form_with_error = true;
			}
		}

		if ($form_with_error){

			$rma_request_form_data["top_message_content"] = "error1";
			x_session_save("rma_request_form_data");

			func_header_location("rma_request.php");
		}


		$create_new_rma_id = true;

		$rma_ids_for_orderid = func_query("SELECT rma_id FROM $sql_tbl[rmas] WHERE orderid='$orderid'");
		if (!empty($rma_ids_for_orderid)){
			foreach ($rma_ids_for_orderid as $k => $v){
				$is_empty_rma_details_for_existed_rma_id = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[rma_details] WHERE rma_id='$v[rma_id]'");
				if (empty($is_empty_rma_details_for_existed_rma_id)){
					$rma_id = $v["rma_id"];
					$create_new_rma_id = false;
					break;
				}
			}
		}


		if ($create_new_rma_id){

			$rma_number = func_query_first_cell("SELECT MAX(rma_number) FROM $sql_tbl[rmas] WHERE orderid='$orderid'") + 1;

	                db_query("INSERT INTO $sql_tbl[rmas] (orderid, zipcode, email, date, status, rma_number) VALUES ('$orderid', '$zipcode', '$email', '".time()."', '1', '$rma_number')");
			$rma_id = db_insert_id();
		}
		else {
			db_query("UPDATE $sql_tbl[rmas] SET zipcode='$zipcode', email='$email' WHERE rma_id='$rma_id'");
		}

		$o = text_crypt($orderid);
		func_header_location("rma_request.php?step=2&o=$o&rma_id=$rma_id");
	}
	elseif ($mode == "to_rma_department" && !empty($rma_id) && !empty($o)){

		$orderid = text_decrypt($o);

                $rma_info = func_get_rma_info($rma_id);

                if ($rma_info["orderid"] != $orderid){
                        $rma_request_form_data["top_message_content"] = "error4";
                        x_session_save("rma_request_form_data");
                        func_header_location("rma_request.php");
                }

		db_query("UPDATE $sql_tbl[rmas] SET explanation='$post_rma[explanation]', status='2' WHERE rma_id='$rma_id'");

		$rma_amounts = 0;
		$rma_items = "";

		db_query("DELETE FROM $sql_tbl[rma_details] WHERE rma_id='$rma_id'");

		if (!empty($post_rma["products"]) && is_array($post_rma["products"])){

			$rma_items .= '<table width="600px" border="1" cellpadding="5" cellspacing="0" bordercolor="#414236" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #000000; line-height: 18px;">';
			$rma_items .= '<tr><td width="150px" style="text-align: left; font-weight: bold;">Item number</td><td width="250px" style="text-align: left; font-weight: bold;">Item name</td><td style="text-align: right; font-weight: bold;" nowrap="nowrap">Return qty</td><td style="text-align: right; font-weight: bold;" nowrap="nowrap">I would like to</td></tr>';


	                $order_data = func_order_data($orderid);

			$rma_would_like_variants = func_query("SELECT * FROM $sql_tbl[rma_would_like_variants]");

			foreach ($post_rma["products"] as $itemid => $v){

				$productcode = "";
				$product = "";
				foreach ($order_data["order"]["shipping_groups"] as $m_id => $group){
					if (!empty($group["products"][$itemid]["productcode"])){

						$productcode = $group["products"][$itemid]["productcode"];
						$product = $group["products"][$itemid]["product"];

                                                $selected_product_options = "";
                                                if (!empty($group["products"][$itemid]["product_options"]) && is_array($group["products"][$itemid]["product_options"])){
 	                                               foreach ($group["products"][$itemid]["product_options"] as $kk => $vv){
        	                                               $selected_product_options .= "<br />".$vv["classtext"]." ".$vv["option_name"];
                                                       }
                                                }

						$would_like = "";
						foreach ($rma_would_like_variants as $kk => $vv){
							if ($vv["code"] == $v["would_like"]){
								$would_like = $vv["name"];
								break;
							}
						}

						$rma_items .= '<tr><td width="150px" style="text-align: left;">'.$group["products"][$itemid]["mpn"].'</td><td width="250px" style="text-align: left;"><a href="'.$group["products"][$itemid]["links"]["customer"].'">'.$product.'</a>'.$selected_product_options.'</td><td style="text-align: right;">'.$v["amount"].'</td><td style="text-align: right;" nowrap="nowrap">'.$would_like.'</td></tr>';

						break;
					}
				}

				$query_data = array(
					"rma_id" => $rma_id,
					"productid" => $v["productid"],
					"itemid" => $itemid,
					"productcode" => addslashes($productcode),
					"product" => addslashes($product),
					"amount" => $v["amount"],
					"would_like" => $v["would_like"]
				);

				func_array2insert("rma_details", $query_data);

				$rma_amounts += $v["amount"];
			}

			$rma_items .= "</table>";
		}

#
##
###
                if (!empty($_FILES) && is_array($_FILES)){

                        $tmp_counter = 0;

                        foreach ($_FILES as $k => $v){
                                if (func_is_image_userfile($v["tmp_name"], $v["size"], $v["type"])){

                                        $data[$tmp_counter] = $v;

                                        $data[$tmp_counter]['ORIG_name'] = $v["name"];
                                        $data[$tmp_counter]['filename'] = $_FILES[$k]["name"] = $rma_id."_".time()."_".$v["name"];
                                        $data[$tmp_counter]["file_path"] = func_move_uploaded_file($k);

                                        $data[$tmp_counter]["source"] = "U";
                                        $data[$tmp_counter]["id"] = $rma_id;
					$data[$tmp_counter]["image_type"] = $v["type"];
                                        $data[$tmp_counter]["type"] = "R";
                                        $data[$tmp_counter]["date"] = time();

                                        $_file_upload_data = array("R" => $data[$tmp_counter]);

                                        $image_perms = func_check_image_storage_perms($_file_upload_data, "R");
                                        $image_posted = func_check_image_posted($_file_upload_data, "R");
                                        if ($image_posted) {
                                                $orderby = func_query_first_cell('SELECT MAX(orderby) FROM '. $sql_tbl['images_R']
                                                    . ' WHERE id="' . $rma_id . '"') + 10;

                                                $image_id = func_save_image($_file_upload_data, "R", $rma_id, array("alt" => "RMA", "orderby" => $orderby));

                                                # normalize name
                                                db_query("UPDATE $sql_tbl[images_R] SET filename='".$data[$tmp_counter]['ORIG_name']."' WHERE imageid='$image_id'");
                                                #

                                                @unlink($data[$tmp_counter]["file_path"]);
                                        }


                                        $tmp_counter++;
                                }
                        }
                }
###
##
#


		if (empty($rma_amounts)){
                        $rma_request_form_data["top_message_content"] = "error3";
                        x_session_save("rma_request_form_data");

			func_header_location("rma_request.php?step=2&o=$o&rma_id=$rma_id");
		}


                $signature = func_get_signature($order_data["order"]["storefrontid"]);
                $cur_storefront_info = func_get_storefront_info($order_data["order"]["storefrontid"]);
                $crypt_orderid = text_crypt($orderid);
                $rma_form_link = "<a href='http://".$cur_storefront_info["domain"]."/rma_request.php?step=2&o=$crypt_orderid&rma_id=$rma_id&prefilled=Y' target='_blank' style='color: blue;'>link</a>";

		$body = $config["RMA_options"]["RMA_to_department_Message"];
                $body = str_replace("{{c-fullname}}", $order_data["userinfo"]["firstname"], $body);
                $body = str_replace("{{orderid}}", $order_data["order"]["order_prefix"].$orderid, $body);
                $body = str_replace("{{userfirstname}}", $userfirstname, $body);
                $body = str_replace("{{signature}}", $signature, $body);
                $body = str_replace("{{rma_form_link}}", $rma_form_link, $body);
                $body = str_replace("{{rma_items}}", $rma_items, $body);

                $mail_smarty->assign("body", $body);

                $subj = $config["RMA_options"]["RMA_to_department_Subject"];
                $subj = str_replace("{{c-fullname}}", $order_data["userinfo"]["firstname"], $subj);
                $subj = str_replace("{{orderid}}", $order_data["order"]["order_prefix"].$orderid, $subj);
                $mail_smarty->assign("subj", $subj);

		func_send_mail($config["RMA_options"]["RMA_to_department_email"], "mail/simple_email_subj.tpl", "mail/simple_email_body.tpl", $order_data['userinfo']["email"], false, false, false, false, "", "RMA_id_".$rma_id, false, false);


		db_query("UPDATE $sql_tbl[rmas] SET status='3' WHERE rma_id='$rma_id'");

                if (!empty($config["RMA_options"]["RMA_Attention_tag"])){

	                $is_such_tag_in_db = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["RMA_options"]["RMA_Attention_tag"]."'");
                        if (empty($is_such_tag_in_db)){
	                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (status_id, orderid) VALUES ('".$config["RMA_options"]["RMA_Attention_tag"]."','$orderid')");

                                $tag_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='".$config["RMA_options"]["RMA_Attention_tag"]."'");
                                $log = "<br />'".$tag_name."' attention tag added";
				func_log_order($orderid, 'X', $log, $login);
                        }

                }

		func_header_location("rma_request.php?step=3&o=$o&rma_id=$rma_id");
	}
}


if (!isset($step)){
	$step = 1;
}

if (
	!($step == "1" || $step == "2" || $step == "3" || $step == "4") ||
	((empty($o) || empty($rma_id)) && ($step == "2" || $step == "3" || $step == "4"))
){
        func_header_location("rma_request.php");
}

if (!empty($o) && !empty($rma_id)){

	$orderid = text_decrypt($o);

	$is_such_order = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE orderid='$orderid'");
	if (empty($is_such_order)){
		$rma_request_form_data["top_message_content"] = "error2";
		x_session_save("rma_request_form_data");
		func_header_location("rma_request.php");
	}

        $rma_info = func_get_rma_info($rma_id);
//func_print_r($rma_info);

        if ($rma_info["orderid"] != $orderid){
		$rma_request_form_data["top_message_content"] = "error4";
                x_session_save("rma_request_form_data");
                func_header_location("rma_request.php");
	}


        $smarty->assign("rma_info", $rma_info);

        $order_data = func_order_data($orderid);
        $order = $order_data["order"];
        $smarty->assign("order", $order);


	if ($step == "2"){

		if (!in_array($rma_info["status"], array("1","2","4"))){
			func_header_location("rma_request.php");
		}

		$smarty->assign("o", $o);
		$smarty->assign("rma_id", $rma_id);

		$rma_would_like_variants = func_query("SELECT * FROM $sql_tbl[rma_would_like_variants] ORDER BY orderby, name");
		$smarty->assign("rma_would_like_variants", $rma_would_like_variants);

		if (empty($rma_info["products"]) && empty($rma_info["explanation"])){
			$smarty->assign("empty_form", "Y");
		}
	}
	elseif ($step == "3"){

		if ($rma_info["status"] != "3"){
			func_header_location("rma_request.php?step=2&o=$o&rma_id=$rma_id");
		}
	}

}


$smarty->assign("step", $step);

$smarty->assign("rma_request_form_data", $rma_request_form_data);
$rma_request_form_data = "";
x_session_save("rma_request_form_data");

$smarty->assign("main","rma_request");

$location[] = array("Product return/replacement request", "");
# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
