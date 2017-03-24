<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (��������� ��� ������ UPS + ��������� � ������ ����� Tracking numbers ��� �������)] */ ?>
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
# $Id: process_order.php,v 1.11.2.3 2006/11/16 13:44:59 twice Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

x_load('order', 'order_edit');

x_session_register("orders_to_delete");

if ($REQUEST_METHOD == "POST") {
#
# Process POST request
#

	if ($mode == "tracking_data") {
		include $xcart_dir."/include/orders_tracking.php";
	}

	if (!empty($export_fmt)) {
		$search_data["orders"]["export_fmt"] = $export_fmt;
		x_session_save("search_data");
	}

# START: random:18591_18598 [2009 Jul 29 10:36] 
	if ($mode == "update" && @$user_account["flag"] != "FS") {
# END: random:18591_18598 [2009 Jul 29 10:36] 
	#
	# Update orders info (status)
	#
		$flag = 0;
		define('ORDERS_LIST_UPDATE', 1);

		if (is_array($order_status) && is_array($order_status_old)) {
			foreach($order_status as $orderid=>$status) {
				if (is_numeric($orderid) && $status != $order_status_old[$orderid]) {
					func_change_order_status($orderid, $status);
					$flag = 1;
				}
			}
	        }
		if ($flag)
			$top_message["content"] = func_get_langvar_by_name("msg_adm_orders_upd");
		func_header_location("orders.php?mode=search");

	} # /if ($mode == "update")

	elseif ($mode == "delete" || $mode == "delete_all") {
	#
	# Delete the selected orders
	#
		if ($confirmed == "Y") {
		# Deleting is confirmed
			if ($mode == "delete_all") {
				include $xcart_dir."/include/orders_deleteall.php";
				$top_message["content"] = func_get_langvar_by_name("msg_adm_all_orders_del");
				func_header_location("orders.php?mode=search");
			}

			if (is_array($orders_to_delete)) {
				foreach ($orders_to_delete as $k=>$v) {
					# Delete order
					func_delete_order($k);
				}
				$orders_to_delete = "";

				#
				# Prepare the information message
				#
				$top_message["content"] = func_get_langvar_by_name("msg_adm_orders_del");
				func_header_location("orders.php?mode=search");
			}
		}
		else {
			$orders_to_delete = (!empty($orderids) ? $orderids : "");
			func_header_location("process_order.php?mode=$mode");
		}

	} # /if ($mode == "delete")

	elseif ($mode == "invoice" and !empty($orderids)) {
	#
	# Display invoices
	#
		$orders_to_delete = (!empty($orderids) ? $orderids : "");
		func_header_location("process_order.php?mode=invoice");
	}
        elseif ($mode == 'xpdf_invoice' and !empty($orderids)) {

        // Display XPDF invoices

    		$orders_to_delete = (!empty($orderids) ? $orderids : '');
	        func_header_location("process_order.php?mode=xpdf_invoice");
        }
	elseif ($mode == "label" and !empty($orderids)) {
	#
	# Display labels
	#
		$orders_to_delete = (!empty($orderids) ? $orderids : "");
		func_header_location("process_order.php?mode=label");

	}

	# Export selected order(s)
	elseif ($mode == "export" and !empty($orderids)) {
		include $xcart_dir."/include/orders_export.php";
	} elseif ($mode == "accounting_apply" && $user_account["flag"] != "FS") {

		if (!empty($groups) && is_array($groups)) {
			$upd_er = false;
			foreach ($groups as $orderid => $group_order) {
				$applied_per_trans_payments = array();
				if (is_array($group_order)) {
					foreach ($group_order as $m_id => $v) {
						$shipping_groups = func_get_shipping_groups($orderid);
						$shipping_groups[$m_id]['acc_paymentid'] = $v['paymentid'];
						$shipping_groups[$m_id]['manufacturerid'] = $m_id;
	
			                        if (is_array($shipping_groups[$m_id]['accounting']) && !empty($shipping_groups[$m_id]['accounting'])) {
			                            $acc_zero_data = array(
                        			        ACC_COST_TO_US  => true,
			                                ACC_REF_TO_CUST => true,
                        			        ACC_REF_TO_US   => true,
			                            );
                        			    $acc_new_data = array(
			                                ACC_COST_TO_US  => false,
                        			        ACC_REF_TO_CUST => false,
			                                ACC_REF_TO_US   => false,
                        			    );
			                            foreach ($shipping_groups[$m_id]['accounting'] as $col => $sga) {
                        			        foreach ($sga as $pdn => $pdv) {
			                                    if (
                        			                in_array($col, array_keys($acc_zero_data)) 
			                                        && !in_array($pdn, array('filled', 'net'))
			                                    ) {
                        			                $pdv = intval($pdv);
                                        
			                                        if (!empty($pdv)) {
                        			                    $acc_zero_data[$col] = false;
			                                        }
                                        
                        			                if (isset($v['acc'][$col][$pdn])) {
			                                            $_pdv = intval($v['acc'][$col][$pdn]);
                        			                    if (!empty($_pdv)) {
                                                			$acc_new_data[$col] = true;
			                                            }
			                                        }
                        			            }
			                                }
                        			    }
			                        }
	
						if (
			                            in_array($shipping_groups[$m_id]['cb_status'], array('P','R','H')) 
                        			    || in_array($shipping_groups[$m_id]['dc_status'], array('C','S'))
			                        ) {
							for ($ak = 1; $ak <= 4; $ak++) {
                        				        if ($ak == ACC_REF_TO_CUST) {
				                                    $refund_group = func_query_first('SELECT total_net, total_gst, total_pst, total_gross'
                                				        . ' FROM ' . $sql_tbl['refund_groups']
				                                        . ' WHERE orderid = "' . $shipping_groups[$m_id]['orderid'] . '"'
                                				        . ' AND manufacturerid = "' . $m_id .'"');
				                                }

								$shipping_groups[$m_id]['accounting'][$ak] = array();
								
								foreach ($price_details_names as $af) {
                                    
				                                    if ($ak == ACC_REF_TO_CUST) {
                                				        if (!empty($refund_group)) {
				                                            $v['acc'][$ak][$af] = $refund_group['total_' . $af];
                                				        } else {
				                                            $v['acc'][$ak][$af] = 0;
				                                        }
				                                    }
									
									$shipping_groups[$m_id]['accounting'][$ak][$af] = $v['acc'][$ak][$af];
								}
							}
						}
	
						if ($apply_per_trans = !in_array($v['paymentid'], $applied_per_trans_payments)) {
							$applied_per_trans_payments[] = $v['paymentid'];
						}
	
						func_recalculate_accounting($shipping_groups[$m_id], $all_processors, $apply_per_trans, true);
			
						$update = array();
						$update['accounting'] = (serialize($shipping_groups[$m_id]['accounting']));
						$update['profit_margin'] = $shipping_groups[$m_id]['profit_margin'];
						$update['acc_paymentid'] = $v['paymentid'];
	
						func_array2update('order_groups', $update ,"orderid='$orderid' AND manufacturerid='$m_id'");

			                        // Change the order group status

			                        if (
                        			    $acc_zero_data[ACC_REF_TO_US]
			                            && $acc_new_data[ACC_REF_TO_US]
                        			) {
			                            func_change_order_group_status($orderid, $m_id, 'Z');
			                        } elseif ($acc_zero_data[ACC_COST_TO_US]
                        			    && $acc_new_data[ACC_COST_TO_US]
			                        ) {
                        			    func_change_order_group_status($orderid, $m_id, 'X');
			                        }
                        
			                        if (
                        			    $acc_zero_data[ACC_REF_TO_CUST]
			                            && $acc_new_data[ACC_REF_TO_CUST]
                        			) {
			                            func_change_order_group_status($orderid, $m_id, 'R');
			                        }

					}
				} else {
					$upd_er = true;
				}
			}
			if ($upd_er) {
				$top_message['content'] = func_get_langvar_by_name('msg_adm_orders_upd_err');
				$top_message['type'] = 'E';
			} else {
				$top_message['content'] = func_get_langvar_by_name('msg_adm_orders_upd');
				$top_message['type'] = 'I';
			}
			func_header_location('orders.php?mode=search');
		}
	}

	$orders_to_delete = "";
	$top_message["content"] = func_get_langvar_by_name("msg_adm_warn_orders_sel");
	$top_message["type"] = "W";

	func_header_location("orders.php?mode=search");

} # /if ($REQUEST_METHOD == "POST")

