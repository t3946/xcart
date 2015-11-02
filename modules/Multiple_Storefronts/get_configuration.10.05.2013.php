<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2012 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2012           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: get_configuration.php,v 1.0.0 2012/02/02 12:02:24 stan Exp $
#

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

$configuration = (!empty($current_storefront))
	? func_query("SELECT * FROM $sql_tbl[storefronts_config] WHERE storefrontid = '$current_storefront' ORDER BY orderby")
	: func_get_default_config('F');

if (!empty($configuration)) {
	
	// Add image row
//	if (!empty($current_storefront)) {

		$configuration[] = array(
			'name'    => 'sf_top_image',
			'comment' => func_get_langvar_by_name('lbl_mf_top_banner_image'),
			'orderby' => '25',
			'type'    => 'text',
		);

//	}



	$display_order = array(
	  'company_name'     => 10,
	  'company_website'  => 20,
	  'cidev_top_header_code' => 33,
	  'cidev_header_code' => 34,
	  'cidev_tracking_code'  => 100,
          'cidev_main_page_code'  => 36,
          'cidev_footer_code'  => 37,
          'cidev_yandex_code_number'  => 38,
          'cidev_ga_code_number'  => 39,
	  'cidev_keywords'  => 21,
	  'cidev_description'  => 22,
	  'sf_top_image'     => 30,
	  'opt_order_prefix' => 40,
	  'search_products_unique_id' => 45,
	  'transfer_to_gcs_if_sku_search_null' => 47,
	  'newsletter_email' => 50,
	  'start_year'       => 60,
	  'brands_columns'   => 64,
	  'storefront_columns' => 67,
	  'show_seed_cats'   => 70,
	  'search_all_website_show' => 80,
	  'shop_closed'      => 90,
	);

	foreach ($configuration as $k => $v) {
		$configuration[$k]['orderby'] = (isset($display_order[$v['name']])) ? $display_order[$v['name']] : $v['orderby'];
	}

	uasort($configuration, 'func_msf_sort_config_array');
}

?>
