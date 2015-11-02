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
# $Id: pages.php,v 1.6 2006/01/30 07:15:50 max Exp $
#
# This script show static page in customer zone

require "./auth.php";

if (
    isset($pageid)
    && !empty($pageid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
    && !func_is_ajax_request()
) {
    func_clean_url_permanent_redirect('S', intval($pageid));
}

require $xcart_dir."/include/categories.php";

x_load('files');

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
else
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Manufacturers"])
    include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";

if (!empty($active_modules["Xcart_Mobile"]) && $config["Appearance"]["Enable_Mobile_skin"] == "Y"){
	if ($detect->isMobile()){
		$pages_dir = $smarty->template_dir[1]."/pages/$store_language/";
	} else {
		$pages_dir = $smarty->template_dir."/pages/$store_language/";
	}
}
else {
	$pages_dir = $smarty->template_dir."/pages/$store_language/";
}

if (isset($_GET['pageid'])) {
#
# Prepare data for editing
#
    $preview = ($mode=="preview" ? "" : "AND active='Y'");
    $page_data = func_query_first("SELECT * FROM $sql_tbl[pages] WHERE pageid='$pageid' $preview AND level='E'");

	if ($page_data["language"] != $store_language) {
		$page_data = func_query_first("SELECT * FROM $sql_tbl[pages] WHERE filename='$page_data[filename]' $preview AND level='E' AND language='$store_language'");
	}

    if ($page_data) {
        $filename = $pages_dir.$page_data["filename"];
		$page_content = func_file_get($filename, true);
        if ($page_content === false) {
            $page_content = func_get_langvar_by_name("lbl_page_not_found", array(), false, true);
        }


        if ($pageid == "39"){
            $disclaimer_text_arr = func_query("Select B.disclaimer_text
From xcart_brands B
            inner join xcart_products P ON P.brandid = B.brandid and P.forsale = 'Y'
            inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = '$current_storefront'
Where Trim(B.disclaimer_text) != '' 
Group By B.brandid");

	    $disclaimer_text = "";
            if (!empty($disclaimer_text_arr)){
                $count_disclaimer_text_arr = count($disclaimer_text_arr);
                $disclaimer_text = "<table cellpadding='0' cellspacing='0'>";
                foreach ($disclaimer_text_arr as $kd => $vd){
                        $disclaimer_text .= "<tr><td>".$vd["disclaimer_text"]."</td></tr>";
                        if ($kd < ($count_disclaimer_text_arr - 1)){
                                $disclaimer_text .= "<tr><td>&nbsp;</td></tr>";
                        }
                }
                $disclaimer_text .= "</table>";
            }
	    $page_content = str_replace("{{brand_disclaimer}}", $disclaimer_text, $page_content);
        }


        $smarty->assign("page_data", $page_data);
        $smarty->assign("page_content", $page_content);

		$location[] = array($page_data["title"], "");
    }
    else {
        func_header_location("error_message.php?page_not_found");
    }


#
##
###
//    if ($pageid == "27"){

//func_print_r($config["Company"]["company_website"] );

	$linktous_text_sample = '<a href="'.$config["Company"]["company_website"]. '">'.$config["Company"]["company_name"].'</a> - '.$config["Company"]["cidev_keywords"];
	$smarty->assign("linktous_text_sample", $linktous_text_sample);

	$linktous_text_sample = stripslashes($linktous_text_sample);
//	$linktous_text_sample = str_replace("'", "&#039;", $linktous_text_sample);
//	$linktous_text_sample = htmlspecialchars($linktous_text_sample);
	$linktous_text_code = "<textarea onclick='this.select()' style='width: 80%;' rows='2'>".$linktous_text_sample."</textarea>";
	$smarty->assign("linktous_text_code", $linktous_text_code);

//	$linktous_banner_sample = '<a href="'.$config["Company"]["company_website"]. '" title="'.addslashes($config["Company"]["cidev_keywords"]).'"><img src="'.$config["Company"]["company_website"]. '/image.php?type=S&id='.$current_storefront.'" alt="'.$config["Company"]["company_name"].'" /></a>';
	$linktous_banner_sample = '<a href="#" title="'.addslashes($config["Company"]["cidev_keywords"]).'"><img src="/image.php?type=S&id='.$current_storefront.'" alt="'.$config["Company"]["company_name"].'" /></a>';
	$smarty->assign("linktous_banner_sample", $linktous_banner_sample);

//	$linktous_banner_sample = stripslashes($linktous_banner_sample);
	$linktous_banner_code = '<a href="'.$config["Company"]["company_website"]. '" title="'.addslashes($config["Company"]["cidev_keywords"]).'"><img src="'.$config["Company"]["company_website"]. '/image.php?type=S&id='.$current_storefront.'" alt="'.$config["Company"]["company_name"].'" /></a>';
	$linktous_banner_code = stripslashes($linktous_banner_code);

//	$linktous_banner_code = "<textarea onclick='this.select()' style='width: 80%;' rows='2'>".$linktous_banner_sample."</textarea>";
	$linktous_banner_code = "<textarea onclick='this.select()' style='width: 80%;' rows='2'>".$linktous_banner_code."</textarea>";
	$smarty->assign("linktous_banner_code", $linktous_banner_code);


//    }
###
##
#


    $smarty->assign("main", "pages");
}

#
##
###
if ($config["Appearance"]["Enable_surf_stats"] == "Y"){
        func_log_cidev_surf("T");
}
###
##
#

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
