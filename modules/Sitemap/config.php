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
 * Module configuration
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

if (!defined('XCART_START')) { header('Location: ../../'); die('Access denied');}

// Db table added by the module
$sql_tbl['sitemap_extra'] = 'xcart_sitemap_extra';

if (defined('Sitemap_page')) {
    $css_files['Sitemap'][] = array();
    // Avail items
    $config['Sitemap']['items'] = array('categories', 'products', 'manufacturers', 'brands', 'pages', 'extra');
}
/*
if (($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mode']) && $_POST['mode'] == 'catalog_gen') || ( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_POST['mode']) && $_POST['mode'] == 'continue')) {
    $additional_hc_data[] = array(
        'generation_script' => $xcart_dir . '/modules/Sitemap/html_catalog.php',
        'page_url' => 'sitemap.php',
        'name_func' => 'sitemap',
        'name_func_params' => array('Sitemap.html'),
        'src_func' => 'sitemap_process_page'
    );
}
*/
?>
