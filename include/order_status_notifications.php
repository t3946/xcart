
<?php
/*****************************************************************************\
 * +-----------------------------------------------------------------------------+
 * | X-Cart                                                                      |
 * | Copyright (c) 2001-2011 Ruslan R. Fazliev <rrf@rrf.ru>                      |
 * | All rights reserved.                                                        |
 * +-----------------------------------------------------------------------------+
 * | PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
 * | FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
 * | AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
 * |                                                                             |
 * | THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
 * | THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
 * | FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
 * | AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
 * | PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
 * | CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
 * | COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
 * | (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
 * | LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
 * | AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
 * | OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
 * | AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
 * | THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
 * | THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
 * |                                                                             |
 * | The Initial Developer of the Original Code is Ruslan R. Fazliev             |
 * | Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2011           |
 * | Ruslan R. Fazliev. All Rights Reserved.                                     |
 * +-----------------------------------------------------------------------------+
 * \*****************************************************************************/

#
# $Id: order_status_notifications.php, v 1.0.0 2011/10/18 12:50:09 kate Exp $
#

if (!defined('XCART_SESSION_START')) {
    header('Location: ../');
    die('Access denied');
}

x_session_register('selected_status');

if (isset($status)) {
    $status = func_query_first_cell_param('SELECT code FROM ' . $sql_tbl['order_statuses'] . ' WHERE code = :code', ['code' => $status]);
    if (!empty($status)) {
        $selected_status = $status;
    }
} else {

    if (empty($selected_status)) {
        $selected_status = 'I';
    }
}

if ($REQUEST_METHOD == 'POST') {
    if ($mode == 'update') {
        if (is_array($update)) {
            if (!empty($update['customer_subject'])) {

                foreach ($update['customer_subject'] as $idx => $value) {

                    $top_message['content'] = '';

                    if (empty($update['customer_subject'])) {
                        $top_message['content'] .= func_get_langvar_by_name('err_empty_customer_mail_subject');
                    }

                    if (empty($update['copy_subject'])) {
                        $top_message['content'] .= func_get_langvar_by_name('err_empty_copy_mail_subject');
                    }

                    if (empty($update['customer_subject']) || empty($update['copy_subject'])) {
                        $top_message['type'] == 'E';
                        func_header_location('order_status_notifications.php');
                    }

                    $query = array(
                        'code' => $selected_status,
                        'customer_subject' => ($update['customer_subject'][$idx]),
                        'copy_subject' => ($update['copy_subject'][$idx]),
                        'email_body' => $update['email_body'][$idx],
                        'enabled' => (isset($update['enabled'][$idx]) && $update['enabled'][$idx] == 'Y') ? 'Y' : 'N',
                        'customer_attach_pdf_invoice' => (isset($update['customer_attach_pdf_invoice'][$idx]) && $update['customer_attach_pdf_invoice'][$idx] == 'Y') ? 'Y' : 'N',
                        'admin_attach_pdf_invoice' => (isset($update['admin_attach_pdf_invoice'][$idx]) && $update['admin_attach_pdf_invoice'][$idx] == 'Y') ? 'Y' : 'N',
                    );

                    func_array2insert('order_status_notifications', $query, true);
                }
            }
        }
    }
    func_header_location('order_status_notifications.php');
}
global $xcart_dir;
$aOrderNotifications = Xcart\OrderStatusNotification::getOrderStatusNotificationsByCode($selected_status);

$smarty->assign('aOrderNotifications', $aOrderNotifications);

$status_types = array(
    'CB' => func_get_langvar_by_name('lbl_cust_bus_payment_status'),
    'DC' => func_get_langvar_by_name('lbl_distr_cust_shipping_status'),
    'BD' => func_get_langvar_by_name('lbl_bus_distr_payment_status'),
//    'CA'    => 'Currently assigned to',
    'RU' => 'REF TO US status',
    'PO' => 'Check transit status',
);

$smarty->assign('status_types', $status_types);

$smarty->assign('status', $selected_status);