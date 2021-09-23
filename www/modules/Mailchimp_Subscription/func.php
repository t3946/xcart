<?php
/* vim: set ts=4 sw=4 sts=4 et: */
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

/**
 * Functions for the Mailchimp subscription 
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Modules
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: func.php 20 2010-11-10 12:45:29Z kuzma $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }


/**
 * Subscription wrapper for Mailchimp service  (listSubscribe method)
 *
 * @param string $email_address E-mail
 * @param mixed  $listid        id of Mailchimp account
 * @param mixed  $apikey        apikey of Mailchimp account
 *
 * @return array
 * @see    ____func_see____
 * @since  1.0.0
 */
function func_mailchimp_get_lists($listid = false, $apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }

    if (false === $listid) {
        $listid = $config['Mailchimp_Subscription']['mailchimp_id'];
    }

    $mailchimp_api = new MCAPI($apikey);

    $mailchimp_merge_vars = array('');

    $mailchimp_response = array();

    $mailchimp_return = $mailchimp_api->lists($apikey);
    
    if ($mailchimp_api->errorCode) {
        $mailchimp_return['Error_code']    = $mailchimp_api->errorCode;
        $mailchimp_return['Error_message'] = $mailchimp_api->errorMessage;
    }

    return $mailchimp_return; 
}

function func_mailchimp_get_campaigns($listid = false, $apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }

    if (false === $listid) {
        $listid = $config['Mailchimp_Subscription']['mailchimp_id'];
    }

    $mailchimp_api = new MCAPI($apikey);

    $mailchimp_merge_vars = array('');

    $mailchimp_response = array();

    $mailchimp_return = $mailchimp_api->campaigns($mailchimp_merge_vars, 0, 25);
    if ($mailchimp_api->errorCode) {
    }

    return $mailchimp_return; 
}

function func_mailchimp_get_list_by_email($email, $apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }
    
    $mailchimp_api = new MCAPI($apikey);

    $mailchimp_return = array();

    $mailchimp_return = $mailchimp_api->listsForEmail($email);
    if ($mailchimp_api->errorCode) {
        $mailchimp_response['Error_code']    = $mailchimp_api->errorCode;
        $mailchimp_response['Error_message'] = $mailchimp_api->errorMessage;
        $mailchimp_return = array();
    }
        
    return $mailchimp_return;  
}
 
function func_mailchimp_subscribe($email_address, $user_info, $listid = false,  $apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }

    $mailchimp_api = new MCAPI($apikey);

    $mailchimp_merge_vars = $user_info;

    $mailchimp_response = array();

    $mailchimp_return = $mailchimp_api->listSubscribe(
        $listid,
        $email_address,
        $mailchimp_merge_vars,
        'html',
        true,
        true
    );

    if ($mailchimp_api->errorCode) {
        $mailchimp_response['Error_code']    = $mailchimp_api->errorCode;
        $mailchimp_response['Error_message'] = $mailchimp_api->errorMessage;

    } else {
        $mailchimp_response['Response'] = $mailchimp_return;
    }

    return $mailchimp_response;
}

function func_mailchimp_update($email_address, $listid, $mailchimp_updates, $apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }

    $mailchimp_api = new MCAPI($apikey);

    $mailchimp_merge_vars = $mailchimp_updates;
    $mailchimp_response = array();

    $mailchimp_return = $mailchimp_api->listUpdateMember($listid, $email_address, $mailchimp_merge_vars);

    if ($mailchimp_api->errorCode) {

        $mailchimp_response['Error_code']    = $mailchimp_api->errorCode;
        $mailchimp_response['Error_message'] = $mailchimp_api->errorMessage;

    } else {
        $mailchimp_response['Response'] = $mailchimp_return;
    }

    return $mailchimp_response;
}

function func_mailchimp_unsubscribe($email_address, $listid = false, $apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }

    $mailchimp_api = new MCAPI($apikey);

    $mailchimp_merge_vars = array('');
    $mailchimp_response = array();

    $mailchimp_return = $mailchimp_api->listUnsubscribe( $listid, $email_address, $mailchimp_merge_vars);

    if ($mailchimp_api->errorCode) {

        $mailchimp_response['Error_code']    = $mailchimp_api->errorCode;
        $mailchimp_response['Error_message'] = $mailchimp_api->errorMessage;

    } else {
        $mailchimp_response['Response'] = $mailchimp_return;
    }

    return $mailchimp_response;
}

function func_mailchimp_campaign_ecomm_add_order($cm_order,$email,$apikey = false)
{
    global $config;

    if (false === $apikey) {
        $apikey = $config['Mailchimp_Subscription']['mailchimp_apikey'];
    }

    $mailchimp_api = new MCAPI($apikey);
    $mailchimp_merge_vars = array('campaign_id'=>$cm_order['campaign_id']);
    
    $mailchimp_return = $mailchimp_api->campaigns($mailchimp_merge_vars,0,25);
    $list_id = $mailchimp_return[0]['list_id'];
    $tmp = $mailchimp_api->listMemberInfo($list_id, $email);
    $email_id = $tmp['id'];
    $cm_order['email_id'] = $email_id;
    
    $mailchimp_response = array();
    $mailchimp_return = $mailchimp_api->campaignEcommAddOrder($cm_order);
    if ($mailchimp_api->errorCode) {

        $mailchimp_response['Error_code']    = $mailchimp_api->errorCode;
        $mailchimp_response['Error_message'] = $mailchimp_api->errorMessage;

    } else {
        $mailchimp_response['Response'] = $mailchimp_return;
    }

    return $mailchimp_response;
}

