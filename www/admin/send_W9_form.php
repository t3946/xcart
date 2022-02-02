<?php
global $REQUEST_METHOD, $xcart_dir, $config, $login, $send_w9_form_name, $send_w9_form_message, $send_w9_form_subject, $send_w9_form_email, $current_storefront;

use Modules\Forms\Helpers\SnippetHelper;
use Xcart\App\Main\Xcart;

define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = ['send_w9_form_message'];

require "./auth.php";

require $xcart_dir . "/include/security.php";


if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}


if ($REQUEST_METHOD === 'POST' && !empty($w9_submit) && $w9_submit === 'Send') {

    if (!empty($send_w9_form_email) || !empty($send_w9_form_fax)) {

        $send_w9_form_message = str_replace([
            '{{requester_name}}',
            '{{requester_organization}}',
        ],[
            $send_w9_form_name,
            empty($send_w9_form_organization) ? 'your organization' : $send_w9_form_organization,
        ], $send_w9_form_message);

        $send_w9_form_message = SnippetHelper::render($send_w9_form_message, []);

        if (empty($send_w9_form_email))
        {
            $to = preg_replace("/[^0-9]/S", "", $send_w9_form_fax).'@faxage.com';
        } else {
            $to = $send_w9_form_email;
        }

        Xcart::app()->mail->raw(
            $to,
            $send_w9_form_subject,
            $send_w9_form_message,
            ['from' => $config['Company']['site_administrator']],
            [['file' => $xcart_dir . '/files/w9_form_files/' . $config['w9_form_file']]]
        );

        Xcart::app()->mail->raw(
            'helpdesk@s3stores.com',
            $send_w9_form_subject,
            $send_w9_form_message,
            ['from' => $config['Company']['site_administrator']],
            [['file' => $xcart_dir . '/files/w9_form_files/' . $config['w9_form_file']]]
        );

        $top_message["content"] = 'W-9 form has been sent';
        $top_message["type"] = "I";
    } else {
        $top_message["content"] = 'Please enter Email address or Fax#';
        $top_message["type"] = "E";
    }
    func_header_location('send_W9_form.php');
}

$location[] = array('Send W-9 form', '');

$smarty->assign("main", "w9_send");
$smarty->assignByRef("config", $config);
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);