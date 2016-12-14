<?php
/* vim: set ts=4 sw=4 sts=4 et: */
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2011 Ruslan R. Fazlyev <rrf@x-cart.com>                  |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLYEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
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
| The Initial Developer of the Original Code is Ruslan R. Fazlyev             |
| Portions created by Ruslan R. Fazlyev are Copyright (C) 2001-2011           |
| Ruslan R. Fazlyev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

/**
 * X-PDF functions
 *
 * @category   X-Cart
 * @package    X-PDF
 * @subpackage Core
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2011 Ruslan R. Fazlyev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: func.php 360 2012-06-14 18:30:25Z max $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */

if (!defined('XCART_START')) { header("Location: index.php"); die("Access denied"); }

function xpdf_init()
{
    global $xpdf_lib;

    $func = 'xpdf_' . $xpdf_lib . '_init';

    if (function_exists($func)) {
        $func();
    }
}

function xpdf_convert($content)
{
    global $xpdf_lib;

    $content = preg_replace('/<script[^>]*>.*<\/script>/iSsU', '', $content);
    $content = preg_replace('/<meta .+\/>/iSsU', '', $content);

    set_time_limit(86400);

    $func = 'xpdf_' . $xpdf_lib . '_convert';

    return $func($content);
}

function xpdf_convert_tpl($tpl, &$smarty, $html_prepare_callback = null)
{
    global $xcart_http_host, $config;

    $http_host = 'http://' . $xcart_http_host;
    if ($config["Appearance"]["Enable_CDN"] == "Y" && !empty($config["Appearance"]["CDN_domain"])) {
        $http_host = '';
    }

    $images_dir = $smarty->_tpl_vars['ImagesDir'];
    $smarty->assign('ImagesDir', $http_host . $images_dir);

    $skin_dir = $smarty->_tpl_vars['SkinDir'];
    $smarty->assign('SkinDir', $http_host . $skin_dir);

    $smarty->assign('pdf_template', $tpl);

    global $already_included_files;
    $already_included_files = array();

    $html = func_display('modules/XPDF/pdf.tpl', $smarty, false);
    $html = str_replace('type="text/css" href="/', 'type="text/css" href="http://' . $xcart_http_host . '/', $html);

    if ($html_prepare_callback) {
        $html = call_user_func($html_prepare_callback, $html, $tpl);
    }

    $smarty->assign('ImagesDir', $images_dir);
    $smarty->assign('SkinDir', $skin_dir);

    return xpdf_convert($html);
}

function xpdf_get_invoices($orders)
{
    global $current_area, $login;

    foreach ($orders as $i => $orderid) {

        $order_data = func_order_data($orderid);

        if (empty($order_data)) {
            unset($orders[$i]);
            continue;
        }

        // Security check if order owned by another customer

        if (
            $current_area == 'C'
            && $order_data['userinfo']['login'] != $login
        ) {
            unset($orders[$i]);
            continue;
        }
    }

    if ($orders && xpdf_get_invoice($orders)) {
        exit (0);
    }

    return false;
}

function xpdf_get_invoice($orderids, $output = true, $internal = false)
{
    x_load('order');

    global $smarty, $shop_language, $current_area, $login, $statuses;
    global $sql_tbl;

    if (!is_array($orderids)) {
        $orderids = array($orderids);
    }

    $orders = array();
    foreach ($orderids as $orderid) {
        if (!$orderid) {
            continue;
        }

        $order = func_order_data($orderid);
        if (!$order) {
            continue;
        }

        if (!$internal) {
            // Check customer access
            if (
                $current_area == 'C'
                && $order['userinfo']['login'] != $login
            ) {
                func_403(10001);
                return false;
            }

            // Check provider access
            if (
                $current_area == 'P'
                && !$single_mode
                && $order['order']['provider'] != $login
            ) {
                func_403(10002);
                return false;
            }

            // Check common access
            if (!in_array($current_area, array('C', 'P', 'A'))) {
                func_403(10003);
                return false;
            }
        }

        $order['products'] = func_translate_products($order['products'], $shop_language);

        $orders[] = $order;
    }

    if (!$orders) {
        return false;
    }

    $smarty->assign('orders', $orders);
    $smarty->assign('statuses', $statuses);

#
##
###
    $tracking_links = func_query_hash("SELECT * FROM $sql_tbl[tracking_links] ORDER BY orderby", 'linkid', false);
    $smarty->assign("tracking_links", $tracking_links);
###
##
#

    $pdf = xpdf_convert_tpl('modules/XPDF/invoice.tpl', $smarty, 'xpdf_invoice_preprocess');

    if ($pdf && $output) {
        header('Content-Type: application/pdf');
        header('Content-Length: ' . strlen($pdf));
        header('Content-Disposition: attachment; filename="invoice-' . implode('-', $orderids) . '.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Description: File Transfer');
        header('Last-Modified: ' . date('D, d M Y H:i:s T', time()));
        print $pdf;

        $pdf = true;
    }

    return $pdf;
}

