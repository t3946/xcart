<?php
global $REQUEST_METHOD, $xcart_dir, $config, $login, $send_w9_form_name, $send_w9_form_message, $send_w9_form_subject, $send_w9_form_email, $current_storefront;
define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = ['send_w9_form_message'];

require "./auth.php";

require $xcart_dir . "/include/security.php";


if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}


if ($REQUEST_METHOD == 'POST' && !empty($w9_submit) && $w9_submit == 'Send') {

    if (!empty($send_w9_form_email)) {
        $oCustomer = \Xcart\Customer::model(['login' => $login]);

        $oMail = \Xcart\Mail::model()->
        setBody($send_w9_form_message)->
        setSubject($send_w9_form_subject);

        $oMail->addReplaceRule('{{requester_name}}', $send_w9_form_name)->
        addReplaceRule('{{requester_organization}}', empty($send_w9_form_organization) ? 'your organization' : $send_w9_form_organization)->
        addReplaceRule('{{userfirstname}}', $oCustomer->getCustomerFullName())->
        addReplaceRule('{{signature}}', func_get_signature($current_storefront));

        $oMail->setTo($send_w9_form_email)->setFrom($oCustomer->getCustomerFullName() . "<" . $config['Company']['site_administrator'] . ">");

        $oMail->addAttachment($xcart_dir . '/files/w9_form_files/' . $config['w9_form_file']);
        $oMail->sendEmail();
        $top_message["content"] = 'W-9 form has been sent';
        $top_message["type"] = "I";
    } else {
        $top_message["content"] = 'Please enter Email address';
        $top_message["type"] = "E";
    }
    func_header_location('send_W9_form.php');
}

$location[] = array('Send W-9 form', '');

$smarty->assign("main", "w9_send");
$smarty->assign_by_ref("config", $config);
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);