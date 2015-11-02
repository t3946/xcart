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
# $Id: send_order_email.php,v 1.0 2010/11/22 15:00:00 kate Exp $
#

require "./auth.php";
require $xcart_dir."/include/security.php";


if ($user_account['flag'] != 'FS') {
	$order_info = func_order_data($orderid);
	$order_info['shipping_groups'] = func_get_shipping_groups($orderid);
	$tracking_links = func_query_hash("SELECT * FROM $sql_tbl[tracking_links]", 'linkid', false);

	$mail_smarty->assign('products',$order_info['products']);
	$mail_smarty->assign('giftcerts',$order_info['giftcerts']);
	$mail_smarty->assign('order',$order_info['order']);
	$mail_smarty->assign('customer',$order_info['userinfo']);
	$mail_smarty->assign('tracking_links',$tracking_links);
	$mail_smarty->assign('statuses', $statuses);
	func_send_mail($order_info['userinfo']['email'], 'mail/order_notification_subj.tpl', 'mail/order_notification_admin.tpl', $config['Company']['orders_department'], false);
	$mail_smarty->assign('show_order_details', 'Y');
	func_send_mail($config['Company']['orders_department'], 'mail/order_notification_subj.tpl', 'mail/order_notification_admin.tpl', $order_info['userinfo']['email'], true, true);
	$mail_smarty->assign('show_order_details', '');
}
