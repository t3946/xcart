<?php
/*****************************************************************************\
 * +-----------------------------------------------------------------------------+
 * | X-Cart                                                                      |
 * | Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
 * | Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
 * | Ruslan R. Fazliev. All Rights Reserved.                                     |
 * +-----------------------------------------------------------------------------+
 * \*****************************************************************************/

#
# $Id: config.php,v 1.0 2010/11/26 17:40:24 kate Exp $
#

if (!defined('XCART_START')) {
    header('Location: ../../');
    die('Access denied');
}

# The maximum quantity of storefronts
define('MAX_STOREFRONTS', 26);
$smarty->assign('MAX_STOREFRONTS', MAX_STOREFRONTS);

$config['available_images']['S'] = 'U';

$sql_tbl['storefronts'] = 'xcart_storefronts';
$sql_tbl['storefronts_config'] = 'xcart_storefronts_config';
$sql_tbl['images_S'] = 'xcart_images_S';
$sql_tbl['products_sf'] = 'xcart_products_sf';
$sql_tbl['storefront_links'] = 'xcart_storefront_links';

$config['available_images']['F'] = 'U';
$sql_tbl['images_F'] = 'xcart_images_F';

x_session_register('domain_specific_config', array());
$domain_specific_config = array(
    'Company' => array(
        'company_name' => '10',
        'company_website' => '20',
        'start_year' => '30',
        'sf_top_image_alt' => '31',
        'cidev_top_header_code' => '33',
        'cidev_header_code' => '34',
        'search_products_unique_id_checkbox' => '46',
        'cidev_tracking_code' => '100',
        'cidev_main_page_code' => '36',
        'cidev_footer_code' => '37',
        'pop_up_in' => '38',
        'pop_up_code' => '38',
        'cidev_yandex_code_number' => '38',
        'cidev_ga_code_number' => '39',
        'cidev_google_adwords' => '41',
        'skip_generating_googlebase_feed' => '47',
        'cidev_keywords' => '21',
        'config_title_meta_tag' => '22',
        'cidev_description' => '23',
        'config_keywords_meta_tag' => '25',
        'html_into_head' => '26',
    ),
    'Storefront_common_details' => array(
        'common_header_code' => '48',
    ),
    'General' => array(
        'shop_closed' => '115',
        'opt_order_prefix' => '72',
    ),
    'Search_products' => array(
        'search_products_unique_id' => '75',
    ),
    'News_Management' => array(
        'newsletter_email' => '105',
    ),
    'Appearance' => array(
        'show_seed_cats' => '90',
        'Enable_CDN' => '210',
        'CDN_domain' => '215',

        'Enable_Mobile_skin' => '250',
        'Google_Trusted_Store_ID' => '280',
        'Enable_surf_stats' => '380',
        'Enable_desktop_notifications_on_product_page' => '660',
        'https_enabled' => '2000',

        'Preferred_served_country' => '480',
        'Preferred_language' => '580',

        'product_advantages_code' => '49',
        'storefront_columns' => 40,
    ),
    'Search_All' => array(
        'search_all_website_show' => '80',
        'transfer_to_gcs_if_sku_search_null' => '47',
    ),
    'Brands' => array(
        'brands_columns' => 35,
    ),
    'Shipping' => array(
        'new_shipping_calculation' => 990,
    ),
);
$smarty->assign('domain_specific_config', $domain_specific_config);