function func_mailchimp_adv_campaign_commission($orderid, $mailchimp_campaignid)
{
    x_load('order');

    $order = func_order_data($orderid);

    $cm_order = array(
        'id'          => $order['order']['orderid'],
        'campaign_id' => $mailchimp_campaignid,
        'email_id'    => '123456',
        'total'       => $order['order']['total'],
        'order_date'  => $order['order']['date'],
        'shipping'    => $order['order']['shipping_cost'],
        'tax'         => $order['order']['tax'],
        'store_id'    => '1111111',
        'store_name'  => 'xcart',
        'plugin_id'   => '1214',
        'items'       => array(),
    );

 if (!empty($order['products']) && is_array($order['products'])){
    foreach ($order['products'] as $pr) {
        $cm_order['items'][] = array(
            'line_num'      => '',
            'product_id'    => $pr['productid'],
            'product_name'  => $pr['product'],
            'category_id'   => 1,
            'category_name' => $pr['product'],
            'qty'           => $pr['amount'],
            'cost'          => $pr['price'],
        );
    }
 }
    return func_mailchimp_campaign_ecomm_add_order($cm_order, $order['order']['email']);
}

function func_mailchimp_batch_subscribe($userinfo)
{
    global $shop_language, $mailchimp_subscription, $sql_tbl, $mc_newslists;

    if ( $userinfo['email'] && $mailchimp_subscription) {

        $mailchimp_user_info = array(
            'FName' => $userinfo['firstname'],
            'LName' => $userinfo['lastname'],
            'email' => $userinfo['email'],
            'phone' => $userinfo['b_phone'],
            'website' => $userinfo['url'],
            'address' => array(
                           'addr1'   => $userinfo['b_address'], 
                           'city'    => $userinfo['b_city'],
                           'state'   => $userinfo['d_state'],
                           'zip'     => $userinfo['b_zipcode'],
                           'country' => $userinfo['b_country'] 
                         )
        ); 
        foreach ($mailchimp_subscription as $key => $id) {
            func_mailchimp_subscribe(
                $userinfo['email'],
                $mailchimp_user_info,
                $key
            ); 
        }
    }
}

function func_mailchimp_resubscribe($profile_values)
{
    global $sql_tbl, $shop_language;
    global $existing_user,$mailchimp_subscription,$mc_newslists, $current_storefront;

    $mc_newslists = func_query("SELECT * FROM $sql_tbl[mailchimp_newslists] WHERE avail='Y' AND subscribe='Y' AND lngcode='$shop_language' and storefrontid = $current_storefront");
    if (!empty($mc_newslists)  ) {
        $mailchimp_user_info = array(
            'FName' => $profile_values['firstname'],
            'LName' => $profile_values['lastname'],
            'phone' => $profile_values['phone'],
            'website' => $profile_values['url'],
            'address' => array(
                           'addr1'   => $profile_values['b_address'], 
                           'city'    => $profile_values['b_city'],
                           'state'   => $profile_values['b_state'],
                           'zip'     => $profile_values['b_zipcode'],
                           'country' => $profile_values['b_country'] 
                         )
          
        ); 
        $mailchimp_cur_subs = array();
        $mailchimp_cur_subs = func_mailchimp_get_list_by_email($existing_user['email']);
        
        $mailchimp_ext_subs = array();
        $mailchimp_ext_subs = func_mailchimp_get_list_by_email($profile_values['email']);
  
        $mailchimp_subs_keys = array();
        if (is_array($mailchimp_subscription)) {
            $mailchimp_subs_keys = array_keys($mailchimp_subscription);
        }

        $mailchimp_delid = array_diff($mailchimp_cur_subs, $mailchimp_subs_keys);
        $mailchimp_insid = array_diff($mailchimp_subs_keys, $mailchimp_cur_subs,$mailchimp_ext_subs);
        $mailchimp_updid = array_intersect($mailchimp_cur_subs, $mailchimp_subs_keys);
        $mailchimp_updid = array_diff($mailchimp_updid, $mailchimp_ext_subs);

        foreach ($mailchimp_delid as $id) {
            $mailchimp_response = func_mailchimp_unsubscribe($existing_user['email'], $id); 
        }

        if (
            count($mailchimp_updid) > 0
            && ($existing_user['email'] != stripslashes($profile_values['email']) || $existing_user['firstname'] != $firstname  )
        ) {
            foreach ($mailchimp_updid as $id) {
                func_mailchimp_update(
                    $existing_user['email'],
                    $id,
                    array(
                        'EMAIL' => $email,
                        'FName' => $profile_values['firstname'],
                        'LName' => $profile_values['lastname'],
                        'phone' => $profile_values['phone'],
                        'website' => $profile_values['url'],
                        'address' => array(
                           'addr1'   => $profile_values['b_address'], 
                           'city'    => $profile_values['b_city'],
                           'state'   => $profile_values['b_state'],
                           'zip'     => $profile_values['b_zipcode'],
                           'country' => $profile_values['b_country'] 
                         )

                    )
                );
            }
        }
        
        foreach ($mailchimp_insid as $id) {
            $mailchimp_response = func_mailchimp_subscribe($profile_values['email'], $mailchimp_user_info, $id); 
//print_r($mailchimp_response);
        }

//exit;
    }
}

