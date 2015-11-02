<?php /* ADDED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2009 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2009           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# brands.php, random
#

require "./auth.php";

$brandid = abs(intval($brandid));

//print"<a href='123.php'>123</a>";

#
##
###
if ($mode == "notify" && !empty($productid) && !empty($notify_email) && !empty($brandid)){
        $is_in_table = func_query_first_cell("SELECT COUNT(sent) FROM $sql_tbl[notify_when_in_stock] WHERE email='$notify_email' AND sent='N' AND productid='$productid'");

        if (empty($is_in_table)){

                $notify_when_in_stock[$productid] = "Y";
                x_session_save('notify_when_in_stock');

                db_query("INSERT INTO $sql_tbl[notify_when_in_stock] (productid, email, date) VALUES ('$productid', '$notify_email', '".time()."')");
		$top_message["content"] = 'Thank you! You will be notified when the product is in stock.';
                $top_message["type"] = "I";
        } else {
                $top_message["content"] = 'You already signed up for this notification.';
                $top_message["type"] = "E";
        }

        $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$brandid'");

        if (!empty($page)){
                $clean_url_link .= "&page=".$page;
        }

        func_header_location($clean_url_link);
}
###
##
#


if (
    isset($brandid)
    && !empty($brandid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined("DISPATCHED_REQUEST")
) {
    func_clean_url_permanent_redirect('M', $brandid);
}



if($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands_list.php";
else
	func_header_location("home.php");

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
