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
 * X-PDF initialization
 *
 * @category   X-Cart
 * @package    X-PDF
 * @subpackage Core
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2011 Ruslan R. Fazlyev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: init.php 188 2011-06-04 16:36:19Z max $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */

if (!defined('XCART_START')) { header("Location: index.php"); die("Access denied"); }

xpdf_init();

if (x_check_controller_condition('C', 'cart', 'order_message')) {

    // Customer's Invoice page
	x_tpl_add_regexp_patch(
		'modules/XPDF/invoice_link.tpl',
	    '/(<table [^>]*class="[^"]*SimpleButton[^"]*"[^>]*>.+mode=invoice.+<\/table>)/USs',
    	'\1%%'
	);

} elseif (x_check_controller_condition('C', 'order')) {

    // Customer's order page
    x_tpl_add_regexp_patch(
        'modules/XPDF/invoice_link.tpl',
        '/(<td [^>]*class="[^"]*ButtonsRow[^"]*"[^>]*>.*)(<table [^>]*class="SimpleButton"[^>]*>.+<\/table>)/USs',
        '\1%%</td><td class="ButtonsRow">\2'
    );

} elseif (x_check_controller_condition('A', 'orders')) {

    // Admin's orders page
    x_tpl_add_regexp_patch(
        'modules/XPDF/orders_button.tpl',
        '/(<input [^>]+\'invoice\'\);[^>]+>[^>]*)</USs',
        '\1%%<'
    );

} elseif (x_check_controller_condition('C', 'orders')) {

    // Customers's orders page
    x_tpl_add_regexp_patch(
        'modules/XPDF/customer_orders_button.tpl',
        '/(<button .+\'invoice\'\);.+<\/button>)/USs',
        '\1%%'
    );

} elseif (x_check_controller_condition('A', 'order')) {

    // Admin's order page
    x_tpl_add_regexp_patch(
        'modules/XPDF/invoice_link_admin.tpl',
        '/(<table [^>]*class="[^"]*ButtonsRow[^"]*"[^>]*>.+)(<td [^>]*class="ButtonsRow"[^>]*>.+<\/td>)/USs',
        '\1<td class="ButtonsRow">%%</td>\2'
    );
}

