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
# $Id: retrieve_orders.php,v 1.0 2010/10/08 12:44:59 kate Exp $
#

if ( !defined('XCART_SESSION_START') ) { header('Location: ../'); die('Access denied'); }

x_load('mail','order');

$location[] = array(func_get_langvar_by_name('lbl_retrieve_orders'), 'retrieve_orders.php');

if ($REQUEST_METHOD == 'POST' && $mode == 'retrieve_orders') {

	$email = mysql_real_escape_string($email);

	if (func_check_email($email) && !empty($email)) {

		$orderids = func_query_column('SELECT orderid FROM ' . $sql_tbl['orders'] . ' WHERE email="' . $email . '"', 'orderid');

		$orders = array();

		if (is_array($orderids)) {
			foreach ($orderids as $orderid) {
				$orders[$orderid] = func_order_data($orderid);
				$orders[$orderid]['shipping_groups'] = func_get_shipping_groups($orderid);
			}
		}

		$tracking_links = func_query_hash("SELECT * FROM $sql_tbl[tracking_links]", 'linkid', false);
		$mail_smarty->assign("tracking_links", $tracking_links);
		$mail_smarty->assign('orders', $orders);
		$mail_smarty->assign('email', $email);
		func_send_mail($email, 'mail/retrieved_orders_subj.tpl', 'mail/retrieved_orders_mail.tpl', $config['Company']['orders_department'], false);
		func_send_mail($config['Company']['support_department'], 'mail/retrieved_orders_subj.tpl', 'mail/retrieved_orders_mail.tpl', $config['Company']['orders_department'], false);
		
		if (!empty($orders)) {
			$top_message['content'] = func_get_langvar_by_name('lbl_email_sent', null, false, true);
			$top_message['type'] = 'I';
		} else {
			$top_message['content'] = func_get_langvar_by_name('txt_no_orders_for_email', null, false, true);
			$top_message['type'] = 'W';
		}

		func_header_location($xcart_web_dir . '/retrieve_orders.php');

	} else {

		func_header_location($xcart_web_dir . '/retrieve_orders.php?section=retrieve_order_error&email=' . urlencode($email));
	}
}

$smarty->assign('main','retrieve_orders');

?>