if ($mode == 'xpayments' && $orderid && $active_modules['XPayments_Connector']) {
	require_once ($xcart_dir . '/modules/XPayments_Connector/admin.php');
}

if ($mode == "invoice" || $mode == "label" || $mode == 'xpdf_invoice') {
#
# Display the printable version of order invoices
#
	if (is_array($orders_to_delete)) {
		$orderid = implode(",", array_keys($orders_to_delete));
		$orders_to_delete = "";
		x_session_save("orders_to_delete");
		include $xcart_dir."/include/history_order.php";
	}
}

if ($mode == "delete") {
#
# Prepare for deleting products
#
	if (is_array($orders_to_delete)) {

		$location[] = array(func_get_langvar_by_name("lbl_orders_management"), "search.php");
		$location[] = array(func_get_langvar_by_name("lbl_delete_orders"), "");
		$smarty->assign("location", $location);

		foreach ($orders_to_delete as $k=>$v) {
			$condition[] = "orderid='".addslashes($k)."'";
		}
		$search_condition = implode(" OR ", $condition);

		$orders = func_query("SELECT orderid, cb_status, date, total FROM $sql_tbl[orders] WHERE $search_condition ORDER BY orderid");

		if (is_array($orders)) {
			foreach ($orders as $k=>$v) {
				$orders[$k]["date"] += $config["Appearance"]["timezone_offset"];
				if (!$single_mode)
					$orders[$k]["provider"] = func_query_first_cell("SELECT provider FROM $sql_tbl[order_details] WHERE orderid='$v[orderid]'");
			}

			$smarty->assign("orders", $orders);

			$smarty->assign("main","order_delete_confirmation");

			#
			# Show admin template because only admin can delete orders
			#
			@include $xcart_dir."/modules/gold_display.php";
			func_display("admin/home.tpl",$smarty);
			exit;
		}

	}

}
elseif ($mode == "delete_all") {
#
# Prepare the confirmation page for deleting all orders
#
	$location[] = array(func_get_langvar_by_name("lbl_orders_management"), "search.php");
	$location[] = array(func_get_langvar_by_name("lbl_delete_orders"), "");
	$smarty->assign("location", $location);

	$orders_count = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders]");
	$smarty->assign("orders_count", $orders_count);

	$smarty->assign("mode","delete_all");
	$smarty->assign("main","order_delete_confirmation");

	#
	# Show admin template because only admin can delete orders
	#
	@include $xcart_dir."/modules/gold_display.php";
	func_display("admin/home.tpl",$smarty);
	exit;

}

$orders_to_delete = "";

$top_message["content"] = func_get_langvar_by_name("msg_adm_warn_orders_sel");
$top_message["type"] = "W";

func_header_location("orders.php?mode=search");

?>
