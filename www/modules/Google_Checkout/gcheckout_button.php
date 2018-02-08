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
# $Id: gcheckout_button.php,v 1.1.2.4 2007/01/08 09:03:54 svowl Exp $
#
# This script prepares the Google Checkout button
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

if (empty($cart['products']) && empty($cart['giftcerts'])) {
	$gcheckout_enabled = false;
	$smarty->assign('gcheckout_enabled', false);
	return;
}

$_gc_button_variant = (func_is_gcheckout_button_enabled() ? 'text' : 'disabled');

$_https = ($current_location == $https_location ? 'https' : 'http');

$gcheckout_button = <<<OUT
<img src="$_https://$gcheckout_env.google.com/{$gcheckout_sbx}buttons/checkout.gif?merchant_id={$config['Google_Checkout']['gcheckout_mid']}&amp;w=160&amp;h=43&amp;style=trans&amp;variant={$_gc_button_variant}&amp;loc=en_US" width="160" height="43" border="0" alt="" />
OUT;

if ($_gc_button_variant != 'disabled') {
	$gcheckout_button = <<<OUT
<a href="cart.php?mode=gcheckout">{$gcheckout_button}</a>
OUT;
}
elseif ($config['Google_Checkout']['gcheckout_display_product_note'] == 'Y') {
	$smarty->assign("gcheckout_display_product_note", true);
}

$smarty->assign('gcheckout_button', $gcheckout_button);

?>
