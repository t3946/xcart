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
# $Id: config.php,v 1.0 2010/11/26 17:40:24 kate Exp $
#

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

# Main storefront: domain
define('MAIN_SF_DOMAIN', 'www.artistsupplysource.com');

# The maximum quantity of storefronts
define('MAX_STOREFRONTS', 26);
$smarty->assign('MAX_STOREFRONTS', MAX_STOREFRONTS);

$config['available_images']['S'] = 'U';

$sql_tbl['storefronts'] = 'xcart_storefronts';
$sql_tbl['storefronts_config'] = 'xcart_storefronts_config';
$sql_tbl['images_S'] = 'xcart_images_S';
$sql_tbl['products_sf'] = 'xcart_products_sf';
$sql_tbl['storefront_links'] = 'xcart_storefront_links';

x_session_register('domain_specific_config', array());
$domain_specific_config = array(
	'Company'	=> array(
		'company_name'		=> '10', 
		'company_website'	=> '20',
		'start_year'		=> '30',
	),
	'General'	=> array(
		'shop_closed'		=> '115', 
		'opt_order_prefix'	=> '72', 
	),
	'Search_products' => array(
		'search_products_unique_id' => '75',
	),
	'News_Management' => array(
		'newsletter_email' 	=> '105', 
	),
    'Appearance'    => array(
        'show_seed_cats'    => '90',
		'storefront_columns'	=> 40,
	),
	'Brands'	=> array(
		'brands_columns'	=> 35,
	),
);
$smarty->assign('domain_specific_config', $domain_specific_config);

?>
