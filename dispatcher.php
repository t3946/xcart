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
 * Clean URLs dispatcher
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Customer interface
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2011 Ruslan R. Fazlyev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: dispatcher.php,v 1.24.2.1 2011/01/10 13:11:42 ferz Exp $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */

define('DISPATCHED_REQUEST', 1);

//die("123-dispatcher.php");

require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'auth.php';

$request_uri_info = @parse_url(stripslashes(func_get_request_uri()));


#
##
###
if (!empty($request_uri_info["query"]) && $request_uri_info['path'] == '/dispatcher.php' && strpos($request_uri_info["query"], "request_uri=/")!== false){
    $request_uri_info['path'] = str_replace('request_uri=/', '/', $request_uri_info["query"]);
}
###
##
#

//print"_SERVER variable returns:";
//func_print_r($_SERVER, $request_uri_info);
//die();

if (
    !isset($request_uri_info['path'])
    || zerolen($request_uri_info['path'])
    ) {

    func_page_not_found();
}





$dispatched_request = preg_replace('/^' . preg_quote($xcart_web_dir . DIR_CUSTOMER . '/', '/') . '/', '', $request_uri_info['path']);


if (strpos($dispatched_request, "keyword/") !== false) {
    $smarty->assign('search_keyword', true);

    if (strpos($dispatched_request, "&") !== false) {
        $dispatched_request_arr = explode("&", $dispatched_request);
        $dispatched_request = $dispatched_request_arr[0];
    }
}

//if ($config['SEO']['canonical'] == 'Y') {
    $smarty->assign('canonical_url', $dispatched_request);
//}


#
##
###
$rest = substr($dispatched_request, -1);
if ($rest != "/"){
    $tmp_new_redirect_url = $http_location . "/".$dispatched_request."/";
    func_header_location($tmp_new_redirect_url, true, 301);
}
###
##
#

#
##
###
//if (!$detect->isMobile()){
if (!$detect_isMobile_was_created){
  if (strpos($QUERY_STRING, "page=") !== false){
    $tmp_new_redirect_url = $http_location . "/".$dispatched_request;
    func_header_location($tmp_new_redirect_url, true, 301);
  }
}
###
##
#

//func_print_r($dispatched_request, $request_uri_info);

$dispatched_request = $ext_dispatched_request = rtrim($dispatched_request, '/');
$dispatched_request = preg_replace("/\.html$/i", '', $dispatched_request);

if (zerolen($dispatched_request)) {

    func_page_not_found();
}

if ($dispatched_request == 'clean-url-test') {

    die('Clean URLs system test completed successfully.');
}

//func_print_r($dispatched_request);

// Perform lookup in clean urls table.

    $cidev_dispatched_request_arr = explode("/", $dispatched_request);
    if (!empty($cidev_dispatched_request_arr) && is_array($cidev_dispatched_request_arr)){

        if ($cidev_dispatched_request_arr[0] == "product"){
            $cidev_clean_url_type = "P";
        } elseif ($cidev_dispatched_request_arr[0] == "category" || $cidev_dispatched_request_arr[0] == "information" || $cidev_dispatched_request_arr[0] == "article"){
            $cidev_clean_url_type = "C";

            if (strpos($dispatched_request, "/brand/") !== false){
                $cat_with_one_brand_filter = "Y";

                $smarty->assign("cat_with_one_brand_filter", $cat_with_one_brand_filter);

                $cat_URL_for_cat_with_one_brand_filter_arr = explode("/brand/", $dispatched_request);
//                $cat_URL_for_cat_with_one_brand_filter = $cat_URL_for_cat_with_one_brand_filter_arr[0];
                $cidev_orig_dispatched_request = $dispatched_request;
                $smarty->assign("cidev_orig_dispatched_request", $cidev_orig_dispatched_request);

                $dispatched_request = $cat_URL_for_cat_with_one_brand_filter_arr[0];
                $smarty->assign("new_dispatched_request", $dispatched_request);

###
                $smarty->assign('canonical_url', $dispatched_request."/");
###

                $brandid_in_url_arr = explode("/", $cat_URL_for_cat_with_one_brand_filter_arr[1]);
                $brandid_in_url = $brandid_in_url_arr[0];
                $brand_name_in_url = $brandid_in_url_arr[1];
                $brandid_and_name_in_url = "brand/".$brandid_in_url."/".$brand_name_in_url;

                $tmp_cidev_brand_clean_url_value = func_query_first_cell("SELECT clean_url FROM  $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$brandid_in_url'");

                if ($brandid_and_name_in_url != $tmp_cidev_brand_clean_url_value){
                    $tmp_new_redirect_url = $xcart_web_dir . "/".$dispatched_request."/".$tmp_cidev_brand_clean_url_value."/";

                    func_header_location($tmp_new_redirect_url, true, 301);
                }
            }

        } elseif ($cidev_dispatched_request_arr[0] == "page"){
            $cidev_clean_url_type = "S";
        } elseif ($cidev_dispatched_request_arr[0] == "brand"){
            $cidev_clean_url_type = "M";
        }

        $cidev_clean_url_id = $cidev_dispatched_request_arr[1];

        if (!empty($cidev_clean_url_type) && !empty($cidev_clean_url_id)){
            $cidev_clean_url_value = func_query_first_cell("SELECT clean_url FROM  $sql_tbl[clean_urls] WHERE resource_type='".addslashes($cidev_clean_url_type)."' AND resource_id='".addslashes($cidev_clean_url_id)."'");
            if (!empty($cidev_clean_url_value) && $cidev_clean_url_value != $dispatched_request){
                $queryParam = $_GET;
                unset($queryParam['request_uri']);
                $sHttpQuery = http_build_query($queryParam);
                func_header_location($xcart_web_dir . "/".$cidev_clean_url_value."/".(empty($sHttpQuery) ? '' : '?'.$sHttpQuery), true, 301);
            }
        }
    }

