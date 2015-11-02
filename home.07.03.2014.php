<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# $Id: home.php,v 1.10 2006/03/31 06:18:48 max Exp $
#

define('OFFERS_DONT_SHOW_NEW',1);
require "./auth.php";

$cat = isset($cat) ? abs(intval($cat)) : 0;

if (
    $cat > 0
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
) {
    func_clean_url_permanent_redirect('C', intval($cat));
}

require $xcart_dir."/include/categories.php";

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
else
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Manufacturers"])
    include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";

if (!empty($cat))
	include "./products.php";
if (empty($products) && empty($keyphrase)) {
        if (!empty($cat)){
                include "./featured_products.php";
        } else {
                include "./new_featured_products.php";
        }
}

if (!empty($keyphrase)) {
    include $xcart_dir . '/include/search_categories.php';
}

if ($active_modules["Bestsellers"])
	include $xcart_dir."/modules/Bestsellers/bestsellers.php";


if (!empty($current_category) and is_array($current_category["category_location"])) {
	foreach ($current_category["category_location"] as $k => $v) {
//		$v[1] .= '&path='.$k;
		$location[] = $v;
	}
}

if (!empty($current_category) && is_array($location)) {
    $current_category['meta_keywords'] = '';
	foreach ($location as $l) {
		$current_category['meta_keywords'] = $l[0] . ', ' . $current_category['meta_keywords'];
	}
	$current_category['meta_keywords'] = trim(strip_tags(substr($current_category['meta_keywords'], 0, strlen($current_category['meta_keywords']) - 2)));
	$smarty->assign('current_category', $current_category);
}

if (!empty($active_modules["Special_Offers"])) {
	include $xcart_dir."/modules/Special_Offers/category_offers.php";
}

#
##
###
$tmp_count_location = count($location);
if (!empty($current_category) && is_array($location) && (empty($page) || $page == "1") ) {
        $counter_location = 0;
        foreach ($location as $k => $v) {
                $counter_location++;
                if ($counter_location == $tmp_count_location){
                        unset($location[$k][1]);
                }
        }
}
###
##
#

#
##
###
if ((empty($cat) || $cat=="0") && (empty($page) || $page == "1")){
        include './newproducts.php';
}
###
##
#

#
## Filter
###
if (empty($cat) || $cat=="0"){
	x_session_register("sorted_filter_values_id");
	x_session_register("filter_selected_brandids");
	x_session_register("filter_prices");
        $sorted_filter_values_id = "";
        $filter_selected_brandids = "";
        $filter_prices = "";
        x_session_save("filter_prices");
        x_session_save("filter_selected_brandids");
        x_session_save("sorted_filter_values_id");

        $filter_min_price_selected = "";
        $filter_max_price_selected = "";
        x_session_save("filter_min_price_selected");
        x_session_save("filter_max_price_selected");
}
###
##
#

#
# Assign Smarty variables and show template
#

$smarty->assign("main","catalog");

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