function xpdf_invoice_preprocess($html)
{
    if (preg_match('/<table [^>]*class="invoice-totals[^>]+>.+<\/table>/USs', $html, $match)) {
        $replace = preg_replace('/<td ([^>]*)class="invoice-line" colspan="2"([^>]*>.+<\/td>)/USs', '<td \1class="invoice-line invoice-line-1"\2<td \1class="invoice-line invoice-line-2"\2', $match[0]);
        $html = str_replace($match[0], $replace, $html);
    }

    $html = str_replace('cellspacing="1"', '', $html);
    $html = str_replace('border="1"', 'border="0" class="xpdf-border"', $html);
    $html = str_replace('<table>', '<table border="0" class="xpdf-no-border">', $html);
    $html = str_replace('<table cellpadding="0">', '<table border="0" cellpadding="0" class="xpdf-no-border">', $html);
    $html = preg_replace('/<span style="WHITE-SPACE: nowrap">(.+)<\/span>/SsU', '$1', $html);
    $html = preg_replace('/(?:\s*<br \/>)+\s*(<\/body>|<table)/Ss', '$1', $html);
    $html = str_replace('<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">', '<table cellspacing="0" cellpadding="0" class="order-invoice">', $html);
    $html = preg_replace('/(<table [^>]*class="order-invoice[ "].+<table [^>]*class="order-invoice(?: [^"]+)?)(")/SsU', '$1 order-invoice-next$2', $html);

    return $html;
}

function xpdf_is_need_invoice($tpl)
{
    global $config;

    if (
        $config['XPDF']['xpdf_email'] == "Y" && (
            preg_match('/order_customer\.tpl/Ss', $tpl)
        )
    ){
        return true;
    } 
    else 
        return false;


//    return 'Y' == $config['XPDF']['xpdf_email']
//        && preg_match('/order_customer\.tpl/Ss', $tpl);
}

function xpdf_get_mail_invoice($lend)
{
    global $mail_smarty;

    $orderid = $mail_smarty->_tpl_vars['order']['orderid'];

    $message = xpdf_get_invoice($orderid, false, true);

    $cell = false;

    if ($message) {
        $cell = array(
            'header' => array (
                'Content-Type'              => 'application/pdf;' . $lend . "\t" . 'name="invoice-' . $orderid . '.pdf"',
                'Content-Transfer-Encoding' => 'base64',
            ),
            'content' => func_mail_enc_base64($message),
        );
    }

    return $cell;
}

#
##
###
function xpdf_get_mail_po_instructions($lend)
{
    global $mail_smarty, $config, $smarty;

    $orderid = $mail_smarty->_tpl_vars['order']['orderid'];

    $po_instructions = $config['Purchase_Order']['po_instructions'];
    $po_instructions = str_replace("{{orderid}}", $orderid, $po_instructions);

    $smarty->assign('po_instructions', $po_instructions);

    $message = xpdf_convert_tpl('modules/XPDF/po_instructions.tpl', $smarty, 'xpdf_invoice_preprocess');

    $cell = false;

    if ($message) {
        $cell = array(
            'header' => array (
                'Content-Type'              => 'application/pdf;' . $lend . "\t" . 'name="Purchase-order-instructions.pdf"',
                'Content-Transfer-Encoding' => 'base64',
            ),
            'content' => func_mail_enc_base64($message),
        );
    }

    return $cell;
}
###
##
#

function xpdf_translate_resource_url($url)
{
    global $http_location, $https_location, $xcart_dir;

    $path = null;

    if (0 === strpos($url, $http_location)) {
        $path = $xcart_dir . str_replace('/', DIRECTORY_SEPARATOR, substr($url, strlen($http_location)));
        if (!file_exists($path)) {
            unset($path);
        }

    } elseif (0 === strpos($url, $https_location)) {
        $path = $xcart_dir . str_replace('/', DIRECTORY_SEPARATOR, substr($url, strlen($https_location)));
        if (!file_exists($path)) {
            unset($path);
        }
    }

    if (!isset($path)) {
        global $xpdf_resource_translation_patterns;

        if (is_array($xpdf_resource_translation_patterns)) {
            foreach ($xpdf_resource_translation_patterns as $pattern => $replace) {
                if (preg_match($pattern, $url)) {
                    $path = preg_replace($pattern, $replace, $url);
                    if (file_exists($path)) {
                        break;

                    } else {
                        unset($path);
                    }
                }
            }
        }
    }

    return isset($path) ? $path : $url;
}
