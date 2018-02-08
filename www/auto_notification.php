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
# $Id: auto_notification.php,v 1 2008/28/02 08:28:02 zrr Exp $
#Send e-mail to customers and admin about customer who sign in but haven't order.
#

require "./auth.php";

x_load('cart','category','crypt','mail','user');

$after_time = time() - 3600*($config["Email_Note"]["eml_send_auto_after_time"]);

$_customers = func_query_column("SELECT c.login FROM $sql_tbl[customers] as c left join $sql_tbl[orders] as o on o.login = c.login WHERE c.first_login < '$after_time' AND c.last_message !='Y' AND c.usertype = 'C' AND o.orderid is null GROUP BY c.login ORDER BY c.last_login DESC");

if (!empty($_customers)) {
	foreach ($_customers as $customer) {
		$user_data = func_userinfo($customer, 'C');
		$mail_smarty->assign("userinfo",$user_data);
		func_send_mail($user_data["email"], "mail/re_signin_admin_notif_subj.tpl", "mail/re_signin_admin_notification.tpl", $config["Email_Note"]["eml_admin_email_auto_time"], false, false, true);
	        func_send_mail($config["Company"]["users_department"], "mail/re_signin_admin_notif_subj.tpl", "mail/re_signin_admin_notification.tpl", $config["Email_Note"]["eml_admin_email_auto_time"], false, false, true);
            db_query("UPDATE $sql_tbl[customers] SET last_message = 'Y' WHERE login = '".$user_data['login']."'");
	}
}

exit;
?>