$clean_url_data = func_clean_url_lookup_resource($dispatched_request);

###
if ($cidev_dispatched_request_arr[0] == "keyword"){
    $clean_url_data['resource_type'] = "K";

    $new_keyword = urldecode($cidev_dispatched_request_arr[1]);
    $new_keyword = preg_replace("/[^0-9a-zA-Z\-]/S", "-", $new_keyword);
    $new_keyword = preg_replace('#(?<!:)-{2,}#', '-', $new_keyword); //remove repeatable '-'
    $new_keyword = trim($new_keyword);
    $new_keyword = strtolower($new_keyword);

    if ($new_keyword != $cidev_dispatched_request_arr[1]){
        $tmp_new_redirect_url = $xcart_web_dir . "/".$cidev_dispatched_request_arr[0]."/".$new_keyword."/";
        func_header_location($tmp_new_redirect_url, true, 301);
    }

//func_print_r($cidev_dispatched_request_arr, $new_keyword);
} else 
###
if (
    empty($clean_url_data)
    || !is_array($clean_url_data)
    || !isset($clean_url_data['resource_type'])
    || !isset($clean_url_data['resource_id'])
    ) {

    // We got no matches in clean urls table. Let's check if the URL exists in URLs history.
    $history_url_data = func_clean_url_history_lookup_resource($dispatched_request);

    if (
        !empty($history_url_data)
        && is_array($history_url_data)
        && isset($history_url_data['resource_type'])
        && isset($history_url_data['resource_id'])
    ) {

        $redirect_url = func_get_resource_url($history_url_data['resource_type'], $history_url_data['resource_id']);

        if ($redirect_url) {
            func_header_location($redirect_url, true, 301);
        }
    }


    func_header_location("/", true, 301);

    func_page_not_found();
}
switch ($config['SEO']['clean_urls_ext_'.strtolower($clean_url_data['resource_type'])]) {
    case '.html':
        $redirect_to_canonical_url = !preg_match("/\.html$/Ssi", $ext_dispatched_request);
        break;
    case '/':
        $redirect_to_canonical_url = preg_match("/\.html$/Ssi", $ext_dispatched_request);
        break;
    default:
        $redirect_to_canonical_url = false;
}

// Perform permanent redirect to the corresponding dynamic page 
// if Clean URLs functionality is disabled
// - or -
// perform permanent redirect to the canonical URL if the path is incorrect.
if ($config['SEO']['clean_urls_enabled'] != 'Y' || $redirect_to_canonical_url) {

    $redirect_url = func_get_resource_url($clean_url_data['resource_type'], $clean_url_data['resource_id'], $QUERY_STRING);

    if ($redirect_url) {

        func_header_location($redirect_url, true, 301);
    }

    func_page_not_found();
}


#
##
###
$smarty->assign('clean_url_data', $clean_url_data);
###
##
#

switch ($clean_url_data['resource_type']) {

case 'C':
    // Category page case
    $_GET['cat'] = $cat = intval($clean_url_data['resource_id']);
    $QUERY_STRING = 'cat=' . $cat . (!empty($QUERY_STRING) ? '&' . $QUERY_STRING : '');
    $PHP_SELF = dirname($PHP_SELF).'/home.php';
 
    require $xcart_dir.DIR_CUSTOMER.'/home.php';
    break;

case 'K':
    // Keyword page case
    $PHP_SELF = dirname($PHP_SELF).'/home.php';

    require $xcart_dir.DIR_CUSTOMER.'/home.php';
    break;

case 'P':
    // Product page case

    $_GET['productid'] = $productid = intval($clean_url_data['resource_id']);
    $QUERY_STRING = 'productid=' . $productid . (!empty($QUERY_STRING) ? '&' . $QUERY_STRING : '');
    $PHP_SELF = dirname($PHP_SELF) . '/product.php';

    

    require $xcart_dir.DIR_CUSTOMER.'/product.php';

    break;

case 'M':
    // brand page case
    $_GET['brandid'] = $brandid = intval($clean_url_data['resource_id']);
    $QUERY_STRING = 'brandid=' . $brandid . (!empty($QUERY_STRING) ? '&' . $QUERY_STRING : '');
    $PHP_SELF = dirname($PHP_SELF) . '/brands.php';

    include $xcart_dir.DIR_CUSTOMER.'/brands.php';
    break;

case 'S':
    // Static page case
    $_GET['pageid'] = $pageid = intval($clean_url_data['resource_id']);
    $QUERY_STRING = 'pageid=' . $pageid . (!empty($QUERY_STRING) ? '&' . $QUERY_STRING : '');
    $PHP_SELF = dirname($PHP_SELF) . '/pages.php';

    require $xcart_dir.DIR_CUSTOMER.'/pages.php';
    break;

default:

    func_page_not_found();
}

?>
