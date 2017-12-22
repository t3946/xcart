<?php
/* vim: set ts=4 sw=4 sts=4 et: */
/*****************************************************************************\
 +-----------------------------------------------------------------------------+
 | X-Cart                                                                      |
 | Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>                  |
 | All rights reserved.                                                        |
 * -----------------------------------------------------------------------------+
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
 | Portions created by Ruslan R. Fazlyev are Copyright (C) 2001-2010           |
 | Ruslan R. Fazlyev. All Rights Reserved.                                     |
 * -----------------------------------------------------------------------------+
\**************************************************************************** */

/**
 * Module initialization
 *
 * @copyright   Copyright (c) 2001-2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license     http://www.x-cart.com/software_license_agreement.html X-Cart license agreement
 * @author      Slam <slam@x-cart.com>
 * @category    X-Cart
 * @package     Modules
 * @subpackage  Sitemap
 * @version     $Id$
 * @since       4.4.0
 */

if (!defined('XCART_START')) { header('Location: ../../'); die('Access denied'); }

if (defined('AREA_TYPE') && in_array(constant('AREA_TYPE'), array('A', 'P'))) {
	// Process changes on the module options page
	if (isset($_GET['option']) && $_GET['option'] == 'Sitemap') {

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sitemap'])) {
			if (!isset($_POST['sitemap'][$_POST['sitemap']['config']])) {
				$sitemap_error = func_get_langvar_by_name('lbl_permission_denied');
			} else {
				switch ($_POST['sitemap']['config']) {
					case 'add':
						$sitemap_error = sitemap_extra_addurl($_POST['sitemap']['add']);
						break;

					case 'delete':
						$sitemap_error = sitemap_extra_delurls($_POST['sitemap']['delete']);
						break;

					case 'update':
						$sitemap_error = sitemap_extra_updateurls($_POST['sitemap']['update']);
						break;

					default:
						break;
				}
			}

			// Store error or success message in session
			x_session_register('top_message');
			if (!empty($sitemap_error)) {
				$top_message['content'] = $sitemap_error;
				$top_message['type'] = 'E';
			} else {
				$top_message['content'] = func_get_langvar_by_name('lbl_done');
				$top_message['type'] = 'I';
			}
			func_header_location($_SERVER['REQUEST_URI']);
		} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$smarty->assign('sitemap_extra', sitemap_extra_geturls());
			$smarty->assign('additional_config', 'modules/Sitemap/config.tpl');
		}
	}
}
?>