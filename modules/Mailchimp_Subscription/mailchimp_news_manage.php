<?php
/* vim: set ts=4 sw=4 sts=4 et: */
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>                  |
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
| Portions created by Ruslan R. Fazlyev are Copyright (C) 2001-2010           |
| Ruslan R. Fazlyev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

/**
 * Manage news related data
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Modules
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: mailchimp_news_manage.php 4 2010-11-02 20:57:01Z kuzma $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */

if (!defined('XCART_START')) { header("Location: ../../"); die("Access denied"); }

x_load('mail');
x_session_register('validated_emails', array());
x_session_register('stored_newsemail');

//exit;
$subscribe_lng = $store_language;
if (empty($mode)) {
	$mode = $REQUEST_METHOD == "GET" ? "archive" : "view";
}
global $current_storefront;

//print_r($REQUEST_METHOD); exit;
if ($REQUEST_METHOD == "POST") {
	$email = trim($newsemail);
	if (!func_check_email($email)) {
		func_header_location("error_message.php?subscribe_bad_email");
	}
        $lists = func_query("SELECT * FROM $sql_tbl[mailchimp_newslists] WHERE avail = 'Y' and storefrontid=$current_storefront");
//print_r($current_storefront);
//print_r($lists); exit;
	if (!is_array($lists) || empty($lists)) {
		$top_message["type"] = "I";
		$top_message["content"] = func_get_langvar_by_name("lbl_no_subscr_news");
		func_header_location("home.php");
	}
	
	$news_lists_num = count($lists);
/*
	if ($mode == 'view' && !empty($active_modules['Image_Verification']) && func_validate_image("on_news_panel", $antibot_input_str)) {
		$top_message = array(
			"type" => "E",
			"content" => func_get_langvar_by_name("msg_err_antibot")
		);
		func_header_location(empty($HTTP_REFERER) ? "home.php" : $HTTP_REFERER);
	}
*/
	if ($news_lists_num > 1 && $mode == 'view') {
		$validated_emails[] = stripslashes($email);
		func_header_location("mailchimp_news.php?mode=list&email=".urlencode(stripslashes($email)));
	}

	if ($mode == 'view' || ($mode == "subscribe" && in_array(stripslashes($email), $validated_emails))) {
            if ($news_lists_num == 1) {
     	        $s_lists=array();
                $s_lists[] = $lists[0]['mc_list_id'];
           } elseif (empty($s_lists) || !is_array($s_lists)) {
		func_header_location("home.php");
	   }
           if (!empty($logged_userid)) {
               $userinfo = func_userinfo($logged_userid, $login_type, false, true, array('C','H'));
               $mailchimp_user_info = array ('FName'=> $userinfo['firstname'],
                                             'LName'=> $userinfo['lastname'],
                                      );
           }else{
               $mailchimp_user_info = array ('FName'=> 'Anonymous',
                                             'LName'=> 'Anonymous',
                                      );
           }

           foreach ($s_lists as $key =>$listid) {
              $mailchimp_response=func_mailchimp_subscribe($email, $mailchimp_user_info, $listid); 
           }
//           print_r($mailchimp_response);exit;
           $mail_smarty->assign("email", stripslashes($email));
		func_header_location("home.php?mode=subscribed&email=".urlencode(stripslashes($email)));
           }
  }

if ($REQUEST_METHOD == "GET" && $mode == "list") {
	if (empty($email) || !in_array(stripslashes($email), $validated_emails) || !func_check_email($email))
		func_header_location("home.php");

	$lists = func_query("SELECT * FROM $sql_tbl[mailchimp_newslists] WHERE avail='Y'");
	if (!is_array($lists) || empty($lists)) {
		$top_message["type"] = "I";
		$top_message["content"] = func_get_langvar_by_name("lbl_no_subscr_news");
		func_header_location("home.php");
	}

	$location[] = array(func_get_langvar_by_name("lbl_news_subscribe_to_newslists"), "");
    $smarty->assign('main', 'mc_news_lists');
    $smarty->assign('mc_lists', $lists);
    $smarty->assign('newsemail', $email);

} else {
	#
	# Show the news from archive
	#
	$location[] = array(func_get_langvar_by_name("lbl_news_archive"), "");

	$smarty->assign("main", "mc_news_archive");

	$smarty->assign("news_messages", func_news_get($shop_language));
}

$smarty->assign("location", $location);

?>
